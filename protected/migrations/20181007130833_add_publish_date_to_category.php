<?php

use Phinx\Migration\AbstractMigration;

/**
 * Добавляет категории дату публикации
 * Class AddPublishDateToCategory
 */
class AddPublishDateToCategory extends AbstractMigration
{

    public function up()
    {
        $this->table('100_questionCategory')
            ->addColumn('publish_date', 'datetime', ['null' => false, 'default' => '2018-01-01 12:00:00'])
            ->addIndex(['publish_date'])
            ->save();
    }

    public function down()
    {
        $this->table('100_questionCategory')
            ->removeColumn('publish_date')
            ->save();
    }

}
