<?php


use App\models\Chat;
use App\models\TransactionCampaign;

class CloseChatCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $sql = 'SELECT chat.*, MAX(mes.created) last_message_time
        FROM `100_chat` chat 
        LEFT JOIN 100_chat_messages mes ON mes.chat_id = chat.id
        WHERE chat.is_closed IS NULL and chat.is_payed 
        GROUP by chat.id
        HAVING last_message_time < :time';
        $time = strtotime('-3day');
        $data = Yii::app()->db->createCommand($sql)->bindParam(':time', $time)->queryAll();

        foreach ($data as $row) {
            /** @var Chat $row */
            /** @var \App\models\User $lawer */
            /** @var TransactionCampaign $trans */
            if ($trans = TransactionCampaign::model()->find('id=:id and status=:status', [':id' => $row['transaction_id'], ':status' => TransactionCampaign::STATUS_HOLD])) {
                $saveTransaction = $trans->dbConnection->beginTransaction();
                try {
                    $trans->status = TransactionCampaign::STATUS_COMPLETE;
                    $lawer = \App\models\User::model()->findByPk($row['lawyer_id']);
                    $lawer->balance += $trans->sum;
                    $chat = Chat::model()->findByPk($row['chat_id']);
                    $chat->is_closed = 1;
                    if ($trans->save() and $lawer->save() and $chat->save()) {
                        $saveTransaction->commit();
                        $lawer->sendDonateChatNotification($chat, $trans->sum);
                    } else {
                        $saveTransaction->rollback();
                    }
                } catch (\Exception $exception) {
                    $saveTransaction->rollback();
                    Yii::log('Ошибки: ' . print_r($trans->errors, true) . ' ' . print_r($lawer->errors, true) . ' ' . print_r($row->errors, true), 'error', 'system.web');

                }

            }

        }
    }
}
