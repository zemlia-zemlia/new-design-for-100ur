<?php

namespace App\repositories;

use App\dto\QuestionRssItemDto;
use App\models\Answer;
use App\models\Question;
use App\models\User;
use CDbCriteria;
use CException;
use Yii;

/**
 * Репозиторий для выборок вопросов из базы
 * Class App\repositories\QuestionRepository.
 */
class QuestionRepository
{
    protected $cacheTime = 600;
    protected $limit = 10;

    /**
     * @param int $cacheTime
     *
     * @return QuestionRepository
     */
    public function setCacheTime($cacheTime)
    {
        $this->cacheTime = $cacheTime;

        return $this;
    }

    /**
     * @param int $limit
     *
     * @return QuestionRepository
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Возвращает массив вопросов, на которые дал ответ юрист
     *
     * @throws CException
     */
    public function findRecentQuestionsByJuristAnswers(User $user): array
    {
        $questions = Yii::app()->db->cache($this->cacheTime)->createCommand()
            ->select('q.id id, q.publishDate date, q.title title')
            ->from('{{question}} q')
            ->leftJoin('{{answer}} a', 'q.id=a.questionId')
            ->where('a.id IS NOT NULL AND q.status IN (:status1, :status2) AND a.authorId = :authorId AND a.status IN (:status3, :status4)', [
                ':status1' => Question::STATUS_PUBLISHED,
                ':status2' => Question::STATUS_CHECK,
                ':status3' => Answer::STATUS_NEW,
                ':status4' => Answer::STATUS_PUBLISHED,
                ':authorId' => $user->id,])
            ->limit($this->limit)
            ->order('a.datetime DESC')
            ->queryAll();

        return $questions;
    }

    /**
     * Возвращает массив вопросов, заданных пользователем
     *
     * @throws CException
     */
    public function findRecentQuestionsByClient(User $user): array
    {
        $questions = Yii::app()->db->cache($this->cacheTime)->createCommand()
            ->select('q.id id, q.publishDate date, q.title title')
            ->from('{{question}} q')
            ->where('q.authorId=:authorId AND q.status IN (:status1, :status2)', [
                ':status1' => Question::STATUS_PUBLISHED,
                ':status2' => Question::STATUS_CHECK,
                ':authorId' => $user->id,])
            ->limit($this->limit)
            ->order('q.publishDate DESC')
            ->queryAll();

        return $questions;
    }

    /**
     * @throws CException
     */
    public function countForModerate(): int
    {
        $allQuestion = Yii::app()->db->cache($this->cacheTime)->createCommand()
            ->select('COUNT(*) counter')
            ->from('{{question}}')
            ->where('isModerated=0 AND status IN (:status1, :status2, :status3)', [
                ':status1' => Question::STATUS_CHECK,
                ':status2' => Question::STATUS_PUBLISHED,
                ':status3' => Question::STATUS_MODERATED,
            ])
            ->queryRow();

        return $allQuestion['counter'];
    }

    /**
     * @throws CException
     */
    public function countNoCat(): int
    {
        $questionsCountRows = Yii::app()->db->cache($this->cacheTime)->createCommand()
            ->select('COUNT(*) counter')
            ->from('{{question}} q')
            ->leftJoin('{{question2category}} q2c', 'q.id=q2c.qId')
            ->where('q2c.cId IS NULL AND q.status IN(' . Question::STATUS_PUBLISHED . ',' . Question::STATUS_CHECK . ', ' . Question::STATUS_MODERATED . ')')
            ->queryRow();

        return $questionsCountRows['counter'];
    }

    /**
     * Возвращает последний вопрос пользователя.
     */
    public function getLastQuestionOfUser(User $user): ?Question
    {
        $questionCriteria = new CDbCriteria();
        $questionCriteria->addCondition('authorId=' . $user->id);
        $questionCriteria->order = 'id DESC';
        $questionCriteria->limit = 1;

        return Question::model()->find($questionCriteria);
    }

    /**
     * Возвращает массив опубликованных вопросов.
     *
     * @param int $limit
     * @param string $order
     * @param string $with
     * @param int $cacheTime
     *
     * @return Question[]|null
     */
    public function findRecentPublishedQuestions(
        $limit = 40,
        $order = 'publishDate DESC',
        $with = 'answersCount',
        $cacheTime = 600
    ): array
    {
        $criteria = new CDbCriteria();
        $criteria->limit = $limit;
        $criteria->with = $with;
        $criteria->addCondition('status IN (' . Question::STATUS_PUBLISHED . ', ' . Question::STATUS_CHECK . ')');
        $criteria->order = $order;

        return Question::model()->cache($cacheTime)->findAll($criteria);
    }

    /**
     * Получает массив данных с годами и месяцами, за которые есть опубликованные вопросы.
     *
     * @return array Пример: [2019 => [11,12], 2020 => [1,2,3]]
     */
    public function getYearsAndMonthsWithQuestions(): array
    {
        $datesArray = [];
        $datesRows = Yii::app()->db->createCommand()
            ->select('YEAR(publishDate) year, MONTH(publishDate) month')
            ->from('{{question}}')
            ->where('status IN (:status1, :status2)', [':status1' => Question::STATUS_CHECK, ':status2' => Question::STATUS_PUBLISHED])
            ->group('year, month')
            ->order('year DESC, month DESC')
            ->queryAll();

        foreach ($datesRows as $row) {
            if ($row['year'] && $row['month']) {
                $datesArray[$row['year']][] = $row['month'];
            }
        }

        return $datesArray;
    }

    /**
     * @param array $attributes
     * @return Question|null
     */
    public function getQuestionByParams(array $attributes): ?Question
    {
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition($attributes);

        return Question::model()->find($criteria);
    }

    /**
     * Возвращает массив последних опубликованных вопросов с полем "число ответов"
     * @param int $limit
     * @param int $cacheTime
     * @return QuestionRssItemDto[]
     * @throws CException
     */
    public function getPublishedQuestionsWithAnswersCountAsArray(int $limit, int $cacheTime): array
    {
        return $this->getPublishedQuestionsArray($limit, $cacheTime, false);
    }

    /**
     * Возвращает массив последних опубликованных вопросов с ответами
     * @param int $limit
     * @param int $cacheTime
     * @return QuestionRssItemDto[]
     * @throws CException
     */
    public function getPublishedQuestionsWithAnswersAsArray(int $limit, int $cacheTime): array
    {
        return $this->getPublishedQuestionsArray($limit, $cacheTime, true);
    }

    /**
     * Возвращает массив DTO объектов QuestionRssItemDto
     * @param int $limit
     * @param int $cacheTime
     * @param bool $onlyWithAnswers
     * @return array
     * @throws CException
     */
    protected function getPublishedQuestionsArray(int $limit, int $cacheTime, $onlyWithAnswers = true):array
    {
        /** @var \CDbCommand $dbCommand */
        $dbCommand = Yii::app()->db->cache($cacheTime)->createCommand()
            ->select('q.id, q.title, q.publishDate, q.createDate, q.questionText, COUNT(*) answersCount')
            ->from('{{question}} q')
            ->leftJoin('{{answer}} a', 'a.questionId=q.id')
            ->where(['in', 'q.status', [Question::STATUS_PUBLISHED, Question::STATUS_CHECK]])
            ->group('q.id')
            ->order('q.publishDate DESC, q.id DESC')
            ->limit($limit);

        if ($onlyWithAnswers == true) {
            $dbCommand = $dbCommand->andWhere('a.id IS NOT NULL');
        }

        $questions = $dbCommand->queryAll();

        $questionDtos = [];
        foreach ($questions as $question) {
            $questionDto = new QuestionRssItemDto();
            $questionDto->setId($question['id'])
                ->setTitle($question['title'])
                ->setCreateDate($question['createDate'])
                ->setPublishDate($question['publishDate'])
                ->setQuestionText($question['questionText'])
                ->setAnswersCount($question['answersCount']);
            $questionDtos[] = $questionDto;
        }

        return $questionDtos;
    }
}
