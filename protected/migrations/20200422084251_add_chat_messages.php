<?php

use Phinx\Migration\AbstractMigration;

class AddChatMessages extends AbstractMigration
{
    public function up()
    {
        $this->table('100_chat_messages')
            ->addColumn('chat_id', 'integer', ['limit' => 11])
            ->addColumn('user_id', 'integer', ['limit' => 11])
            ->addColumn('message', 'text')
            ->addColumn('created', 'integer', ['limit' => 11])
            ->addColumn('is_read', 'integer', ['limit' => 1, 'default' => 0])
            ->addIndex(['chat_id'])
            ->create();
    }

    public function down()
    {
        $this->table('100_chat_messages')->drop();
    }
}
