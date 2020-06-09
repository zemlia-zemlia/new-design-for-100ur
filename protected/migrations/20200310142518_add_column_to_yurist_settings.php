<?php

use Phinx\Migration\AbstractMigration;

class AddColumnToYuristSettings extends AbstractMigration
{
    public function up()
    {
        $this->table('100_yuristSettings')
            ->addColumn('inn', 'string', ['null' => true, 'comment' => 'ИНН', 'limit' => 11])
            ->addColumn('companyName', 'string', ['null' => true, 'comment' => 'Название компании', 'limit' => 255])
            ->addColumn('address', 'string', ['null' => true, 'comment' => 'Адрес', 'limit' => 255])
            ->save();
    }

    public function down()
    {
        $this->table('100_yuristSettings')
            ->removeColumn('inn')
            ->removeColumn('companyName')
            ->removeColumn('address')
            ->save();
    }
}
