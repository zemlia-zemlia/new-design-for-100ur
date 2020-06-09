<?php

use Phinx\Migration\AbstractMigration;

/**
 * Исправление отсутствия дефолтных значений полей в БД
 * Class FixDefaultValues.
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

        $this->table('100_lead')
            ->changeColumn('questionId', 'integer', ['default' => null, 'null' => true])
            ->changeColumn('leadStatus', 'integer', ['default' => 0, 'null' => false])
            ->changeColumn('addedById', 'integer', ['default' => null, 'null' => true])
            ->changeColumn('campaignId', 'integer', ['default' => null, 'null' => true])
            ->changeColumn('buyerId', 'integer', ['default' => null, 'null' => true])
            ->changeColumn('buyPrice', 'integer', ['default' => 0, 'null' => false])
            ->changeColumn('price', 'integer', ['default' => 0, 'null' => false])
            ->changeColumn('brakComment', 'string', ['default' => null, 'null' => true])
            ->changeColumn('secretCode', 'string', ['default' => null, 'null' => true])
            ->changeColumn('email', 'string', ['default' => null, 'null' => true])
            ->removeColumn('contactId')
            ->save();

        $this->table('100_question')
            ->changeColumn('number', 'integer', ['default' => 0, 'null' => false])
            ->changeColumn('price', 'integer', ['default' => 0, 'null' => false])
            ->changeColumn('buyPrice', 'integer', ['default' => 0, 'null' => false])
            ->changeColumn('publishedBy', 'integer', ['default' => null, 'null' => true])
            ->changeColumn('sourceId', 'integer', ['default' => 0, 'null' => false])
            ->changeColumn('phone', 'string', ['default' => null, 'null' => true])
            ->changeColumn('ip', 'string', ['default' => null, 'null' => true])
            ->changeColumn('email', 'string', ['default' => null, 'null' => true])
            ->changeColumn('sessionId', 'string', ['default' => null, 'null' => true])
            ->changeColumn('moderatedBy', 'integer', ['default' => null, 'null' => true])
            ->save();

        $this->table('100_questionCategory')
            ->changeColumn('description1', 'text', ['default' => null, 'null' => true])
            ->changeColumn('description2', 'text', ['default' => null, 'null' => true])
            ->changeColumn('seoTitle', 'string', ['default' => null, 'null' => true])
            ->changeColumn('seoDescription', 'text', ['default' => null, 'null' => true])
            ->changeColumn('seoKeywords', 'text', ['default' => null, 'null' => true])
            ->changeColumn('seoH1', 'string', ['default' => null, 'null' => true])
            ->changeColumn('root', 'integer', ['default' => 0, 'null' => false])
            ->changeColumn('lft', 'integer', ['default' => 0, 'null' => false])
            ->changeColumn('rgt', 'integer', ['default' => 0, 'null' => false])
            ->changeColumn('level', 'integer', ['default' => 1, 'null' => false])
            ->changeColumn('path', 'string', ['default' => null, 'null' => true])
            ->changeColumn('image', 'string', ['default' => null, 'null' => true])
            ->changeColumn('publish_date', 'datetime', ['default' => null, 'null' => true])
            ->save();

        $this->table('100_answer')
            ->changeColumn('videoLink', 'string', ['default' => null, 'null' => true])
            ->changeColumn('karma', 'integer', ['default' => 0, 'null' => false])
            ->save();

        $this->table('100_leadsource')
            ->changeColumn('description', 'string', ['default' => null, 'null' => true])
            ->removeColumn('officeId')
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
            ->changeColumn('priceConsult', 'integer', ['default' => 0, 'null' => false])
            ->changeColumn('priceDoc', 'integer', ['default' => 0, 'null' => false])
            ->changeColumn('advOrganisation', 'string', ['default' => null, 'null' => true])
            ->changeColumn('advNumber', 'string', ['default' => null, 'null' => true])
            ->changeColumn('position', 'string', ['default' => null, 'null' => true])
            ->changeColumn('site', 'string', ['default' => null, 'null' => true])
            ->changeColumn('emailVisible', 'string', ['default' => null, 'null' => true])
            ->changeColumn('phoneVisible', 'string', ['default' => null, 'null' => true])
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
        $this->table('100_leadsource')
            ->addColumn('officeId', 'integer', ['default' => null, 'null' => true])
            ->save();

        $this->table('100_lead')
            ->addColumn('contactId', 'integer', ['default' => null, 'null' => true])
            ->save();
    }
}
