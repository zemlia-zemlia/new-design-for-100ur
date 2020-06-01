<?php

use Phinx\Migration\AbstractMigration;

class AddColumnToChatTable extends AbstractMigration
{
    public function up()
    {
        $this->table('100_chat')
            ->addColumn('is_petition', 'integer', ['null' => true,  'limit' => 1])
            ->save();
    }

    public function down()
    {
        $this->table('100_chat')
            ->removeColumn('is_petition')
            ->save();
    }
}
