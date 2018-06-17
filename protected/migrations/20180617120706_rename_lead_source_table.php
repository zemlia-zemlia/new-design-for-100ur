<?php

use Phinx\Migration\AbstractMigration;

class RenameLeadSourceTable extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('100_leadsource100');
        $table->rename('100_leadsource');
    }
    
    public function down()
    {
        $table = $this->table('100_leadsource');
        $table->rename('100_leadsource100');
    }
}
