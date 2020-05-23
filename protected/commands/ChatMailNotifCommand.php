<?php


use App\models\Chat;
use App\models\User;
use App\notifiers\UserNotifier;

class ChatMailNotifCommand extends CConsoleCommand
{


    public function actionIndex()
    {
        // после скольки секунд неактивности отправлять пользователю уведомление о новых сообщениях
        $numberOfSecondInactivity = 600;

        $sql = 'SELECT chat.id cht_id, usr.id  usr_id, MAX(msg.created) last_message_time
        FROM 100_chat chat 
        LEFT JOIN 100_chat_messages msg ON msg.chat_id = chat.id
        LEFT JOIN 100_user usr ON chat.user_id = usr.id OR chat.lawyer_id = usr.id
        WHERE usr.lastActivity < NOW() - INTERVAL :inactivityPeriod SECOND AND usr.id != msg.user_id
        GROUP by usr.id, chat.id
        HAVING last_message_time > :time';

        $time = time() - $numberOfSecondInactivity;
        $data = Yii::app()->db->createCommand($sql)
            ->bindParam(':time', $time)
            ->bindParam(':inactivityPeriod', $numberOfSecondInactivity)
            ->queryAll();

        foreach ($data as $row) {
            $chat = Chat::model()->findByPk($row['cht_id']);
            $user = User::model()->findByPk($row['usr_id']);
            $notifier = new UserNotifier(Yii::app()->mailer, $user);
            $notifier->sendChatUserNotification($chat, $user);
        }
    }
}
