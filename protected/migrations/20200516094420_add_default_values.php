<?php

use Phinx\Migration\AbstractMigration;

class AddDefaultValues extends AbstractMigration
{

    public function up()
    {
        $this->table('100_yuristSettings')
            ->changeColumn('alias', 'string', ['default' => null, 'null' => true])
            ->changeColumn('description', 'text', ['default' => null, 'null' => true])
            ->changeColumn('hello', 'string', ['default' => null, 'null' => true])
            ->save();

    }

    public function down()
    {

    }
}
