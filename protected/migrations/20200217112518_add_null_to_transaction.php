<?php

use Phinx\Migration\AbstractMigration;

class Add_NullToTransaction extends AbstractMigration
{

    public function up()
    {
        $this->execute('ALTER TABLE `100_transactioncampaign`
CHANGE `campaignId` `campaignId` int(11) NULL AFTER `buyerId`');
    }

    public function down()
    {

    }
}
