<?php


class UserActivityWidget extends CWidget
{
    public $userId;
    public $periodStart;
    public $periodEnd;
    public $template = 'default';

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

        $activityRows = Yii::app()->db
            ->createCommand()
            ->select('DATE(ts) date, action')
            ->from('{{user_activity}}')
            ->where('userId=:userId AND DATE(ts)>=:start AND DATE(ts)<=:finish', [
                ':userId' => $this->userId,
                ':start' => $this->periodStart,
                ':finish' => $this->periodEnd,
            ])
            ->order('id ASC')
            ->queryAll();

        if (sizeof($activityRows) == 0) {
            return;
        }

        foreach ($activityRows as $row) {
            $activityData[$row['date']][] = [
                'action' => $row['action'],
            ];
        }

        $rankByDay = [];
        $availableDates = array_keys($activityData);

        $dateStart = new DateTime(reset($availableDates));
        $dateFinish = new DateTime(end($availableDates));

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

        $weekdayOfFirstDate = (int)$dateStart->format('w');

        $firstDateInCalendar = ($weekdayOfFirstDate == 0) ?
            $weekdayOfFirstDate :
            (clone $dateStart)->sub(new DateInterval('P' . $weekdayOfFirstDate . 'D'));


        $this->render($this->template, [
            'rankByDay' => $rankByDay,
            'firstDateInCalendar' => $firstDateInCalendar,
        ]);
    }
}
