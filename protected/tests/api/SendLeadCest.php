<?php

use Codeception\Util\HttpCode;
use Faker\Factory;

/**
 * Class SendLeadCest
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

        $this->leadSourceAttributes = $this->generateValidSourceAttributes();
        $I->haveInDatabase(self::LEAD_SOURCE_TABLE, $this->leadSourceAttributes);
    }

    public function trySendGetRequest(ApiTester $I)
    {
        $I->sendGET(self::API_URL);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'code' => 400,
            'message' => 'No input data'
        ]);
    }

    public function trySendRequestWithoutAppId(ApiTester $I)
    {
        $I->sendPOST(self::API_URL, []);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'code' => 400,
            'message' => 'Unknown sender. Check appId parameter'
        ]);
    }

    /**
     * Отправка лида, для которого нет кампании и он не будет автоматически продан
     * @param ApiTester $I
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
     * Тест отправки лида в тестовом режиме: возвращается ответ, но лид не сохраняется
     * @param ApiTester $I
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
     * Лид, который должен быть продан в кампанию
     * @param ApiTester $I
     */
    public function sendLeadWithCampaign(ApiTester $I)
    {
        $buyerAttributes = $this->generateValidUserAttributes(['role' => User::ROLE_BUYER]);
        $I->haveInDatabase(self::USER_TABLE, $buyerAttributes);

        $campaignAttributes = $this->generateValidCampaignAttributes([
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
     * Попытка отправить лид с неправильной сигнатурой
     * @param ApiTester $I
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
     * Отправка лида из несуществующего города
     * @param ApiTester $I
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
     * Отправка лида повторно
     * @param ApiTester $I
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
            'message' => 'Dublicates found'
        ]);
    }

    /**
     * @return array
     */
    protected function generateValidSourceAttributes(): array
    {
        return [
            'id' => $this->faker->randomNumber(),
            'appId' => self::APP_ID,
            'secretKey' => self::SECRET_KEY,
            'name' => 'Партнерка',
            'active' => 1,
            'userId' => 10000,
            'priceByPartner' => 1
        ];
    }

    /**
     * @param array $forcedFields
     * @return array
     */
    protected function generateValidUserAttributes($forcedFields = []): array
    {
        $attributes = [
            'id' => $this->faker->numberBetween(1, 100000),
            'name' => $this->faker->name,
            'lastName' => $this->faker->lastName,
            'role' => User::ROLE_CLIENT,
            'email' => $this->faker->randomNumber(6) . '@yurcrm.ru',
            'phone' => PhoneHelper::normalizePhone($this->faker->phoneNumber),
            'active100' => 1,
            'townId' => $this->faker->numberBetween(1, 999),
            'balance' => 1000000,
            'priceCoeff' => 0.5,
        ];

        $attributes = array_merge($attributes, $forcedFields);

        return $attributes;
    }

    /**
     * @param array $forcedFields
     * @return array
     */
    protected function generateValidCampaignAttributes($forcedFields = []): array
    {
        $requestParams = [
            'id' => $this->faker->randomNumber(),
            'regionId' => $this->faker->numberBetween(1, 99),
            'townId' => $this->faker->numberBetween(1, 999),
            'timeFrom' => 0,
            'timeTo' => 24,
            'price' => 15000,
            'leadsDayLimit' => 10,
            'realLimit' => 10,
            'brakPercent' => 20,
            'buyerId' => 3333,
            'active' => 1,
            'type' => Campaign::TYPE_BUYERS,
        ];

        $requestParams = array_merge($requestParams, $forcedFields);
        return $requestParams;
    }

    /**
     * @param array $forcedFields Массив атрибутов, которые нужно переопределить вручную
     * @return array
     */
    protected function generateValidLeadRequestData($forcedFields = []): array
    {
        $secretKey = self::SECRET_KEY;
        $requestParams = [
            'name' => $this->faker->name,
            'phone' => PhoneHelper::normalizePhone($this->faker->phoneNumber),
            'email' => 'vasya@yurcrm.ru',
            'town' => 'Москва',
            'question' => $this->faker->paragraph,
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
