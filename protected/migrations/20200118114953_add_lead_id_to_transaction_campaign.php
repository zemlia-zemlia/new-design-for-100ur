<?php

use Phinx\Migration\AbstractMigration;

/**
 * Добавляет поле leadId в таблицу 100_transactionCampaign
 * Class AddLeadIdToTransactionCampaign.
 */
class AddLeadIdToTransactionCampaign extends AbstractMigration
{
    const TRANSACTION_TABLE_NAME = '100_transactionCampaign';
    const LEADID_FIELD = 'leadId';

    public function up()
    {
        $this->table(self::TRANSACTION_TABLE_NAME)
            ->addColumn(self::LEADID_FIELD, 'integer', ['signed' => false, 'null' => true, 'default' => null, 'comment' => 'lead id'])
            ->addIndex(['leadId'])
            ->save();
    }

    public function down()
    {
        $this->table(self::TRANSACTION_TABLE_NAME)
            ->removeColumn(self::LEADID_FIELD)
            ->save();
    }
}
