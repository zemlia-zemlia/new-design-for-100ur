<?php

/**
 *  Пересчет балансов юристов и вебмастеров, исходя из их транзакций
 * Обновляет баланс пользователей, у которых не сходится баланс и сумма транзакций
 */
class RecalculateBalancesCommand extends CConsoleCommand
{

    public function actionIndex()
    {
        $balances = Yii::app()->db->createCommand()
                ->select('u.id, u.balance, SUM(t.sum) transactionSum')
                ->from('{{transactionCampaign}} t')
                ->leftJoin('{{user}} u', 'u.id = t.buyerId')
                ->where(['in', 'u.role', [User::ROLE_JURIST, User::ROLE_BUYER]])
                ->having('(u.balance - transactionSum) > 0')
                ->group('u.id')
                ->queryAll();
        
        foreach ($balances as $balance) {
            Yii::app()->db->createCommand()
                    ->update('{{user}}', ['balance' => $balance['transactionSum']], 'id=:id', [':id' => $balance['id']]);
        }
    }

}
