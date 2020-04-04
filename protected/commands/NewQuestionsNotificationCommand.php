<?php

/*
 * отправляет юристам уведомления о новых вопросах из их города/региона
 */

use App\models\Question;

class NewQuestionsNotificationCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        Question::sendRecentQuestionsNotifications(24);
    }
}
