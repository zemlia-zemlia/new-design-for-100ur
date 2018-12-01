<?php

use Phinx\Migration\AbstractMigration;

/**
 * Создание таблицы для хранения файлов, прикрепляемых к сущностям
 * Class FilesTable
 */
class FilesTable extends AbstractMigration
{

    public function up()
    {
        $this->table('100_file')
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('filename', 'string', ['limit' => 255])
            ->addColumn('objectId', 'integer', ['default' => 0])
            ->addColumn('objectType', 'integer', ['default' => 0])
            ->addColumn('type', 'integer', ['default' => 0])
            ->create();
    }

    public function down()
    {
        $this->table('100_file')->drop();
    }
}
