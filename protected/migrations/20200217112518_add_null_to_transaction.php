<?php

use Phinx\Migration\AbstractMigration;

class AddNullToTransaction extends AbstractMigration
{

    public function up()
    {
        $this->execute('ALTER TABLE `100_transactionСampaign`
CHANGE `campaignId` `campaignId` int(11) NULL AFTER `buyerId`');
    }

    public function down()
    {

    }
}
