<?php

use Phinx\Migration\AbstractMigration;

class AddStatusToTransaction extends AbstractMigration
{

    public function up()
    {
        $this->table('100_transactionCampaign')
            ->addColumn('status', 'integer', ['limit' => 2, 'null' => true, 'default' => 1])
            ->save();

    }

    public function down()
    {
        $this->table('100_transactionCampaign')
            ->removeColumn('status')
            ->save();
    }
}
