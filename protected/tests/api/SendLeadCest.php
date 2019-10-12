<?php

use Codeception\Util\HttpCode;
use Faker\Factory;

/**
 * @todo подумать, как запускать тестирование, чтобы в тестируемом скрипте подгружался тестовый конфиг
 * Class SendLeadCest
 */
class SendLeadCest
{
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    const LEAD_SOURCE_TABLE = '100_leadsource';
    const LEADS_TABLE = '100_lead';
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
    }

    protected function trySendGetRequest(ApiTester $I)
    {
        $I->sendGET(self::API_URL);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'code' => 400,
            'message' => 'No input data'
        ]);
    }

    protected function trySendRequestWithoutAppId(ApiTester $I)
    {
        $I->sendPOST(self::API_URL, []);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'code' => 400,
            'message' => 'Unknown sender. Check appId parameter'
        ]);
    }

    /**
     * @param ApiTester $I
     */
    public function trySendLeadTest(ApiTester $I)
    {
        $I->haveInDatabase(self::LEAD_SOURCE_TABLE, [
            'id' => 33,
            'appId' => self::APP_ID,
            'secretKey' => self::SECRET_KEY,
            'name' => 'Партнерка',
            'active' => 1,
            'userId' => 10000,
            'priceByPartner' => 1
        ]);

        $I->seeInDatabase(self::LEAD_SOURCE_TABLE, ['id' => 33]);

        $name = $this->faker->name;
        $phone = PhoneHelper::normalizePhone($this->faker->phoneNumber);
        $town = 'Москва';
        $email = 'vasya@yurcrm.ru';
        $question = $this->faker->paragraph;
        $appId = self::APP_ID;
        $secretKey = self::SECRET_KEY;

        $signature = md5($name . $phone . $town . $question . $appId . $secretKey);

        $requestParams = [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'town' => $town,
            'question' => $question,
            'price' => 95,
            'appId' => $appId,
            'signature' => $signature,
            'testMode' => 0,
        ];

        $I->sendPOST(self::API_URL, $requestParams);

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['code' => 200]);

        $I->seeInDatabase(self::LEADS_TABLE, ['phone' => $phone, 'sourceId' => 33]);
    }
}
