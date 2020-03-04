<?php

use Phinx\Migration\AbstractMigration;

class RenameFilesToDocs extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('100_file2category');
        $table
            ->rename('100_docs2category')
            ->update();

        $table = $this->table('100_file2object');
        $table
            ->rename('100_docs2object')
            ->update();

        $table = $this->table('100_file_category');
        $table
            ->rename('100_docs_category')
            ->update();
    }

    public function down()
    {
        $table = $this->table('100_docs2category');
        $table
            ->rename('100_file2category')
            ->update();

        $table = $this->table('100_docs2object');
        $table
            ->rename('100_file2object')
            ->update();

        $table = $this->table('100_docs_category');
        $table
            ->rename('100_file_category')
            ->update();
    }
}
