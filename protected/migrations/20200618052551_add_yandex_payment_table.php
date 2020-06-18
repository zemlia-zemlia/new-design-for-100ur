<?php

use Phinx\Migration\AbstractMigration;

class AddYandexPaymentTable extends AbstractMigration
{
    const TABLE_NAME = '100_yandex_payment';

    /**
     * Создание таблицы для хранения результатов обработки запросов от Яндекса
     */
    public function up()
    {
        $this->table(self::TABLE_NAME)
            ->addColumn('operation_id', 'string', ['null' => false, 'comment' => 'id операции из запроса Яндекса'])
            ->addColumn('label', 'string', ['null' => false, 'comment' => 'Метка, содержащая код оплачиваемой сущности'])
            ->addColumn('datetime', 'datetime', ['null' => false, 'comment' => 'Дата и время'])
            ->addColumn('status', 'integer', ['default' => 0,])
            ->addIndex(['operation_id'])
            ->save();
    }

    public function down()
    {
        $this->table(self::TABLE_NAME)->drop();
    }
}
