<?php

use Phinx\Migration\AbstractMigration;

class AddNullToTransaction extends AbstractMigration
{

    public function up()
    {
        $this->execute('ALTER TABLE `100_transactionCampaign`
CHANGE `campaignId` `campaignId` int(11) NULL AFTER `buyerId`');
    }

    public function down()
    {
        $this->execute('ALTER TABLE `100_transactionCampaign`
CHANGE `campaignId` `campaignId` int(11) NOT  NULL AFTER `buyerId`');

    }
}
