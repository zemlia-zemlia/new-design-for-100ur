<?php

use Phinx\Migration\AbstractMigration;

/**
 * Создание таблицы для хранения данных от FileSystem
 * Class CreateFileTable
 */
class CreateFileCategoryTable extends AbstractMigration
{
    const TABLE = '100_file_category';

    public function up()
    {
        $this->table(self::TABLE)
            ->addColumn('name', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('lft', 'integer', ['limit' => 11, 'null' => false])
            ->addColumn('rgt', 'integer', ['limit' => 11, 'null' => false])
            ->addColumn('root', 'integer', ['limit' => 11, 'null' => false])
            ->addColumn('level', 'integer', ['limit' => 11, 'null' => false])

            ->save();
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}

