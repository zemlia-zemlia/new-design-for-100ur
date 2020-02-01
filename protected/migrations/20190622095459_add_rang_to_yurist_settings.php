<?php

use Phinx\Migration\AbstractMigration;

/**
 * Создание в настройках юриста нового поля - ранг
 * Class AddRangToYuristSettings
 */
class AddRangToYuristSettings extends AbstractMigration
{

    public function up()
    {
        $this->table('100_yuristSettings')
            ->addColumn('rang', 'integer', ['default' => 0, 'comment' => 'Звание юриста'])
            ->addIndex(['rang'])
            ->save();
    }

    public function down()
    {
        $this->table('100_yuristSettings')
            ->removeColumn('rang')
            ->save();
    }
}
