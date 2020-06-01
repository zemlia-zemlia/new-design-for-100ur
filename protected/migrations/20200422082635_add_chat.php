<?php

use Phinx\Migration\AbstractMigration;

class AddChat extends AbstractMigration
{
    public function up()
    {
        $this->table('100_chat')
            ->addColumn('user_id', 'integer', ['limit' => 11])
            ->addColumn('lawyer_id', 'integer', ['limit' => 11, 'null' => true, 'default' => null])
            ->addColumn('is_payed', 'integer', ['limit' => 1, 'null' => true])
            ->addColumn('transaction_id', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('created', 'integer', ['limit' => 11])
            ->addColumn('is_closed', 'integer', ['limit' => 1, 'null' => true])
            ->addColumn('chat_id', 'string', ['limit' => 255])
            ->addColumn('is_confirmed', 'integer', ['limit' => 1, 'null' => true, 'default' => null])
            ->addIndex(['user_id'])
            ->addIndex(['lawyer_id'])
            ->addIndex(['created'])
            ->create();
    }

    public function down()
    {
        $this->table('100_chat')->drop();
    }
}
