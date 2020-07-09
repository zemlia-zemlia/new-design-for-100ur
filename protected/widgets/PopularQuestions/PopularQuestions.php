<?php

use App\models\Answer;

/**
 * Отображение недавних вопросов с максимальным числом ответов
 * Class PopularQuestions.
 */
class PopularQuestions extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $cacheTime = 600; // время кеширования по умолчанию
    public $showPayed = false; // показывать платные вопросы
    public $intervalDays = 100; // период выборки

    public function run()
    {
        $priceCondition = (true === $this->showPayed) ? 'q.price > 0' : 'q.price = 0';

        $criteria  = new \CDbCriteria();
        $criteria->alias = 'q';
        $criteria->distinct = true;
        $criteria->join = 'LEFT JOIN {{answer}} a ON q.id = a.questionId LEFT JOIN {{user}} u ON a.authorId = u.id AND u.lastAnswer = a.datetime';

        $criteria->condition = 'q.createDate > NOW() - INTERVAL :interval DAY AND u.lastAnswer IS NOT NULL  AND ' . $priceCondition . '  AND u.active100=1 AND a.status!=:spamStatus';
        $criteria->params = ['spamStatus' => Answer::STATUS_SPAM, ':interval' => $this->intervalDays];
        $criteria->order = 'u.lastAnswer DESC';


        $questions = \App\models\Question::model()->cache($this->cacheTime)->findAll($criteria);

        $questions = array_slice($questions, 0, 10);

        $this->render($this->template, [
            'questions' => $questions,
        ]);
    }
}
