<?php

use App\models\UserActivity;

class CreateUserActivitiesCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $logRecords = Yii::app()->db
            ->createCommand("SELECT * FROM {{log}} where class='User'")
            ->queryAll();

        foreach ($logRecords as $logRecord) {
            $activityRow = [
                'ts' => $logRecord['created'],
                'userId' => $logRecord['subjectId'],
            ];

            if (stristr($logRecord['message'], 'залогинился')) {
                $activityRow['action'] = UserActivity::ACTION_LOGIN;
            } elseif (stristr($logRecord['message'], 'ответил на вопрос')) {
                $activityRow['action'] = UserActivity::ACTION_ANSWER_QUESTION;
            } elseif (stristr($logRecord['message'], 'Автологин')) {
                $activityRow['action'] = UserActivity::ACTION_AUTOLOGIN;
            } elseif (stristr($logRecord['message'], 'прокомментировал ответ')) {
                $activityRow['action'] = UserActivity::ACTION_POST_COMMENT;
            }

            Yii::app()->db->createCommand()->insert('{{user_activity}}', $activityRow);
        }
    }

    public function actionClear()
    {
        Yii::app()->db->createCommand()->truncateTable('{{user_activity}}');
    }
}
