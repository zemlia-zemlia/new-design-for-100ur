<?php

/**
 * Задает записям из 100_transactionCampaign значение leadId согласно значению из поля description
 * Class SetLeadIdToCampaignTransactionCommand
 */
class SetLeadIdToCampaignTransactionCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $transactions = Yii::app()->db->createCommand()
            ->select('*')
            ->from("{{transactionCampaign}}")
            ->where('sum<0 AND leadId IS NULL')
            ->order('id DESC')
            ->queryAll();

        foreach ($transactions as $transaction) {
            $leadId = null;
            echo $transaction['description'] . PHP_EOL;
            preg_match('/[#|=]([0-9]+)$/', $transaction['description'], $leadIdMatches);

            if (isset($leadIdMatches[1])) {
                echo $leadIdMatches[1] . PHP_EOL;
                $leadId = (int)$leadIdMatches[1];

                echo (
                    Yii::app()->db->createCommand()
                    ->update("{{transactionCampaign}}", ['leadId' => $leadId], 'id=:id', [':id' => $transaction['id']])
                ) ? 'OK' : 'fail';

                echo PHP_EOL;
            }
        }
    }
}
