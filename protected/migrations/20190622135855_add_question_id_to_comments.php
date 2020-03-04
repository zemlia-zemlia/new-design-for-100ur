<?php

use Phinx\Migration\AbstractMigration;

/**
 * Добавление поля questionId в таблицу комментариев
 * Class AddQuestionIdToComments.
 */
class AddQuestionIdToComments extends AbstractMigration
{
    public function up()
    {
        $this->table('100_comment')
            ->addColumn('questionId', 'integer', ['default' => null, 'null' => true])
            ->addIndex(['questionId'])
            ->save();
    }

    public function down()
    {
        $this->table('100_comment')
            ->removeColumn('questionId')
            ->save();
    }
}
