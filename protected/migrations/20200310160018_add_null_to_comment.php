<?php

use Phinx\Migration\AbstractMigration;

class AddNullToComment extends AbstractMigration
{
    public function up()
    {
        $this->execute('ALTER TABLE `100_userStatusRequest`
CHANGE `comment` `comment`  text COLLATE `utf8_general_ci`  NULL AFTER `position`');
    }

    public function down()
    {
        $this->execute('ALTER TABLE `100_userStatusRequest`
CHANGE `comment` `comment` text COLLATE `utf8_general_ci` NOT  NULL AFTER `position`');
    }
}
