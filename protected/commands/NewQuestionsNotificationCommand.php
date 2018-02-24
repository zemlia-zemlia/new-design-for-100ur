<?php

/*
 * команда обходит все вопросы и генерирует для них заголовки, после чего сохраняет вопрос
 */
class NewQuestionsNotificationCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        Question::sendRecentQuestionsNotifications();
    }
}

