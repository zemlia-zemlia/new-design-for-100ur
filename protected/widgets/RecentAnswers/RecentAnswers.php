<?php

use App\models\Answer;

/**
 *  виджет для вывода последних ответов, юристы должны быть уникальными.
 */
class RecentAnswers extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $cacheTime = 600; // время кеширования
    public $limit = 6;

    public function run()
    {
        $answers = Yii::app()->cache->get('recentAnswers');

        if (false === $answers) {




        $criteria  = new \CDbCriteria();
        $criteria->alias = 'q';
        $criteria->join = 'LEFT JOIN {{answer}} a ON q.id = a.questionId LEFT JOIN {{user}} u ON a.authorId = u.id AND u.lastAnswer = a.datetime';

        $criteria->condition = 'u.lastAnswer IS NOT NULL AND u.active100=1 AND a.status!=:spamStatus';
        $criteria->params = ['spamStatus' => Answer::STATUS_SPAM];
        $criteria->order = 'u.lastAnswer DESC';
        $criteria->limit = $this->limit;

        $questions = \App\models\Question::model()->findAll($criteria);

//             храним результаты выборки ответов в кеше
            Yii::app()->cache->set('recentAnswers', $answers, $this->cacheTime);
        }

        $this->render($this->template, [
            'questions' => $questions,
        ]);
    }
}
