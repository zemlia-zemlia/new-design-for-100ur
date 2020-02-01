<?php
/**
 * Репозиторий для выборок вопросов из базы
 * Class QuestionRepository
 */

class QuestionRepository
{
    protected $cacheTime = 600;
    protected $limit = 10;

    /**
     * @param int $cacheTime
     * @return QuestionRepository
     */
    public function setCacheTime($cacheTime)
    {
        $this->cacheTime = $cacheTime;
        return $this;
    }

    /**
     * @param int $limit
     * @return QuestionRepository
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Возвращает массив вопросов, на которые дал ответ юрист
     * @param User $user
     * @return array
     */
    public function findRecentQuestionsByJuristAnswers(User $user): array
    {
        $questions = Yii::app()->db->cache($this->cacheTime)->createCommand()
            ->select('q.id id, q.publishDate date, q.title title')
            ->from('{{question}} q')
            ->leftJoin('{{answer}} a', 'q.id=a.questionId')
            ->where('a.id IS NOT NULL AND q.status IN (:status1, :status2) AND a.authorId = :authorId', [
                ':status1' => Question::STATUS_PUBLISHED,
                ':status2' => Question::STATUS_CHECK,
                ':authorId' => $user->id])
            ->limit($this->limit)
            ->order('a.datetime DESC')
            ->queryAll();

        return $questions;
    }

    /**
     * Возвращает массив вопросов, заданных пользователем
     * @param User $user
     * @return array
     */
    public function findRecentQuestionsByClient(User $user): array
    {
        $questions = Yii::app()->db->cache($this->cacheTime)->createCommand()
            ->select('q.id id, q.publishDate date, q.title title')
            ->from('{{question}} q')
            ->where('q.authorId=:authorId AND q.status IN (:status1, :status2)', [
                ':status1' => Question::STATUS_PUBLISHED,
                ':status2' => Question::STATUS_CHECK,
                ':authorId' => $user->id])
            ->limit($this->limit)
            ->order('q.publishDate DESC')
            ->queryAll();

        return $questions;
    }
}
