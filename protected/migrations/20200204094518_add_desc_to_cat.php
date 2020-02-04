<?php

use Phinx\Migration\AbstractMigration;

class AddDescToDCat extends AbstractMigration
{

    public function up()
    {
        $this->table('100_file_category')
            ->addColumn('description', 'text', ['null' => 'true', 'comment' => 'Описание'])
            ->save();
    }

    public function down()
    {
        $this->table('100_file_category')
            ->removeColumn('description')
            ->save();
    }
}
