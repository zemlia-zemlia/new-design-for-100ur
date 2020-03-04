<?php

use Phinx\Migration\AbstractMigration;

/**
 * Миграция добавляет таблицу для хранения данных об активности пользователей
 * которая заменит таблицу log
 * Class AddUserActivityTable.
 */
class AddUserActivityTable extends AbstractMigration
{
    const ACTIVITY_TABLE = '100_user_activity';

    public function up()
    {
        $table = $this->table(self::ACTIVITY_TABLE);
        $table->addColumn('userId', 'integer', ['null' => false])
            ->addColumn('ts', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('action', 'integer', ['null' => false])
            ->addColumn('ip', 'string', ['null' => true, 'default' => null])
            ->addIndex(['userId'])
            ->addIndex(['ts'])
            ->addIndex(['action'])
            ->save();
    }

    public function down()
    {
        $this->table(self::ACTIVITY_TABLE)->drop();
    }
}
