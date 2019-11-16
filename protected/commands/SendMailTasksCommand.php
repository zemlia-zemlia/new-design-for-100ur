<?php
/**
 * Отправка рассылок
 *
 * Class SendMailTasksCommand
 */

class SendMailTasksCommand extends CConsoleCommand
{
    public function actionIndex($limit = 100)
    {
        echo '=== sending mails ===' . PHP_EOL;

        $mailsSent = Mail::sendTasks($limit, false);

        echo 'mails sent: ' . $mailsSent . PHP_EOL;
    }
}