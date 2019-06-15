<?php

use Phinx\Migration\AbstractMigration;

/**
 * Добавление заголовка в таблицу комментариев
 * Class AddTitleToComments
 */
class AddTitleToComments extends AbstractMigration
{
    const TABLE = '100_comment';

    public function up()
    {
        $this->table(self::TABLE)
            ->addColumn('title', 'string', ['limit' => 255, 'null' => true])
            ->save();
    }

    public function down()
    {
        $this->table(self::TABLE)
            ->removeColumn('title')
            ->save();
    }
}
