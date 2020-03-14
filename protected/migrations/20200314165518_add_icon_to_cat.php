<?php

use Phinx\Migration\AbstractMigration;

class AddIconToCat extends AbstractMigration
{
    public function up()
    {
        $this->table('100_questionCategory')
            ->addColumn('icon', 'string', ['limit' => 255, 'null' => true, 'default' => null])
            ->save();
    }

    public function down()
    {
        $this->table('100_questionCategory')
            ->removeColumn('icon')
            ->save();
    }
}
