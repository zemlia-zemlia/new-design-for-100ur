<?php

use Phinx\Migration\AbstractMigration;

class DefaultValues extends AbstractMigration
{

    public function up()
    {
        $this->table('100_userFile')
            ->changeColumn('isVerified', 'integer', ['default' => 0, 'null' => false])
            ->changeColumn('comment', 'text', ['default' => null, 'null' => true])
            ->changeColumn('reason', 'string', ['default' => null, 'null' => true])
            ->save();

        $this->table('100_userStatusRequest')
            ->changeColumn('vuz', 'string', ['default' => null, 'null' => true])
            ->changeColumn('facultet', 'string', ['default' => null, 'null' => true])
            ->changeColumn('education', 'string', ['default' => null, 'null' => true])
            ->changeColumn('advOrganisation', 'string', ['default' => null, 'null' => true])
            ->changeColumn('advNumber', 'string', ['default' => null, 'null' => true])
            ->changeColumn('position', 'string', ['default' => null, 'null' => true])
            ->changeColumn('fileId', 'text', ['default' => null, 'null' => true])
            ->changeColumn('comment', 'text', ['default' => null, 'null' => true])
            ->changeColumn('vuzTownId', 'integer', ['default' => null, 'null' => true])
            ->changeColumn('educationYear', 'integer', ['default' => null, 'null' => true])
            ->save();
    }

    public function down()
    {

    }
}
