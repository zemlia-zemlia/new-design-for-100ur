<?php

/**
 * Консольная команда для загрузки активных пользователей в Sendpulse через API.
 */
class ExportToSendpulseCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(['active100' => 1]);
        $criteria->addInCondition('role', [User::ROLE_BUYER, User::ROLE_CLIENT, User::ROLE_JURIST, User::ROLE_PARTNER]);
        $users = User::model()->findAll($criteria);
        $usersCount = sizeof($users);

        foreach ($users as $index => $user) {
            echo $index . '/' . $usersCount . ': ' . $user->email . PHP_EOL;
            $user->addToSendpulse();
        }
    }
}
