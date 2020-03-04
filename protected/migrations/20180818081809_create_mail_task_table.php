<?php

use Phinx\Migration\AbstractMigration;

class CreateMailTaskTable extends AbstractMigration
{
    public function up()
    {
        $this->table('100_mailtask', ['comment' => 'Задания по отправке рассылок'])
            ->addColumn('startDate', 'date', ['null' => true, 'default' => null])
            ->addColumn('status', 'integer', ['length' => 1, 'default' => 0])
            ->addColumn('mailId', 'integer', ['default' => 0])
            ->addColumn('email', 'string', ['length' => 255])
            ->addColumn('userId', 'integer', ['default' => 0])
            ->addIndex('mailId')
            ->save();
    }

    public function down()
    {
        $table = $this->table('100_mailtask');
        $table->drop();
    }
}
