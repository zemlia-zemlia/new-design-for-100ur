<?php

use Phinx\Migration\AbstractMigration;

/**
 * Удаление неиспользуемой таблицы 100_transaction
 * Class DropTransactionTable.
 */
class DropTransactionTable extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('100_transaction');
        if ($table->exists()) {
            $table->drop();
        }
    }

    public function down()
    {
        // do nothing
    }
}
