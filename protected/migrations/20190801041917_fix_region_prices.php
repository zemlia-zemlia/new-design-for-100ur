<?php

use Phinx\Migration\AbstractMigration;

/**
 * Миграция переводит цены лидов региона из рублей в копейки
 * Class FixRegionPrices.
 */
class FixRegionPrices extends AbstractMigration
{
    const TABLE = '100_region';

    public function up()
    {
        $this->query('UPDATE `' . self::TABLE . '` SET sellPrice = sellPrice*100, buyPrice = buyPrice*100');
    }

    public function down()
    {
        $this->query('UPDATE `' . self::TABLE . '` SET sellPrice = sellPrice/100, buyPrice = buyPrice/100');
    }
}
