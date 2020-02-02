<?php

use Phinx\Migration\AbstractMigration;

/**
 * Создание таблицы для хранения данных от FileSystem
 * Class CreateFileTable
 */
class CreateFile2ObjectTable extends AbstractMigration
{
    const TABLE = '100_file2object';

    public function up()
    {
        $this->table(self::TABLE)
            ->addColumn('file_id', 'integer', ['limit' => 11, 'null' => false])
            ->addColumn('object_id', 'integer', ['limit' => 11, 'null' => false])
            ->addColumn('object_type', 'integer', ['limit' => 11, 'null' => false])

            ->save();
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}

