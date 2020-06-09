<?php

// виджет для вывода топа юристов

use App\models\Answer;
use App\models\User;

class TopYurists extends CWidget
{
    const FETCH_RANDOM = 0;
    const FETCH_RANKED = 1;

    public $template = 'default'; // представление виджета по умолчанию
    public $cacheTime = 600; // время кеширования по умолчанию
    public $limit = 6; // лимит выводимых юристов
    public $fetchType = self::FETCH_RANDOM;
    public $interval = 30; // за сколько дней выбираем ответы юристов

    public function run()
    {
        // В зависимости от заданного типа выборки получаем данные по разному
        switch ($this->fetchType) {
            case self::FETCH_RANDOM:
            default:
                $usersData = $this->getRandom();
                break;
            case self::FETCH_RANKED:
                $usersData = $this->getRanked();
                break;
        }

        $this->render($this->template, [
            'usersData' => $usersData,
        ]);
    }

    /**
     * найдем рандомных юристов.
     */
    protected function getRandom(): array
    {
        $usersCriteria = new CDbCriteria();
        $usersCriteria->order = 'RAND()';
        $usersCriteria->limit = $this->limit;
        $usersCriteria->with = 'settings';
        $usersCriteria->condition = 'role = ' . User::ROLE_JURIST . ' AND active100=1 AND karma>0 AND avatar IS NOT NULL AND settings.status!=0';
        $users = User::model()->findAll($usersCriteria);

        return $users;
    }

    /**
     * поиск юристов, ранжированный.
     * Возвращаемый массив: [[user => User, answersCount => N]].
     *
     * @throws CException
     */
    protected function getRanked(): array
    {
        $usersData = [];

        $userIdsAndAnswersCounts = Yii::app()->db->cache($this->cacheTime)
            ->createCommand()
            ->select('u.id, COUNT(*) answersCounter')
            ->from('{{user}} u')
            ->leftJoin('{{yuristSettings}} s', 's.yuristId = u.id')
            ->leftJoin('{{answer}} a', 'a.authorId = u.id')
            ->where('role = ' . User::ROLE_JURIST . ' AND active100=:active AND u.karma>0 AND avatar IS NOT NULL AND s.status!=0 AND a.status != :statusSpam AND a.datetime > NOW()-INTERVAL :interval DAY', [
                ':active' => 1,
                ':statusSpam' => Answer::STATUS_SPAM,
                ':interval' => $this->interval,
            ])
            ->group('u.id')
            ->limit($this->limit)
            ->order('answersCounter DESC')
            ->queryAll();

        $userIds = array_column($userIdsAndAnswersCounts, 'id');

        $usersObjects = User::model()->cache($this->cacheTime)
            ->with('settings')
            ->findAllByAttributes(['id' => $userIds]);

        foreach ($userIdsAndAnswersCounts as $idAndCount) {
            $userData = [];
            $userData['answersCount'] = $idAndCount['answersCounter'];
            foreach ($usersObjects as $user) {
                if ($user->id == $idAndCount['id']) {
                    $userData['user'] = $user;
                    break;
                }
            }
            $usersData[] = $userData;
        }

        return $usersData;
    }
}
