<?php

namespace models;

use CDbTransaction;
use User;
use Yii;

class LeadTest extends \Codeception\Test\Unit
{
    /**
     * @var \IntegrationTester
     */
    protected $tester;

    /** @var CDbTransaction */
    protected $transaction;

    const LEAD_TABLE = '100_lead';
    const LEAD_SOURCE_TABLE = '100_leadsource';
    const USER_TABLE = '100_user';
    const PARTNER_TRANSACTIONS_TABLE = '100_partnerTransaction';

    protected function _before()
    {
        Yii::app()->db->createCommand()->truncateTable(self::LEAD_TABLE);
        Yii::app()->db->createCommand()->truncateTable(self::LEAD_SOURCE_TABLE);
        Yii::app()->db->createCommand()->truncateTable(self::USER_TABLE);
        Yii::app()->db->createCommand()->truncateTable(self::PARTNER_TRANSACTIONS_TABLE);

        $this->transaction = Yii::app()->db->beginTransaction();
    }

    protected function _after()
    {
        $this->transaction->rollback();
    }

    /**
     * @todo дописать тест
     */
    public function testCreateLeadByAPI()
    {
        $usersFixture = [
            'name' => 'Вебмастер',
            'id' => 10000,
            'role' => User::ROLE_PARTNER,
            'active100' => 1
        ];

        $leadSourceFixture = [
            'appId' =>'188',
            'secretKey' =>'3388',
            'id' => 33,
            'name' => 'Партнерка',
            'active' => 1,
            'userId' => 10000,
            'priceByPartner' => 1
        ];

        $this->tester->haveInDatabase(self::USER_TABLE, $usersFixture);
        $this->tester->haveInDatabase(self::LEAD_SOURCE_TABLE, $leadSourceFixture);

        $apiClient = new \StoYuristovClient('188', '3388', 0, 'http://100yuristov');
        $apiClient->name = "Пушкин";
        $apiClient->phone = "+7987666-09-09";
        $apiClient->town = "Название города";
        $apiClient->question = "текст вопроса";
    }
}