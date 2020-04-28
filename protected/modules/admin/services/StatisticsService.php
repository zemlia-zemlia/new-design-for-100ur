<?php

use App\helpers\DateHelper;
use App\models\Answer;
use App\models\Question;
use App\models\User;

/**
 * Класс для получения различных статистик
 * Class StatisticsService.
 */
class StatisticsService
{
    public function getYuristsActivityStats()
    {
        $yuristActivityStatsRows = Yii::app()->db->createCommand()
            ->select('COUNT(*) counter, DATE(lastActivity) lastDate')
            ->from('{{user}}')
            ->where('role=:role', [':role' => User::ROLE_JURIST])
            ->group('lastDate')
            ->order('lastDate DESC')
            ->limit(10)
            ->queryAll();
        $yuristActivityStats = [];

        foreach ($yuristActivityStatsRows as $row) {
            $yuristActivityStats[$row['lastDate']] = $row['counter'];
        }

        $yuristActivityStats = DateHelper::fillEmptyDatesArrayByDefaultValues($yuristActivityStats);

        ksort($yuristActivityStats);

        return $yuristActivityStats;
    }

    /**
     * @param int $days
     *
     * @return int
     */
    public function getPublishedQuestionsNumberInPeriod($days = 30): int
    {
        $questionNumber = Yii::app()->db->createCommand()
            ->select('count(*)')
            ->from('{{question}}')
            ->where('status IN (:status1, :status2) AND createDate > NOW() - INTERVAL :interval DAY', [
                ':status1' => Question::STATUS_CHECK,
                ':status2' => Question::STATUS_PUBLISHED,
                ':interval' => (int) $days,
            ])
            ->queryScalar();

        return $questionNumber;
    }

    /**
     * Число вопросов, заданных за $days дней, на которые даны ответы.
     *
     * @param int $days
     *
     * @return int
     *
     * @throws CException
     */
    public function getCountOfAnswersForRecentQuestions($days): int
    {
        $questionsWithAnswers = Yii::app()->db->createCommand()
            ->select('count(*)')
            ->from('{{question}} q')
            ->leftJoin('{{answer}} a', 'a.questionId = q.id')
            ->where('q.status IN (:status1, :status2) AND q.createDate > NOW() - INTERVAL :interval DAY AND a.status!=:answerSpam', [
                ':status1' => Question::STATUS_CHECK,
                ':status2' => Question::STATUS_PUBLISHED,
                ':interval' => (int) $days,
                ':answerSpam' => Answer::STATUS_SPAM,
            ])
            ->group('q.id')
            ->queryColumn();

        return sizeof($questionsWithAnswers);
    }

    /**
     * @param int $days
     *
     * @return int
     *
     * @throws CException
     *
     * @todo Сделать, чтобы учитывался только первый ответ на вопрос
     */
    public function getAverageDiffBetweenQuestionAndAnswer($days): int
    {
        $diffsInSeconds = Yii::app()->db->createCommand()
            ->select('TIME_TO_SEC(TIMEDIFF(a.datetime, q.publishDate)) delta')
            ->from('{{question}} q')
            ->leftJoin('{{answer}} a', 'a.questionId = q.id')
            ->where('q.status IN (:status1, :status2) AND q.createDate > NOW() - INTERVAL :interval DAY AND a.status!=:answerSpam', [
                ':status1' => Question::STATUS_CHECK,
                ':status2' => Question::STATUS_PUBLISHED,
                ':interval' => (int) $days,
                ':answerSpam' => Answer::STATUS_SPAM,
            ])
            ->group('a.id')
            ->queryColumn();

        return (count($diffsInSeconds) > 0) ?
            round((array_sum($diffsInSeconds) / count($diffsInSeconds)) / 3600, 1) :
            0;
    }
}
