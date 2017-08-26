<?php

// виджет для вывода топа юристов

class TopYurists extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $cacheTime = 600; // время кеширования по умолчанию
    public $limit = 6; // лимит выводимых юристов

    public function run()
    {
//        $criteria = new CDbCriteria;
//                
//        $criteria->addInCondition('role', array(User::ROLE_JURIST, User::ROLE_OPERATOR, User::ROLE_CALL_MANAGER));
//        $criteria->addColumnCondition(array('active100'=>1));
//        $criteria->order = "RAND()";
//        $criteria->limit = $this->limit;
//        $criteria->with = array('categories', 'settings', 'settings.town');
//        

        // найдем 6 рандомных юристов

        $users = Yii::app()->db->cache($this->cacheTime)->createCommand()
                ->select('u.*, s.status yuristStatus, t.name town')
                ->from('{{user}} u')
                ->leftJoin('{{yuristSettings}} s', 's.yuristId = u.id')
                ->leftJoin('{{town}} t', 't.id = u.townId')
                ->where('role = ' . User::ROLE_JURIST . ' AND active100=1 AND avatar IS NOT NULL AND s.status!=0')
                ->limit($this->limit)
                ->order('RAND()')
                ->queryAll();

        
        $this->render($this->template, array(
            'users' =>  $users,
        ));
    }
}
?>