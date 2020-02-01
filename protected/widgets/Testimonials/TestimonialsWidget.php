<?php

class TestimonialsWidget extends CWidget
{
    public $limit = 3;
    public $template = 'default'; // представление виджета по умолчанию
    public $cacheTime = 600; // время кеширования по умолчанию

    public function run()
    {
        $criteria = new CDbCriteria();
        $criteria->limit = $this->limit;
        $criteria->order = 't.id DESC';
        $criteria->with = ['question', 'author'];
        $criteria->addColumnCondition(['t.status' => Comment::STATUS_CHECKED, 't.type' => Comment::TYPE_USER]);

        $testimonials = Comment::model()->findAll($criteria);

        $this->render($this->template, [
            'testimonials' => $testimonials,
        ]);
    }
}
