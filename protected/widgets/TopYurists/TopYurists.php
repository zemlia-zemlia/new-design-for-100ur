<?php

// виджет для вывода топа юристов

class TopYurists extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $cacheTime = 600; // время кеширования по умолчанию
    public $limit = 6; // лимит выводимых юристов

    public function run()
    {
        $criteria = new CDbCriteria;
        
        $criteria->addColumnCondition(array('role' => User::ROLE_JURIST));
        $criteria->addColumnCondition(array('role' => User::ROLE_OPERATOR), 'AND', 'OR');
        $criteria->addColumnCondition(array('active'=>1));
        $criteria->order = "RAND()";
        $criteria->limit = $this->limit;
        
        $users = User::model()->cache($this->cacheTime)->findAll($criteria);
        
        $this->render($this->template, array(
            'users' =>  $users,
        ));
    }
}
?>