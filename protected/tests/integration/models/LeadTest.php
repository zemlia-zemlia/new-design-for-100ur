<?php

namespace Tests\Integration\Models;

use App\components\apiClasses\ApiLexprofit;
use App\components\ApiClassFactory;
use App\models\Campaign;
use App\models\Lead;
use App\models\User;
use CActiveDataProvider;
use Exception;
use Tests\Factories\CampaignFactory;
use Tests\Factories\LeadFactory;
use Tests\Factories\LeadSourceFactory;
use Tests\Factories\UserFactory;
use Tests\integration\BaseIntegrationTest;
use Yii;
use YurcrmClient\YurcrmClient;
use YurcrmClient\YurcrmResponse;

/**
 * Class LeadTest.
 *
 * @todo Покрыть тестами следующий функционал:
 * - Перевод лида в статус Брак из другого статуса с удалением транзакции вебмастера
 */
class LeadTest extends BaseIntegrationTest
{
    const LEAD_TABLE = '100_lead';
    const LEAD_SOURCE_TABLE = '100_leadsource';
    const USER_TABLE = '100_user';
    const PARTNER_TRANSACTIONS_TABLE = '100_partnerTransaction';
    const CAMPAIGN_TRANSACTIONS_TABLE = '100_transactionCampaign';
    const CAMPAIGNS_TABLE = '100_campaign';

    protected function _before()
    {
        Yii::app()->db->createCommand()->truncateTable(self::LEAD_TABLE);
        Yii::app()->db->createCommand()->truncateTable(self::LEAD_SOURCE_TABLE);
        Yii::app()->db->createCommand()->truncateTable(self::USER_TABLE);
        Yii::app()->db->createCommand()->truncateTable(self::PARTNER_TRANSACTIONS_TABLE);
        Yii::app()->db->createCommand()->truncateTable(self::CAMPAIGN_TRANSACTIONS_TABLE);
        Yii::app()->db->createCommand()->truncateTable(self::CAMPAIGNS_TABLE);
    }

    protected function _after()
    {
    }

    /**
     * Проверка, что в изначально загруженном дампе есть города и регионы.
     */
    public function testTownAndRegionLoaded()
    {
        $this->tester->seeInDatabase('100_town', ['id' => 598]);
    }

    /**
     * Попытка продать лид в нулевую кампанию и нулевому покупателю.
     *
     * @throws Exception
     */
    public function testSellLeadToIncorrectCampaignAndBuyer()
    {
        $this->loadFixtures();

        $lead = new Lead();
        $lead->attributes = (new LeadFactory())->generateOne();
        $sellResult = $lead->sellLead(null, null);

        $this->assertEquals(false, $sellResult);
    }

    /**
     * Продажа лида в партнерскую программу.
     *
     * @dataProvider providerPartner
     *
     * @throws Exception
     */
    public function testSellLeadToPartnerProgram($partnerResult, $expectedResult)
    {
        $this->loadFixtures();

        $vologdaLead = (new LeadFactory())->generateOne([
            'sourceId' => 33,
            'townId' => 170,
            'buyPrice' => 1000,
        ]);
        $buyer = null;
        $campaignId = 5;
        $campaign = Campaign::model()->findByPk($campaignId);

        $apiClassMock = $this->createMock(ApiLexProfit::class);
        $apiClassMock->method('send')->willReturn($partnerResult);
        $apiClassFactoryMock = $this->createMock(ApiClassFactory::class);
        $apiClassFactoryMock->method('getApiClass')->willReturn($apiClassMock);

        $lead = new Lead();
        $lead->setApiClassFactory($apiClassFactoryMock);
        $lead->attributes = $vologdaLead;
        $this->assertTrue($lead->save());
        $sellResult = $lead->sellLead($buyer, $campaign);

        $this->assertEquals($expectedResult, $sellResult);

        if (true == $expectedResult) {
            $this->tester->seeInDatabase(self::LEAD_TABLE, [
                'id' => $lead->id,
                'price' => 0,
                'campaignId' => $campaignId,
            ]);
        }
    }

    public function providerPartner(): array
    {
        return [
            'partner program returns success' => [
                'partnerResult' => true,
                'expectedResult' => true,
            ],
            'partner program returns fail' => [
                'partnerResult' => false,
                'expectedResult' => false,
            ],
        ];
    }

    public function testSellLeadToBuyerWithYurcrmAccount()
    {
        $this->loadFixtures();

        $lebedyanskyLeadAttributes = (new LeadFactory())->generateOne([
            'sourceId' => 33,
            'townId' => 512,
            'buyPrice' => 1000,
        ]);

        $buyerId = 10004;
        $campaignId = 6;
        $buyer = User::model()->findByPk($buyerId);
        $campaign = Campaign::model()->findByPk($campaignId);

        $yurcrmClientMock = $this->createMock(YurcrmClient::class);
        $yurcrmResultMock = $this->createMock(YurcrmResponse::class);
        $yurcrmResultMock->method('getResponse')->willReturn('test response data');
        $yurcrmResultMock->method('getHttpCode')->willReturn(200);

//        $yurcrmClientMock->method('send')->willReturn($yurcrmResultMock);
        $yurcrmClientMock->method('setRoute')->willReturn($yurcrmClientMock);
        $yurcrmClientMock->expects($this->once())->method('send');
        $buyer->setYurcrmClient($yurcrmClientMock);

        $lebedyanskyLead = new Lead();
        $lebedyanskyLead->attributes = $lebedyanskyLeadAttributes;
        $this->assertTrue($lebedyanskyLead->save());

        $sellResult = $lebedyanskyLead->sellLead($buyer, $campaign);
    }

    /**
     * @dataProvider providerSellLead
     *
     * @param int  $buyerId
     * @param int  $campaignId
     * @param bool $expectedResult
     *
     * @throws Exception
     */
    public function testSellLead(
        array $leadAttributes,
        $buyerId,
        $campaignId,
        $expectedResult
    ) {
        $this->loadFixtures();

        $buyer = User::model()->findByPk($buyerId);
        $campaign = Campaign::model()->findByPk($campaignId);
        $lead = new Lead();
        $lead->attributes = $leadAttributes;
        $this->assertTrue($lead->save());
        $sellResult = $lead->sellLead($buyer, $campaign);

        $this->assertEquals($expectedResult, $sellResult);

        if (true == $expectedResult) {
            $this->tester->seeInDatabase(self::PARTNER_TRANSACTIONS_TABLE, [
                'leadId' => $lead->id,
            ]);

            $this->tester->seeInDatabase(self::CAMPAIGN_TRANSACTIONS_TABLE, [
                'campaignId' => $campaignId,
                'leadId' => $lead->id,
            ]);
        }
    }

    /**
     * Аттрибуты лида.
     */
    public function providerSellLead(): array
    {
        $moscowLead = (new LeadFactory())->generateOne([
            'sourceId' => 33,
            'townId' => 598,
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
        ];
    }

    public function testSearch()
    {
        $leadsFixture = [
            (new LeadFactory())->generateOne([
                'name' => 'Вася Пупкин',
                'townId' => 598,
            ]),
            (new LeadFactory())->generateOne([
                'name' => 'Иван Пупкин',
                'townId' => 170,
            ]),
            (new LeadFactory())->generateOne([
                'name' => 'Игорь Сечин',
                'townId' => 500,
            ]),
        ];
        $this->loadToDatabase(self::LEAD_TABLE, $leadsFixture);

        $searchModel = new Lead();
        $searchModel->name = 'Пупкин';
        $searchModel->regionId = 25; // Московская область

        $searchResult = $searchModel->search();
        $this->assertInstanceOf(CActiveDataProvider::class, $searchResult);
        $this->assertEquals(1, $searchResult->totalItemCount);

        $searchModelNoRegion = new Lead();
        $searchModelNoRegion->name = 'Сечин';
        $searchResult = $searchModelNoRegion->search();
        $this->assertEquals(1, $searchResult->totalItemCount);
    }

    /**
     * @dataProvider providerCalculatePrices
     *
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
            (new UserFactory())->generateOne([
                'name' => 'Партнерская программа',
                'role' => User::ROLE_BUYER,
                'balance' => 100000,
                'id' => 10003,
            ]),
            (new UserFactory())->generateOne([
                'name' => 'Покупатель лидов с Yurcrm',
                'role' => User::ROLE_BUYER,
                'balance' => 100000,
                'id' => 10004,
                'yurcrmToken' => '123',
                'yurcrmSource' => 1,
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
                'priceByPartner' => 1,
            ]),
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
            // Вологда, отправка в партнерские системы
            (new CampaignFactory())->generateOne([
                'id' => 5,
                'regionId' => 0,
                'townId' => 170,
                'price' => 5000,
                'leadsDayLimit' => 1,
                'buyerId' => 10003,
                'active' => 1,
                'type' => Campaign::TYPE_PARTNERS,
                'sendToApi' => 1,
                'apiClass' => 'ApiLexprofit',
            ]),
            // Лебедянь
            (new CampaignFactory())->generateOne([
                'id' => 6,
                'regionId' => 0,
                'townId' => 512,
                'price' => 5000,
                'leadsDayLimit' => 1,
                'buyerId' => 10004,
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
        $leadFactory = new LeadFactory();
        $leadsData = [
            $leadFactory->generateOne([
                'leadStatus' => 1,
                'campaignId' => 1,
            ]),
            $leadFactory->generateOne([
                'leadStatus' => 1,
                'campaignId' => 1,
            ]),
            $leadFactory->generateOne([
                'leadStatus' => 1,
                'campaignId' => 0,
            ]),
            $leadFactory->generateOne([
                'leadStatus' => 2,
                'campaignId' => 0,
            ]),
        ];

        $this->loadToDatabase(self::LEAD_TABLE, $leadsData);

        $this->assertEquals(2, Lead::getStatusCounter(1));
        $this->assertEquals(3, Lead::getStatusCounter(1, false));
    }

    public function testFindDublicates()
    {
        $leadFactory = new LeadFactory();

        $leadsData = [
            $leadFactory->generateOne([
                'phone' => '1234567',
                'townId' => 100,
                'question_date' => (new \DateTime())->modify('-1 hour')->format('Y-m-d H:i:s'),
            ]),
            $leadFactory->generateOne([
                'phone' => '1234567',
                'townId' => 100,
                'question_date' => (new \DateTime())->modify('-12 hour')->format('Y-m-d H:i:s'),
            ]),
            $leadFactory->generateOne([
                'phone' => '1234567',
                'townId' => 101,
                'question_date' => (new \DateTime())->modify('-1 hour')->format('Y-m-d H:i:s'),
            ]),
            $leadFactory->generateOne([
                'phone' => '1234567',
                'townId' => 100,
                'question_date' => (new \DateTime())->modify('-1 month')->format('Y-m-d H:i:s'),
            ]),
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
