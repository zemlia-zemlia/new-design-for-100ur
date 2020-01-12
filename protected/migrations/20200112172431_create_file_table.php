<?php

use Phinx\Migration\AbstractMigration;

/**
 * Создание таблицы для хранения данных от FileSystem
 * Class CreateFileTable
 */
class CreateFileTable extends AbstractMigration
{
    const TABLE = '100_file';

    public function up()
    {
        $this->table(self::TABLE)
            ->addColumn('name', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('filename', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('type', 'integer', ['limit' => 4, 'null' => false])
            ->addColumn('downloads_count', 'integer', ['limit' => 11, 'null' => false, 'default' => 0])
            ->save();
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}


