<?php

use Phinx\Migration\AbstractMigration;

class AddTypeToCampaignTransaction extends AbstractMigration
{
    public function up()
    {
        $this->table('100_transactionCampaign')
            ->addColumn('type', 'integer', ['null' => 'true', 'comment' => 'тип транзакции'])
            ->save();
    }

    public function down()
    {
        $this->table('100_transactionCampaign')
            ->removeColumn('type')
            ->save();
    }
}
