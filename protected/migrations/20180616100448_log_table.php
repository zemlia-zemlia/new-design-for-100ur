<?php

use Phinx\Migration\AbstractMigration;

class LogTable extends AbstractMigration
{
    const TABLE_NAME = '100_log';

    public function up()
    {
        $table = $this->table(self::TABLE_NAME, ['collation' => 'utf8_general_ci']);
        $table->addColumn('message', 'string', ['limit' => 255])
            ->addColumn('class', 'string', ['limit' => 255])
            ->addColumn('subjectId', 'integer')
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['class'])
            ->addIndex(['subjectId'])
            ->addIndex(['created'])
            ->save();
    }

    public function down()
    {
        $table = $this->table(self::TABLE_NAME);
        $table->drop();
    }
}
