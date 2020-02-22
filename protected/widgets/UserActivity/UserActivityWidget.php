<?php

/**
 * Виджет показа статистики активности пользователя по дням в виде тепловой карты
 * Class UserActivityWidget.
 */
class UserActivityWidget extends CWidget
{
    public $userId; // id пользователя. Если null - все пользователи
    public $periodStart; // с какой даты показывать (Y-m-d). По умолч. за 3 месяца до сегодня
    public $periodEnd; // до какой даты (Y-m-d). По умолчанию до сегодня
    public $role; // Роль пользователя. Задается, если $userId не указан.
    public $template = 'default'; // шаблон отображения

    public function init()
    {
        parent::init();
        if (is_null($this->periodStart)) {
            $this->periodStart = (new DateTime($this->periodStart))->sub(new DateInterval('P3M'))->format('Y-m-d');
        }
        if (is_null($this->periodEnd)) {
            $this->periodEnd = (new DateTime($this->periodEnd))->format('Y-m-d');
        }
    }

    public function run()
    {
        $activityData = [];

        $activityRows = $this->getActivityRawData($this->userId);

        if (0 == sizeof($activityRows)) {
            return;
        }

        foreach ($activityRows as $row) {
            $activityData[$row['date']][] = [
                'action' => $row['action'],
            ];
        }

        $availableDates = array_keys($activityData);
        $dateStart = new DateTime(reset($availableDates));
        $dateFinish = new DateTime(end($availableDates));

        $rankByDay = $this->getRanksByDays($activityData, $dateStart, $dateFinish);

        $firstDateInCalendar = $this->calculateFirstDateInCalendar($dateStart);

        $this->render($this->template, [
            'rankByDay' => $rankByDay,
            'firstDateInCalendar' => $firstDateInCalendar,
        ]);
    }

    /**
     * Выбирает из базы данные по активности пользователя/пользователей в виде строк.
     *
     * @param int|null $userId
     * @param int|null $role
     *
     * @return array
     */
    private function getActivityRawData($userId = null, $role = null): array
    {
        /** @var CDbCommand $activityRowsCommand */
        $activityRowsCommand = Yii::app()->db
            ->createCommand()
            ->select('DATE(ts) date, action')
            ->from('{{user_activity}} a')
            ->where('DATE(ts)>=:start AND DATE(ts)<=:finish', [
                ':start' => $this->periodStart,
                ':finish' => $this->periodEnd,
            ])
            ->order('id ASC');

        if (!is_null($userId)) {
            $activityRowsCommand->andWhere('userId=:userId', [
                ':userId' => $userId,
            ]);
        } elseif (!is_null($role)) {
            $activityRowsCommand->leftJoin('{{user}} u', 'u.id = a.userId')
                ->andWhere('u.role = :role', [
                    ':role' => $role,
                ]);
        }

        $activityRows = $activityRowsCommand->queryAll();

        return $activityRows;
    }

    /**
     * Поскольку первый день в выборке может быть не первым днем недели, получим ближайший
     * первый день недели в прошлом от первого дня выборки.
     *
     * @param DateTime $dateStart
     *
     * @return DateTime|int
     *
     * @throws Exception
     */
    private function calculateFirstDateInCalendar(DateTime $dateStart)
    {
        $weekdayOfFirstDate = (int) $dateStart->format('w');

        $firstDateInCalendar = (0 == $weekdayOfFirstDate) ?
            $dateStart :
            (clone $dateStart)->sub(new DateInterval('P' . $weekdayOfFirstDate . 'D'));

        return $firstDateInCalendar;
    }

    /**
     * @param array    $activityData
     * @param DateTime $dateStart
     * @param DateTime $dateFinish
     *
     * @return array
     *
     * @throws Exception
     */
    private function getRanksByDays(array $activityData, $dateStart, $dateFinish): array
    {
        $rankByDay = [];

        $currentDate = clone $dateStart;

        while ($currentDate <= $dateFinish) {
            $currentDateFormatted = $currentDate->format('Y-m-d');

            $dailyRank = 0;
            if (is_array($activityData[$currentDateFormatted])) {
                foreach ($activityData[$currentDateFormatted] as $activity) {
                    $dailyRank += UserActivity::getActionRate($activity['action']);
                }
            }
            $rankByDay[$currentDateFormatted] = $dailyRank;
            $currentDate->add(new DateInterval('P1D'));
        }

        return $rankByDay;
    }
}
