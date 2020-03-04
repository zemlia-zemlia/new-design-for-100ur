<?php

use Phinx\Migration\AbstractMigration;

/**
 * Таблица почтовых рассылок
 * Class CreateMailTable.
 */
class CreateMailTable extends AbstractMigration
{
    public function up()
    {
        $this->table('100_mail', ['comment' => 'Почтовые рассылки'])
            ->addColumn('createDate', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('subject', 'text')
            ->addColumn('message', 'text')
            ->save();
    }

    public function down()
    {
        $table = $this->table('100_mail');
        $table->drop();
    }
}
