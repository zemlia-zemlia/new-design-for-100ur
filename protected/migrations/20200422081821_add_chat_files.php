<?php

use Phinx\Migration\AbstractMigration;

class AddChatFiles extends AbstractMigration
{

    public function up()
    {
        $this->table('100_chat_files')
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('mime', 'string', ['limit' => 100])
            ->addColumn('filename', 'string', ['limit' => 255])
            ->addColumn('user_id', 'integer', ['limit' => 11])
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }

    public function down()
    {
        $this->table('100_chat_files')->drop();
    }
}
