<?php

use Phinx\Migration\AbstractMigration;

class ChatTokenColumn extends AbstractMigration
{
    public function up()
    {
        $this->table('100_user')
            ->addColumn('chatToken', 'string', ['null' => true, 'default' => null])
            ->addIndex(['chatToken'], ['unique' => true])
            ->save();
        // устанавливаем всем существующим пользователям рандомные токены
        $this->execute('UPDATE 100_user SET chatToken = MD5(`id` + RAND()*1000000) WHERE id > 0');
    }

    public function down()
    {
        $this->table('100_user')
            ->removeColumn('chatToken')
            ->save();
    }
}
