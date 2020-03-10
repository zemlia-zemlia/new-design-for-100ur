<?php

use Phinx\Migration\AbstractMigration;

class AddNullToFileId extends AbstractMigration
{
    public function up()
    {
        $this->execute('ALTER TABLE `100_userStatusRequest`
CHANGE `fileId` `fileId` int(11) NULL AFTER `comment`');
    }

    public function down()
    {
        $this->execute('ALTER TABLE `100_userStatusRequest`
CHANGE `fileId` `fileId` int(11) NOT  NULL AFTER `comment`');
    }
}
