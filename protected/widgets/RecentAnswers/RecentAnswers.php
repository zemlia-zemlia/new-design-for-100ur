<?php

// виджет для вывода последних ответов

class RecentAnswers extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $cacheTime = 600; // время кеширования
    public $limit = 5;
    
    public function run()
    {
        $criteria = new CDbCriteria();
        $criteria->limit = $this->limit;
        $criteria->order = 'datetime DESC';
        $criteria->with = array('question', 'author');
        
        $answers = Answer::model()->findAll($criteria);
                
        $this->render($this->template, array(
            'answers'  =>  $answers,
        ));
    }
}
?>