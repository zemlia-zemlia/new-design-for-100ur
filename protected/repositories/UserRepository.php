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
     * @param string $email
     * @param string $code
     * @return User|null
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
     * @param User $user
     * @param string $lastAnswerTs
     * @return mixed
     */
    public function saveLastAnswerTs(User $user, string $lastAnswerTs): int
    {
        return $this->dbConnection->createCommand()
            ->update('{{user}}', ['lastAnswer' => $lastAnswerTs], 'id=:id', [':id' => $user->id]);
    }

    /**
     * последний запрос на смену статуса
     * @param User $user
     * @return array|null
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
}