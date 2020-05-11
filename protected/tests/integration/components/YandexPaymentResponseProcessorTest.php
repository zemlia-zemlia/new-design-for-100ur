<?php

namespace App\tests\integration\components;

use App\models\Money;
use App\models\TransactionCampaign;
use App\models\User;
use App\models\YaPayConfirmRequest;
use Tests\Factories\UserFactory;
use Tests\integration\BaseIntegrationTest;
use YandexPaymentResponseProcessor;
use Yii;

class YandexPaymentResponseProcessorTest extends BaseIntegrationTest
{
    protected function _before()
    {
        Yii::app()->db->createCommand()->truncateTable(User::getFullTableName());
        Yii::app()->db->createCommand()->truncateTable(Money::getFullTableName());
        Yii::app()->db->createCommand()->truncateTable(TransactionCampaign::getFullTableName());
    }

    /**
     * Тест пополнения баланса пользователя
     */
    public function testProcessPaymentForUserBalance()
    {
        $userFactory = new UserFactory();
        $userAttributes = $userFactory->generateOne([
            'id' => 100,
            'balance' => 0,
        ]);

        $this->loadToDatabase(User::getFullTableName(), [$userAttributes]);
        $this->tester->seeInDatabase('100_user', ['id' => 100]);

        $requestData = [
            'label' => 'u-100',
            'amount' => 125,
        ];
        $secret = 'test_secret';
        $yandexRequestData = new YaPayConfirmRequest();
        $yandexRequestData->setAttributes($requestData);

        $paymentProcessor = new YandexPaymentResponseProcessor($yandexRequestData, $secret, false);
        $processResult = $paymentProcessor->process();

        $this->assertTrue($processResult);
        $this->tester->seeInDatabase(User::getFullTableName(), [
            'id' => 100,
            'balance' => 12500,
        ]);

        $this->tester->seeInDatabase(Money::getFullTableName(), [
            'type' => Money::TYPE_INCOME,
            'direction' => 501,
        ]);

        $this->tester->seeInDatabase(TransactionCampaign::getFullTableName(), [
            'sum' => 12500,
            'buyerId' => 100,
        ]);
    }
}
