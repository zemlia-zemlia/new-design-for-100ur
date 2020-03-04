<?php

use Phinx\Migration\AbstractMigration;

/**
 * Добавление в таблицу источников поля, определяющего, принимать ли данные о цене от вебмастера
 * Class LeadPriceByPartner.
 */
class LeadPriceByPartner extends AbstractMigration
{
    const TABLE = '100_leadsource';

    public function up()
    {
        $this->table(static::TABLE)
            ->addColumn('priceByPartner', 'boolean', ['default' => 0])
            ->save();
    }

    public function down()
    {
        $this->table(static::TABLE)
            ->removeColumn('priceByPartner')
            ->save();
    }
}
