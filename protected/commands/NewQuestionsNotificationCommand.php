<?php

/*
 * отправляет юристам уведомления о новых вопросах из их города/региона
 */
class NewQuestionsNotificationCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        Question::sendRecentQuestionsNotifications(24);
    }
}

