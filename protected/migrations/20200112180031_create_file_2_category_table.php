<?php

use Phinx\Migration\AbstractMigration;

/**
 * Создание таблицы для хранения данных от FileSystem
 * Class CreateFileTable.
 */
class CreateFile2CategoryTable extends AbstractMigration
{
    const TABLE = '100_file2category';

    public function up()
    {
        $this->table(self::TABLE)
            ->addColumn('file_id', 'integer', ['limit' => 11, 'null' => false])
            ->addColumn('category_id', 'integer', ['limit' => 11, 'null' => false])

            ->save();
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
