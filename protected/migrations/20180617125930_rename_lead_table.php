<?php

use Phinx\Migration\AbstractMigration;

class RenameLeadTable extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('100_lead100');
        $table->rename('100_lead');
    }
    
    public function down()
    {
        $table = $this->table('100_lead');
        $table->rename('100_lead100');
    }
}
