<?php

use Phinx\Migration\AbstractMigration;

class AddActiveToDocsCat extends AbstractMigration
{
    public function up()
    {
        $this->table('100_docs_category')
            ->addColumn('active', 'integer', ['limit' => 1, 'null' => true])
            ->save();
    }

    public function down()
    {
        $this->table('100_docs_category')
            ->removeColumn('active')
            ->save();
    }
}
