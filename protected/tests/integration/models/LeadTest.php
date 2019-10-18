<?php

namespace Tests\Integration\Models;

use CDbTransaction;
use Codeception\Test\Unit;
use Exception;
use Lead;
use User;
use Yii;
use Faker\Factory;

class LeadTest extends Unit
{
    /**
     * @var \IntegrationTester
     */
    protected $tester;

    /** @var CDbTransaction */
    protected $transaction;

    protected $faker;

    const LEAD_TABLE = '100_lead';
    const LEAD_SOURCE_TABLE = '100_leadsource';
    const USER_TABLE = '100_user';
    const PARTNER_TRANSACTIONS_TABLE = '100_partnerTransaction';
    const CAMPAIGNS_TABLE = '100_campaign';

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->faker = Factory::create('ru_RU');
    }

    protected function _before()
    {
        Yii::app()->db->createCommand()->truncateTable(self::LEAD_TABLE);
        Yii::app()->db->createCommand()->truncateTable(self::LEAD_SOURCE_TABLE);
        Yii::app()->db->createCommand()->truncateTable(self::USER_TABLE);
        Yii::app()->db->createCommand()->truncateTable(self::PARTNER_TRANSACTIONS_TABLE);
        Yii::app()->db->createCommand()->truncateTable(self::CAMPAIGNS_TABLE);
    }

    protected function _after()
    {
    }

    public function testTownAndRegionLoaded()
    {
        $this->tester->seeInDatabase('100_town', ['id' => 598]);
    }

    /**
     * @dataProvider providerSellLead
     * @param array $leadAttributes
     * @param integer $buyerId
     * @param integer $campaignId
     * @param bool $expectedResult
     * @throws Exception
     */
    public function testSellLead(
        array $leadAttributes,
        $buyerId,
        $campaignId,
        $expectedResult
    )
    {
        $this->loadFixtures();

        $lead = new Lead();
        $lead->attributes = $leadAttributes;
        $sellResult = $lead->sellLead($buyerId, $campaignId);

        $this->assertEquals($expectedResult, $sellResult);
    }

    /**
     * Аттрибуты лида
     * @return array
     */
    public function providerSellLead(): array
    {
        $moscowLead = [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'question' => $this->faker->paragraph,
            'sourceId' => 33,
            'townId' => 598,
            'buyPrice' => 0,
        ];
        $balashikhaLead = [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'question' => $this->faker->paragraph,
            'sourceId' => 33,
            'townId' => 66,
            'buyPrice' => 1000,
        ];

        return [
            'Incorrect buyer and campaign' => [
                'leadAttributes' => $moscowLead,
                'buyerId' => 0,
                'campaignId' => 0,
                'expectedResult' => false,
            ],
            'Campaign not exists' => [
                'leadAttributes' => $moscowLead,
                'buyerId' => 0,
                'campaignId' => 999999,
                'expectedResult' => false,
            ],
            'Moscow lead, poor campaign' => [
                'leadAttributes' => $moscowLead,
                'buyerId' => 0,
                'campaignId' => 4,
                'expectedResult' => false,
            ],
//            'Moscow lead, rich campaign' => [
//                'leadAttributes' => $moscowLead,
//                'buyerId' => 0,
//                'campaignId' => 3,
//                'expectedResult' => true,
//            ],
//            [
//                'leadAttributes' => $balashikhaLead,
//                'buyerId' => 0,
//                'campaignId' => 3,
//                'expectedResult' => true,
//            ],
        ];
    }

    private function loadFixtures(): void
    {
        $usersFixture = [
            [
                'id' => 10000,
                'name' => 'Вебмастер',
                'role' => User::ROLE_PARTNER,
                'active100' => 1
            ],
            [
                'id' => 10001,
                'name' => 'Покупатель лидов при деньгах',
                'role' => User::ROLE_BUYER,
                'active100' => 1,
                'balance' => 100000,
            ],
            [
                'id' => 10002,
                'name' => 'Покупатель лидов бедный',
                'role' => User::ROLE_BUYER,
                'active100' => 1,
                'balance' => 100,
            ],
        ];

        $leadSourceFixture = [
            [
                'id' => 33,
                'appId' => '188',
                'secretKey' => '3388',
                'name' => 'Партнерка',
                'active' => 1,
                'userId' => 10000,
                'priceByPartner' => 1
            ]
        ];

        $campaignsFixture = [
            // Мос обл
            [
                'id' => 1,
                'regionId' => 25,
                'townId' => 0,
                'price' => 1500,
                'leadsDayLimit' => 2,
                'buyerId' => 10001,
                'active' => 1,
                'type' => 0,
            ],
            // неактивная
            [
                'id' => 2,
                'regionId' => 25,
                'townId' => 0,
                'price' => 1500,
                'leadsDayLimit' => 2,
                'buyerId' => 10001,
                'active' => 0,
                'type' => 0,
            ],
            // Москва
            [
                'id' => 3,
                'regionId' => 0,
                'townId' => 598,
                'price' => 5000,
                'leadsDayLimit' => 1,
                'buyerId' => 10001,
                'active' => 1,
                'type' => 0,
            ],
            // Москва
            [
                'id' => 4,
                'regionId' => 0,
                'townId' => 598,
                'price' => 5000,
                'leadsDayLimit' => 1,
                'buyerId' => 10002,
                'active' => 1,
                'type' => 0,
            ],
        ];

        $this->loadToDatabase(self::USER_TABLE, $usersFixture);
        $this->loadToDatabase(self::LEAD_SOURCE_TABLE, $leadSourceFixture);
        $this->loadToDatabase(self::CAMPAIGNS_TABLE, $campaignsFixture);
    }

    /**
     * Записывает в базу массив из нескольких записей
     * @param string $tableName
     * @param array $data
     */
    protected function loadToDatabase($tableName, $data)
    {
        foreach ($data as $record) {
            if (is_array($record)) {
                $this->tester->haveInDatabase($tableName, $record);
            }
        }
    }


    public function testGetStatusCounter()
    {
        $leadsData = [
            [
                'leadStatus' => 1,
                'campaignId' => 1,
            ],
            [
                'leadStatus' => 1,
                'campaignId' => 1,
            ],
            [
                'leadStatus' => 1,
                'campaignId' => 0,
            ],
            [
                'leadStatus' => 2,
                'campaignId' => 0,
            ],
        ];

        $this->loadToDatabase(self::LEAD_TABLE, $leadsData);

        $this->assertEquals(2, Lead::getStatusCounter(1));
        $this->assertEquals(3, Lead::getStatusCounter(1, false));
    }

    public function testFindDublicates()
    {
        $leadsData = [
            [
                'phone' => '1234567',
                'townId' => 100,
                'question_date' => (new \DateTime())->modify('-1 hour')->format('Y-m-d H:i:s')
            ],
            [
                'phone' => '1234567',
                'townId' => 100,
                'question_date' => (new \DateTime())->modify('-12 hour')->format('Y-m-d H:i:s')
            ],
            [
                'phone' => '1234567',
                'townId' => 101,
                'question_date' => (new \DateTime())->modify('-1 hour')->format('Y-m-d H:i:s')
            ],
            [
                'phone' => '1234567',
                'townId' => 100,
                'question_date' => (new \DateTime())->modify('-1 month')->format('Y-m-d H:i:s')
            ],
        ];

        $this->loadToDatabase(self::LEAD_TABLE, $leadsData);

        $lead = new Lead();
        $lead->phone = '1234567';
        $lead->townId = 100;

        $this->assertEquals(2, $lead->findDublicates());
        $this->assertEquals(1, $lead->findDublicates(10*3600));
        $this->assertEquals(3, $lead->findDublicates(2*30*24*3600));
    }
}
