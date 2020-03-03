<?php

use Phinx\Migration\AbstractMigration;

/**
 * Создание таблицы для хранения данных от Ulogin
 * Class CreateUloginTable.
 */
class CreateUloginTable extends AbstractMigration
{
    const TABLE = 'ulogin_user';

    public function up()
    {
        $this->table(self::TABLE)
            ->addColumn('identity', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('network', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('email', 'string', ['limit' => 255, 'null' => true, 'default' => null])
            ->addColumn('full_name', 'string', ['limit' => 255, 'null' => true, 'default' => null])
            ->addColumn('state', 'boolean')
            ->addColumn('user_id', 'integer', ['signed' => false, 'null' => false, 'comment' => 'id пользователя в системе 100 Юристов'])
            ->addIndex(['user_id'])
            ->addIndex(['identity'], ['unique' => true])
            ->save();
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
