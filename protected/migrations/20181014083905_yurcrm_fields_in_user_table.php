<?php

use Phinx\Migration\AbstractMigration;

class YurcrmFieldsInUserTable extends AbstractMigration
{
    /**
     * Применение миграции.
     */
    public function up()
    {
        $this->table('100_user')
            ->addColumn('yurcrmSource', 'integer', ['default' => 0, 'comment' => 'id источника 100yuristov.com в базе yurcrm'])
            ->addColumn('yurcrmToken', 'string', ['default' => null, 'null' => true, 'comment' => 'токен для api yurcrm'])
            ->save();
    }

    /**
     * Откат миграции.
     */
    public function down()
    {
        $this->table('100_user')
            ->removeColumn('yurcrmSource')
            ->removeColumn('yurcrmToken')
            ->save();
    }
}
