<?php

// виджет для вывода топа юристов

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
                $users = $this->getRandom();
                break;
            case self::FETCH_RANKED:
                $users = $this->getRanked();
                break;
        }

        $this->render($this->template, array(
            'users' => $users,
        ));
    }

    /**
     * найдем рандомных юристов
     * @return array
     */
    protected function getRandom(): array
    {
        $users = Yii::app()->db->cache($this->cacheTime)->createCommand()
            ->select('u.*, s.status yuristStatus, s.*')
            ->from('{{user}} u')
            ->leftJoin('{{yuristSettings}} s', 's.yuristId = u.id')
            ->where('role = ' . User::ROLE_JURIST . ' AND active100=1 AND karma>0 AND avatar IS NOT NULL AND s.status!=0')
            ->limit($this->limit)
            ->order('RAND()')
            ->queryAll();

        return $users;
    }

    /**
     * поиск юристов, ранжированный
     * @return array
     */
    protected function getRanked(): array
    {
        $users = Yii::app()->db->cache($this->cacheTime)->createCommand()
            ->select('u.*, s.status yuristStatus, s.*, COUNT(*) answersCounter')
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

        return $users;
    }
}
