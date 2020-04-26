<?php

use Phinx\Migration\AbstractMigration;

/**
 * Исправление отсутствия дефолтных значений полей в БД
 * Class FixDefaultValues
 */
class FixDefaultValues extends AbstractMigration
{

    public function up()
    {
        $this->table('100_comment')
            ->changeColumn('authorName', 'string', ['default' => null, 'null' => true])
            ->changeColumn('root', 'integer', ['default' => null, 'null' => true])
            ->changeColumn('lft', 'integer', ['default' => null, 'null' => true])
            ->changeColumn('rgt', 'integer', ['default' => null, 'null' => true])
            ->changeColumn('level', 'integer', ['default' => 0, 'null' => false])
            ->save();

        $this->table('100_question')
            ->changeColumn('number', 'integer', ['default' => 0, 'null' => false])
            ->changeColumn('price', 'integer', ['default' => 0, 'null' => false])
            ->changeColumn('publishedBy', 'integer', ['default' => null, 'null' => true])
            ->changeColumn('phone', 'string', ['default' => null, 'null' => true])
            ->changeColumn('email', 'string', ['default' => null, 'null' => true])
            ->changeColumn('sessionId', 'string', ['default' => null, 'null' => true])
            ->changeColumn('moderatedBy', 'integer', ['default' => null, 'null' => true])
            ->save();

        $this->table('100_answer')
            ->changeColumn('videoLink', 'string', ['default' => null, 'null' => true])
            ->save();

        $this->table('100_partnerTransaction')
            ->changeColumn('leadId', 'integer', ['default' => null, 'null' => true])
            ->changeColumn('userId', 'integer', ['default' => null, 'null' => true])
            ->changeColumn('questionId', 'integer', ['default' => null, 'null' => true])
            ->save();

        $this->table('100_yuristSettings')
            ->changeColumn('startYear', 'integer', ['default' => null, 'null' => true])
            ->changeColumn('vuz', 'string', ['default' => null, 'null' => true])
            ->changeColumn('facultet', 'string', ['default' => null, 'null' => true])
            ->changeColumn('education', 'string', ['default' => null, 'null' => true])
            ->changeColumn('vuzTownId', 'integer', ['default' => null, 'null' => true])
            ->changeColumn('educationYear', 'integer', ['default' => null, 'null' => true])
            ->changeColumn('advOrganisation', 'string', ['default' => null, 'null' => true])
            ->changeColumn('advNumber', 'string', ['default' => null, 'null' => true])
            ->changeColumn('position', 'string', ['default' => null, 'null' => true])
            ->changeColumn('site', 'string', ['default' => null, 'null' => true])
            ->save();

        $this->execute('UPDATE 100_user SET lastTransactionTime=null WHERE lastTransactionTime<"2010-01-01"');
        $this->execute('UPDATE 100_user SET birthday=null WHERE birthday<"1900-01-01"');

        $this->table('100_user')
            ->changeColumn('confirm_code', 'string', ['default' => null, 'null' => true])
            ->changeColumn('birthday', 'date', ['default' => null, 'null' => true])
            ->changeColumn('autologin', 'string', ['default' => null, 'null' => true])
            ->changeColumn('phone', 'string', ['default' => null, 'null' => true])
            ->changeColumn('townName', 'string', ['default' => null, 'null' => true])
            ->changeColumn('lastTransactionTime', 'datetime', ['default' => null, 'null' => true])
            ->changeColumn('refId', 'integer', ['default' => null, 'null' => true])
            ->changeColumn('rating', 'float', ['default' => 0, 'null' => false])
            ->changeColumn('name2', 'string', ['default' => null, 'null' => true])
            ->changeColumn('lastName', 'string', ['default' => null, 'null' => true])
            ->changeColumn('email', 'string', ['default' => null, 'null' => true])
            ->changeColumn('balance', 'integer', ['default' => 0, 'null' => false])
            ->save();

    }

    public function down()
    {

    }
}
