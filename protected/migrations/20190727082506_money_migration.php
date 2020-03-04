<?php

use Phinx\Migration\AbstractMigration;

/**
 * Миграция умножает значения всех денежных сумм на 100 и делает поля, их хранящие, целыми
 * Class MoneyMigration.
 */
class MoneyMigration extends AbstractMigration
{
    public function up()
    {
        $tables = $this->getTablesWithFields();
        foreach ($tables as $tableName => $fields) {
            $this->migrateTable($tableName, $fields);
        }
    }

    public function down()
    {
        $tables = $this->getTablesWithFields();
        foreach ($tables as $tableName => $fields) {
            $this->rollbackTable($tableName, $fields);
        }
    }

    /**
     * Мигрирует таблицу.
     *
     * @param string $tableName
     * @param array  $fields
     */
    private function migrateTable($tableName, $fields)
    {
        $table = $this->table($tableName);
        foreach ($fields as $field) {
            $table->changeColumn($field, 'decimal', ['precision' => 10, 'scale' => 2]);
            $this->execute('update `' . $tableName . "` SET `{$field}` = `{$field}` * 100");
            $table->changeColumn($field, 'integer');
        }
        $table->save();
    }

    /**
     * Откатывает таблицу.
     *
     * @param string $tableName
     * @param array  $fields
     */
    private function rollbackTable($tableName, $fields)
    {
        $table = $this->table($tableName);
        foreach ($fields as $field) {
            $table->changeColumn($field, 'decimal', ['precision' => 10, 'scale' => 2]);
            $this->execute('update `' . $tableName . "` SET `{$field}` = `{$field}` / 100");
        }
        $table->save();
    }

    /**
     * @return array Ключи - названия таблиц, значения - массивы полей
     */
    private function getTablesWithFields()
    {
        return [
            '100_campaign' => [
                'price',
                'balance',
            ],
            '100_expence' => [
                'expences',
            ],
            '100_lead' => [
                'price',
                'buyPrice',
            ],
            '100_money' => [
                'value',
            ],
            '100_order' => [
                'price',
            ],
            '100_orderresponse' => [
                'price',
            ],
            '100_partnerTransaction' => [
                'sum',
            ],
            '100_question' => [
                'price',
                'buyPrice',
            ],
            '100_town' => [
                'sellPrice',
                'buyPrice',
            ],
            '100_transaction' => [
                'value',
            ],
            '100_transactionCampaign' => [
                'sum',
            ],
            '100_user' => [
                'balance',
            ],
            '100_yuristSettings' => [
                'priceConsult',
                'priceDoc',
            ],
        ];
    }
}
