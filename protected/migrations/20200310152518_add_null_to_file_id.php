<?php

use Phinx\Migration\AbstractMigration;

class AddNullToFileId extends AbstractMigration
{
    public function up()
    {
        $this->table('100_userStatusRequest')
              ->changeColumn('fileId', 'integer', ['null' => true, 'limit' => 11]);

    }

    public function down()
    {
        $this->table('100_userStatusRequest')
            ->changeColumn('fileId', 'integer', ['null' => false, 'limit' => 11]);
    }
}
