<?php

use Phinx\Migration\AbstractMigration;

class TownIdDefault extends AbstractMigration
{

    public function up()
    {
        $this->table('100_question')
            ->changeColumn('townIdByIP', 'integer', ['default' => null, 'null' => true])
            ->save();
    }

    public function down()
    {

    }
}
