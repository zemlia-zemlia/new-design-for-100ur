<?php

namespace Tests\Integration\Models;

use Campaign;
use Exception;
use IntegrationTester;
use Lead;
use Tests\Factories\CampaignFactory;
use Tests\Factories\LeadFactory;
use Tests\Factories\LeadSourceFactory;
use Tests\Factories\UserFactory;
use Tests\integration\BaseIntegrationTest;
use User;
use Yii;

class LeadTest extends BaseIntegrationTest
{
    const LEAD_TABLE = '100_lead';
    const LEAD_SOURCE_TABLE = '100_leadsource';
    const USER_TABLE = '100_user';
    const PARTNER_TRANSACTIONS_TABLE = '100_partnerTransaction';
    const CAMPAIGNS_TABLE = '100_campaign';

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
     * Попытка продать лид в нулевую кампанию и нулевому покупателю
     * @throws Exception
     */
    public function testSellLeadToIncorrectCampaignAndBuyer()
    {
        $this->loadFixtures();

        $lead = new Lead();
        $lead->attributes = (new LeadFactory())->generateOne();
        $sellResult = $lead->sellLead(0, 0);

        $this->assertEquals(false, $sellResult);
    }

    /**
     * Попытка продать лид в несуществующую кампанию
     * @throws Exception
     */
    public function testSellLeadToNonExistentCampaign()
    {
        $this->loadFixtures();

        $lead = new Lead();
        $lead->attributes = (new LeadFactory())->generateOne();
        $sellResult = $lead->sellLead(0, 999999);

        $this->assertEquals(false, $sellResult);
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
        $this->assertTrue($lead->save());
        $sellResult = $lead->sellLead($buyerId, $campaignId);

        $this->assertEquals($expectedResult, $sellResult);

        if ($expectedResult == true) {
            $this->tester->seeInDatabase(self::PARTNER_TRANSACTIONS_TABLE, [
                'leadId' => $lead->id,
            ]);
        }
    }

    /**
     * Аттрибуты лида
     * @return array
     */
    public function providerSellLead(): array
    {
        $moscowLead = (new LeadFactory())->generateOne([
            'sourceId' => 33,
            'townId' => 598,
            'buyPrice' => 1000,
        ]);
        $balashikhaLead = (new LeadFactory())->generateOne([
            'sourceId' => 33,
            'townId' => 66,
            'buyPrice' => 1000,
        ]);

        return [
            'Moscow lead, poor campaign' => [
                'leadAttributes' => $moscowLead,
                'buyerId' => 0,
                'campaignId' => 4,
                'expectedResult' => false,
            ],
            'Moscow lead, rich campaign' => [
                'leadAttributes' => $moscowLead,
                'buyerId' => 0,
                'campaignId' => 3,
                'expectedResult' => true,
            ],
//            [
//                'leadAttributes' => $balashikhaLead,
//                'buyerId' => 0,
//                'campaignId' => 3,
//                'expectedResult' => true,
//            ],
        ];
    }

    /**
     * @dataProvider providerCalculatePrices
     * @param $leadAttributes
     * @param $expectedBuyPrice
     * @param $expectedSellPrice
     */
    public function testCalculatePrices($leadAttributes, $expectedBuyPrice, $expectedSellPrice)
    {
        $lead = new Lead();
        $lead->attributes = $leadAttributes;

        list($buyPrice, $sellPrice) = $lead->calculatePrices();
        $this->assertEquals($expectedBuyPrice, $buyPrice);
        $this->assertEquals($expectedSellPrice, $sellPrice);
    }

    public function providerCalculatePrices(): array
    {
        return [
            'Moscow' => [
                'leadAttributes' => (new LeadFactory())->generateOne([
                    'townId' => 598,
                ]),
                'buyPrice' => 20000,
                'sellPrice' => 20000 * Yii::app()->params['priceCoeff'],
            ],
            'Balashikha' => [
                'leadAttributes' => (new LeadFactory())->generateOne([
                    'townId' => 172,
                ]),
                'buyPrice' => 7000,
                'sellPrice' => 7000 * Yii::app()->params['priceCoeff'],
            ],
        ];
    }

    private function loadFixtures(): void
    {
        $usersFixture = [
            (new UserFactory())->generateOne([
                'name' => 'Вебмастер',
                'role' => User::ROLE_PARTNER,
                'id' => 10000,
            ]),
            (new UserFactory())->generateOne([
                'name' => 'Покупатель лидов при деньгах',
                'role' => User::ROLE_BUYER,
                'balance' => 100000,
                'id' => 10001,
            ]),
            (new UserFactory())->generateOne([
                'name' => 'Покупатель лидов бедный',
                'role' => User::ROLE_BUYER,
                'balance' => 100,
                'id' => 10002,
            ]),
        ];

        $leadSourceFixture = [
            (new LeadSourceFactory())->generateOne([
                'id' => 33,
                'appId' => '188',
                'secretKey' => '3388',
                'name' => 'Партнерка',
                'active' => 1,
                'userId' => 10000,
                'priceByPartner' => 1
            ])
        ];

        $campaignsFixture = [
            // Мос обл
            (new CampaignFactory())->generateOne([
                'id' => 1,
                'regionId' => 25,
                'townId' => 0,
                'price' => 1500,
                'leadsDayLimit' => 2,
                'buyerId' => 10001,
                'active' => 1,
                'type' => 0,
            ]),
            // неактивная
            (new CampaignFactory())->generateOne([
                'id' => 2,
                'regionId' => 25,
                'townId' => 0,
                'price' => 1500,
                'leadsDayLimit' => 2,
                'buyerId' => 10001,
                'active' => 0,
                'type' => Campaign::TYPE_BUYERS,
            ]),
            // Москва
            (new CampaignFactory())->generateOne([
                'id' => 3,
                'regionId' => 0,
                'townId' => 598,
                'price' => 5000,
                'leadsDayLimit' => 1,
                'buyerId' => 10001,
                'active' => 1,
                'type' => Campaign::TYPE_BUYERS,
            ]),
            // Москва
            (new CampaignFactory())->generateOne([
                'id' => 4,
                'regionId' => 0,
                'townId' => 598,
                'price' => 5000,
                'leadsDayLimit' => 1,
                'buyerId' => 10002,
                'active' => 1,
                'type' => Campaign::TYPE_BUYERS,
            ]),
        ];

        $this->loadToDatabase(self::USER_TABLE, $usersFixture);
        $this->loadToDatabase(self::LEAD_SOURCE_TABLE, $leadSourceFixture);
        $this->loadToDatabase(self::CAMPAIGNS_TABLE, $campaignsFixture);
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
        $this->assertEquals(1, $lead->findDublicates(10 * 3600));
        $this->assertEquals(3, $lead->findDublicates(2 * 30 * 24 * 3600));
    }
}
