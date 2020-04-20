<?php

class m200420_101300_chat extends CDbMigration
{
    public function up()
    {
        $sql = file_get_contents('./chat_dump.sql');
        $this->execute($sql);
    }

    public function down()
    {
        return false;
    }

    /*
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
