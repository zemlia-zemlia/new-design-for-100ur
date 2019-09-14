<?php

use Phinx\Migration\AbstractMigration;

/**
 * Создает в таблице пользователей поле uloginId
 * Class AddUloginIdToUser
 */
class AddUloginIdToUser extends AbstractMigration
{
    const TABLE = '100_user';

    public function up()
    {
        $this->table(self::TABLE)
            ->addColumn('uloginId', 'integer', ['null'=>true, 'default' => null])
            ->save();
    }

    public function down()
    {
        $this->table(self::TABLE)
            ->removeColumn('uloginId');
    }
}
