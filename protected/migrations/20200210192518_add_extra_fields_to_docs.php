<?php

use Phinx\Migration\AbstractMigration;

class AddExtraFieldsToDocs extends AbstractMigration
{
    public function up()
    {
        $this->table('100_docs')
            ->addColumn('size', 'integer', ['limit' => 11, 'null' => true])
            ->addColumn('uploadTs', 'integer', ['limit' => 11, 'null' => true])
            ->save();
        $this->query('ALTER TABLE `100_docs` CHANGE `type` `type` varchar(50) NULL AFTER `filename`');
    }

    public function down()
    {
        $this->table('100_docs')
            ->removeColumn('size')
            ->removeColumn('uploadTs')
            ->save();
        $this->query('ALTER TABLE `100_docs` CHANGE `type` `type` int(11) NULL AFTER `filename`');
    }
}
