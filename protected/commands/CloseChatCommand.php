<?php


use App\models\Chat;
use App\models\TransactionCampaign;

class CloseChatCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $sql = 'SELECT * FROM `100_chat` chat INNER JOIN 100_chat_messages mess ON mess.chat_id = chat.id WHERE chat.is_closed IS NULL and chat.is_payed and mess.created < :time GROUP by chat.id';
        $time = strtotime('-3day');
        $data = Yii::app()->db->createCommand($sql)->bindParam(':time', $time)->queryAll();

        foreach ($data as $row) {
            /** @var Chat $row */
            /** @var \App\models\User $user */
            /** @var TransactionCampaign $trans */
            if ($trans = TransactionCampaign::model()->find('id=:id and status=:status', [':id' => $row['transaction_id'], ':status' => TransactionCampaign::STATUS_HOLD])) {
                $saveTransaction = $trans->dbConnection->beginTransaction();
                try {
                    $trans->status = TransactionCampaign::STATUS_COMPLETE;
                    $user = \App\models\User::model()->findByPk($row['layer_id']);
                    $user->balance += $trans->sum;
                    $chat = Chat::model()->findByPk($row['chat_id']);
                    $chat->is_closed = 1;
                    if ($trans->save() and $user->save() and $chat->save()) {
                        $saveTransaction->commit();
                        $user->sendDonateChatNotification($this->chat, $trans->sum);
                    } else {
                        var_dump($chat->getErrors());
                        var_dump($user->getErrors());
                        var_dump($trans->getErrors());
                    }
                } catch (\Exception $exception) {
                    $saveTransaction->rollback();
                    Yii::log('Ошибки: ' . print_r($trans->errors, true) . ' ' . print_r($user->errors, true) . ' ' . print_r($row->errors, true), 'error', 'system.web');

                }

            }

        }
    }
}
