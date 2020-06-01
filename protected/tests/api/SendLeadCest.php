<?php

namespace Tests\Api;

use ApiTester;
use App\models\Lead;
use App\models\User;
use Codeception\Example;
use Codeception\Util\HttpCode;
use Faker\Factory;
use Tests\Factories\CampaignFactory;
use Tests\Factories\LeadFactory;
use Tests\Factories\LeadSourceFactory;
use Tests\Factories\UserFactory;
use Yii;

/**
 * Class SendLeadCest.
 */
class SendLeadCest
{
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /** @var array */
    protected $leadSourceAttributes;

    const LEAD_SOURCE_TABLE = '100_leadsource';
    const LEADS_TABLE = '100_lead';
    const CAMPAIGNS_TABLE = '100_campaign';
    const USER_TABLE = '100_user';
    const PARTNER_TRANSACTION_TABLE = '100_partnerTransaction';

    const API_URL = '/api/sendLead';
    const APP_ID = 188;
    const SECRET_KEY = 'a3388';

    public function __construct()
    {
        $this->faker = Factory::create('ru_RU');
    }

    public function _before(ApiTester $I)
    {
        Yii::app()->db->createCommand()->truncateTable(self::LEAD_SOURCE_TABLE);

        $this->leadSourceAttributes = (new LeadSourceFactory())->generateOne([
            'appId' => self::APP_ID,
            'secretKey' => self::SECRET_KEY,
        ]);

        $I->haveInDatabase(self::LEAD_SOURCE_TABLE, $this->leadSourceAttributes);
    }

    public function trySendGetRequest(ApiTester $I)
    {
        $I->sendGET(self::API_URL);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'code' => 400,
            'message' => 'No input data',
        ]);
    }

    public function trySendRequestWithoutAppId(ApiTester $I)
    {
        $I->sendPOST(self::API_URL, []);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'code' => 400,
            'message' => 'Unknown sender. Check appId parameter',
        ]);
    }

    /**
     * Отправка лида, для которого нет кампании и он не будет автоматически продан.
     */
    public function sendValidLeadWithoutCampaign(ApiTester $I)
    {
        $I->seeInDatabase(self::LEAD_SOURCE_TABLE, [
            'id' => $this->leadSourceAttributes['id'],
            'active' => $this->leadSourceAttributes['active'],
            'appId' => self::APP_ID,
        ]);

        $requestParams = $this->generateValidLeadRequestData();

        $I->sendPOST(self::API_URL, $requestParams);

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['code' => HttpCode::OK]);

        $I->seeInDatabase(self::LEADS_TABLE, [
            'phone' => $requestParams['phone'],
            'sourceId' => $this->leadSourceAttributes['id'],
            'leadStatus' => Lead::LEAD_STATUS_DEFAULT,
        ]);
    }

    /**
     * Тест отправки лида в тестовом режиме: возвращается ответ, но лид не сохраняется.
     */
    public function sendCorrectLeadInTestMode(ApiTester $I)
    {
        $requestParams = $this->generateValidLeadRequestData(['testMode' => 1]);

        $I->sendPOST(self::API_URL, $requestParams);

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['code' => HttpCode::OK]);

        $I->dontSeeInDatabase(self::LEADS_TABLE, [
            'phone' => $requestParams['phone'],
            'sourceId' => $this->leadSourceAttributes['id'],
        ]);
    }

    /**
     * Лид, который должен быть продан в кампанию.
     */
    public function sendLeadWithCampaign(ApiTester $I)
    {
        $buyerAttributes = (new UserFactory())->generateOne(['role' => User::ROLE_BUYER]);
        $I->haveInDatabase(self::USER_TABLE, $buyerAttributes);

        $campaignAttributes = (new CampaignFactory())->generateOne([
            'regionId' => 0,
            'townId' => 598,
            'buyerId' => $buyerAttributes['id'],
        ]);
        $I->haveInDatabase(self::CAMPAIGNS_TABLE, $campaignAttributes);

        $sendLeadRequestParams = $this->generateValidLeadRequestData([
            'town' => 'Москва',
        ]);
        $I->sendPOST(self::API_URL, $sendLeadRequestParams);

        $I->seeResponseContainsJson(['code' => 200]);

        $I->seeInDatabase(self::LEADS_TABLE, [
            'phone' => $sendLeadRequestParams['phone'],
            'sourceId' => $this->leadSourceAttributes['id'],
            'leadStatus' => Lead::LEAD_STATUS_SENT,
            'buyerId' => $buyerAttributes['id'],
            'campaignId' => $campaignAttributes['id'],
        ]);
    }

    /**
     * Попытка отправить лид с неправильной сигнатурой.
     */
    public function sendLeadWithIncorrectSignature(ApiTester $I)
    {
        $sendLeadRequestParams = $this->generateValidLeadRequestData();
        $sendLeadRequestParams['signature'] = 'hello_world';

        $I->sendPOST(self::API_URL, $sendLeadRequestParams);

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['code' => 400, 'message' => 'Signature is wrong']);

        $I->dontSeeInDatabase(self::LEADS_TABLE, [
            'phone' => $sendLeadRequestParams['phone'],
            'sourceId' => $this->leadSourceAttributes['id'],
        ]);
    }

    /**
     * Отправка лида из несуществующего города.
     */
    public function sendLeadWithIncorrectTown(ApiTester $I)
    {
        $sendLeadRequestParams = $this->generateValidLeadRequestData(['town' => 'Лименда-15']);

        $I->sendPOST(self::API_URL, $sendLeadRequestParams);

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'code' => 404,
            'message' => 'Unknown town. Provide correct town name in Russian language',
        ]);

        $I->dontSeeInDatabase(self::LEADS_TABLE, [
            'phone' => $sendLeadRequestParams['phone'],
            'sourceId' => $this->leadSourceAttributes['id'],
        ]);
    }

    /**
     * Отправка лида повторно.
     */
    public function sendDuplicate(ApiTester $I)
    {
        $sendLeadRequestParams = $this->generateValidLeadRequestData();

        $I->sendPOST(self::API_URL, $sendLeadRequestParams);

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'code' => 200,
        ]);

        $I->seeInDatabase(self::LEADS_TABLE, [
            'phone' => $sendLeadRequestParams['phone'],
            'sourceId' => $this->leadSourceAttributes['id'],
        ]);

        // отправим тот же лид еще раз

        $I->sendPOST(self::API_URL, $sendLeadRequestParams);

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'code' => 400,
            'message' => 'Dublicates found',
        ]);
    }

    /**
     * Отправка лида от вебмастера. Проверяем, что вебмастеру создалась/не создалась транзакция.
     *
     * @dataProvider providerCampaignWithUser
     */
    public function sendLeadFromSourceWithUser(ApiTester $I, Example $example)
    {
        $buyerAttributes = (new UserFactory())->generateOne(['role' => User::ROLE_BUYER]);
        $I->haveInDatabase(self::USER_TABLE, $buyerAttributes);

        $campaignAttributes = (new CampaignFactory())->generateOne([
            'regionId' => 0,
            'townId' => 598,
            'buyerId' => $buyerAttributes['id'],
        ]);
        $I->haveInDatabase(self::CAMPAIGNS_TABLE, $campaignAttributes);

        $webmasterAttributes = (new UserFactory())->generateOne([
            'role' => User::ROLE_PARTNER,
            'balance' => 0,
        ]);
        if ($example['partnerId']) {
            $webmasterAttributes['id'] = $example['partnerId'];
        }

        $leadSourceAttributes = (new LeadSourceFactory())->generateOne([
            'userId' => $webmasterAttributes['id'],
            'secretKey' => self::SECRET_KEY,
        ]);
        $I->haveInDatabase(self::USER_TABLE, $webmasterAttributes);
        $I->haveInDatabase(self::LEAD_SOURCE_TABLE, $leadSourceAttributes);

        $sendLeadRequestParams = $this->generateValidLeadRequestData([
            'town' => 'Москва',
            'appId' => $leadSourceAttributes['appId'],
        ]);
        $I->sendPOST(self::API_URL, $sendLeadRequestParams);

        $I->seeResponseContainsJson(['code' => 200, 'buyPrice' => $example['buyPrice']]);

        // должна создаться транзакция вебмастера
        $I->seeInDatabase(self::PARTNER_TRANSACTION_TABLE, [
            'partnerId' => $webmasterAttributes['id'],
            'sourceId' => $leadSourceAttributes['id'],
            'sum' => $example['partnerTransactionSum'],
        ]);

        // лид продан
        $I->seeInDatabase(self::LEADS_TABLE, [
            'phone' => $sendLeadRequestParams['phone'],
            'sourceId' => $leadSourceAttributes['id'],
            'leadStatus' => Lead::LEAD_STATUS_SENT,
            'buyerId' => $buyerAttributes['id'],
            'campaignId' => $campaignAttributes['id'],
        ]);
    }

    protected function providerCampaignWithUser(): array
    {
        return [
            'regular webmaster' => [
                'partnerId' => null,
                'buyPrice' => '95.00',
                'partnerTransactionSum' => 9500,
            ],
            '100yuristov internal webmaster' => [
                'partnerId' => Yii::app()->params['webmaster100yuristovId'],
                'buyPrice' => '95.00',
                'partnerTransactionSum' => 0,
            ],
        ];
    }

    /**
     * @param array $forcedFields Массив атрибутов, которые нужно переопределить вручную
     */
    protected function generateValidLeadRequestData($forcedFields = []): array
    {
        $secretKey = self::SECRET_KEY;
        $leadAttributes = (new LeadFactory())->generateOne();
        $requestParams = [
            'name' => $leadAttributes['name'],
            'phone' => $leadAttributes['phone'],
            'email' => $leadAttributes['email'],
            'town' => 'Москва',
            'question' => $leadAttributes['question'],
            'price' => 95,
            'appId' => self::APP_ID,
            'testMode' => 0,
        ];

        $requestParams = array_merge($requestParams, $forcedFields);

        $signature = md5($requestParams['name'] .
            $requestParams['phone'] .
            $requestParams['town'] .
            $requestParams['question'] .
            $requestParams['appId'] .
            $secretKey);

        $requestParams['signature'] = $signature;

        return $requestParams;
    }
}
