<?php

use Phinx\Migration\AbstractMigration;

class AddTransactionIdToAnswer extends AbstractMigration
{
    public function up()
    {
        $this->table('100_answer')
            ->addColumn('transactionId', 'integer', ['null' => 'true', 'comment' => 'ID транзакции вознаграждения за ответ'])
            ->addIndex(['transactionId'])
            ->save();
    }

    public function down()
    {
        $this->table('100_answer')
            ->removeColumn('transactionId')
            ->save();
    }
}
