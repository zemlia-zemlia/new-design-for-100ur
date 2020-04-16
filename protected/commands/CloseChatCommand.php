<?php


use App\models\Chat;
use App\models\TransactionCampaign;

class CloseChatCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $data = Chat::model()->
        findAll('is_closed IS NULL and is_payed and created < :time', [':time' => strtotime('-3day')]);
        foreach ($data as $row) {
            /** @var Chat $row */
            /** @var \App\models\User $user */
            /** @var TransactionCampaign $trans */
            if ($trans = TransactionCampaign::model()->find('id=:id and status=:status', [':id' => $row->transaction_id, ':status' => TransactionCampaign::STATUS_HOLD])) {
                $saveTransaction = $trans->dbConnection->beginTransaction();
                try {
                    $trans->status = TransactionCampaign::STATUS_COMPLETE;
                    $user = \App\models\User::model()->findByPk($row->layer_id);
                    $user->balance += $trans->sum;
                    $row->is_closed = 1;
                    if ($trans->save() and $user->save() and $row->save()) {
                        $saveTransaction->commit();
                        $user->sendDonateChatNotification($this->chat, $trans->sum);
                    } else {
                        var_dump($row->getErrors());
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
