<?php


namespace App\repositories;


use App\models\User;
use CDbCriteria;

class UserRepository
{
    /**
     * @param string $email
     * @param string $code
     * @return User|null
     */
    public function getUserByEmailAndConfirmationCode($email, $code):?User
    {
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(['email' => $email])
            ->addColumnCondition(['confirm_code' => $code])
            ->addColumnCondition(['active100' => 0]);
        $criteria->limit = 1;

        //находим пользователя с данным мейлом и кодом подтверждения
        return User::model()->find($criteria);
    }
}