<?php

use Phinx\Migration\AbstractMigration;

/**
 * Миграция добавляет в таблицу {{campaign}} поля для отправки лида в API.
 */
class AddApiFieldsToCampaign extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('100_campaign');
        $table->addColumn('sendToApi', 'integer', ['length' => 1, 'default' => 0, 'comment' => 'Отправлять лиды через API'])
                ->addColumn('apiClass', 'string', ['length' => 255, 'default' => '', 'comment' => 'Имя класса для работы с API'])
                ->addColumn('type', 'integer', ['length' => 4, 'default' => 0, 'comment' => 'Тип кампании'])
                ->update();
    }
}
