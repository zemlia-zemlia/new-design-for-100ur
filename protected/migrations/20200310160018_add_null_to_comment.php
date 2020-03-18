<?php

use Phinx\Migration\AbstractMigration;

class AddNullToComment extends AbstractMigration
{
    public function up()
    {
        $this->table('100_userStatusRequest')
            ->changeColumn('fileId', 'text', ['null' => true])
            ->save();
    }

    public function down()
    {
        $this->table('100_userStatusRequest')
            ->changeColumn('fileId', 'text', ['null' => false])
            ->save();
    }
}