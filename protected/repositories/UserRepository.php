<?php

namespace App\repositories;

use App\models\User;
use CDbConnection;
use CDbCriteria;
use Yii;

class UserRepository
{
    /** @var CDbConnection */
    private $dbConnection;

    public function __construct()
    {
        $this->dbConnection = Yii::app()->db;
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function getById(int $id): ?User
    {
        return User::model()->findByPk($id);
    }

    /**
     * @param array $attributes
     * @return User|null
     */
    public function getByAttributes(array $attributes): ?User
    {
        return User::model()->findByAttributes($attributes);
    }

    /**
     * @param string $email
     * @param string $code
     */
    public function getUserByEmailAndConfirmationCode($email, $code): ?User
    {
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(['email' => $email])
            ->addColumnCondition(['confirm_code' => $code])
            ->addColumnCondition(['active100' => 0]);
        $criteria->limit = 1;

        //находим пользователя с данным мейлом и кодом подтверждения
        return User::model()->find($criteria);
    }

    /**
     * @return mixed
     */
    public function saveLastAnswerTs(User $user, string $lastAnswerTs): int
    {
        return $this->dbConnection->createCommand()
            ->update('{{user}}', ['lastAnswer' => $lastAnswerTs], 'id=:id', [':id' => $user->id]);
    }

    /**
     * последний запрос на смену статуса.
     *
     * @throws \CException
     */
    public function getLastChangeStatusRequestAsArray(User $user): ?array
    {
        return $this->dbConnection->createCommand()
            ->select('*')
            ->from('{{userStatusRequest}}')
            ->where('yuristId=:id AND isVerified=0', [':id' => $user->id])
            ->order('id DESC')
            ->limit(1)
            ->queryAll();
    }

    /**
     * Получение числа вопросов, заданных пользователем за последние часы.
     *
     * @param int $intervalHours Количество часов
     *
     * @return mixed
     *
     * @throws \CException
     */
    public function getRecentQuestionCount(User $user, $intervalHours): int
    {
        return $this->dbConnection->createCommand()
            ->select('COUNT(id) counter')
            ->from('{{question}}')
            ->where('authorId=:id AND createDate > NOW() - INTERVAL :hours HOUR', [':id' => $user->id, ':hours' => $intervalHours])
            ->queryScalar();
    }
}
