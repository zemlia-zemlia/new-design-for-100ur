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
            // выберем ответы за последний месяц, с вопросами и авторами
            $answersRows = Yii::app()->db->createCommand()
                    ->select('q.title questionTitle, q.id questionId, q.price questionPrice, q.payed questionPayed, a.answerText, a.datetime answerTime, a.authorId, u.name authorName, u.name2 authorName2, u.lastName authorLastName, u.lastActivity')
                    ->from('{{answer}} a')
                    ->leftJoin('{{user}} u', 'a.authorId = u.id AND u.lastAnswer = a.datetime')
                    ->leftJoin('{{question}} q', 'q.id = a.questionId')
                    ->where('u.lastAnswer IS NOT NULL AND u.active100=1 AND a.status!=:spamStatus', ['spamStatus' => Answer::STATUS_SPAM])
                    ->order('u.lastAnswer DESC')
                    ->limit($this->limit)
                    ->queryAll();

            $answers = [];

            foreach ($answersRows as $row) {
                $answers[$row['authorId']] = $row;
            }
            // храним результаты выборки ответов в кеше
            Yii::app()->cache->set('recentAnswers', $answers, $this->cacheTime);
        }
        $this->render($this->template, [
            'answers' => $answers,
        ]);
    }
}
