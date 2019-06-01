<?php

/**
 * Отображение недавних вопросов с максимальным числом ответов
 * Class PopularQuestions
 */
class PopularQuestions extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $cacheTime = 600; // время кеширования по умолчанию
    public $showPayed = false; // показывать платные вопросы

    public function run()
    {
        $priceCondition = ($this->showPayed === true) ? 'price > 0' : 'price = 0';
        $questions = Yii::app()->db->cache($this->cacheTime)->createCommand()
            ->select('q.id, q.title, q.createDate, q.price, COUNT(q.id) counter')
            ->from('{{question}} q')
            ->leftJoin('{{answer}} a', 'q.id=a.questionId')
            ->where('q.createDate > NOW() - INTERVAL 7 DAY AND ' . $priceCondition . ' AND a.status!=:status', [
                ':status' => Answer::STATUS_SPAM,
            ])
            ->group('q.id')
            ->order('id DESC')
            ->limit(10)
            ->queryAll();

        $this->render($this->template, [
            'questions' => $questions,
        ]);
    }
}
