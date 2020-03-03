<?php

use Phinx\Migration\AbstractMigration;

class AddDescToDocs extends AbstractMigration
{
    public function up()
    {
        $this->table('100_docs')
            ->addColumn('description', 'text', ['null' => 'true', 'comment' => 'Описание'])
            ->save();
    }

    public function down()
    {
        $this->table('100_docs')
            ->removeColumn('description')
            ->save();
    }
}
