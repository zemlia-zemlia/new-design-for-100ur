<?php

namespace App\tests\integration\components;

use App\Exceptions\YandexPaymentException;
use App\models\Answer;
use App\models\Chat;
use App\models\Money;
use App\models\Question;
use App\models\TransactionCampaign;
use App\models\User;
use App\models\YaPayConfirmRequest;
use App\tests\Factories\ChatFactory;
use Tests\Factories\AnswerFactory;
use Tests\Factories\QuestionFactory;
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
        Yii::app()->db->createCommand()->truncateTable(Question::getFullTableName());
        Yii::app()->db->createCommand()->truncateTable(Answer::getFullTableName());
        Yii::app()->db->createCommand()->truncateTable(Chat::getFullTableName());
    }

    /**
     * Тест обработки невалидного запроса
     * @throws YandexPaymentException
     */
    public function testProcessInvalidData()
    {
        $requestData = [
            'label' => 'z-100',
            'amount' => 125,
        ];
        $secret = 'test_secret';
        $yandexRequestData = new YaPayConfirmRequest();
        $yandexRequestData->setAttributes($requestData);

        $paymentProcessor = new YandexPaymentResponseProcessor($yandexRequestData, $secret, false);

        $this->expectException(YandexPaymentException::class);
        $paymentProcessor->process();
    }

    /**
     * Тест пополнения баланса пользователя.
     */
    public function testProcessPaymentForUserBalance()
    {
        $userFactory = new UserFactory();
        $userAttributes = $userFactory->generateOne([
            'id' => 100,
            'balance' => 0,
        ]);

        $this->loadToDatabase(User::getFullTableName(), [$userAttributes]);
        $this->tester->seeInDatabase(User::getFullTableName(), ['id' => 100]);

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

    public function testPaymentForQuestion()
    {
        $questionFactory = new QuestionFactory();
        $questionAttributes = $questionFactory->generateOne([
            'id' => 100,
            'payed' => 0,
        ]);

        $this->loadToDatabase(Question::getFullTableName(), [$questionAttributes]);
        $this->tester->seeInDatabase(Question::getFullTableName(), ['id' => 100]);

        $requestData = [
            'label' => 'q-100',
            'withdraw_amount' => 125,
        ];
        $secret = 'test_secret';
        $yandexRequestData = new YaPayConfirmRequest();
        $yandexRequestData->setAttributes($requestData);

        $paymentProcessor = new YandexPaymentResponseProcessor($yandexRequestData, $secret, false);
        $processResult = $paymentProcessor->process();

        $this->assertTrue($processResult);
        $this->tester->seeInDatabase(Question::getFullTableName(), [
            'id' => 100,
            'payed' => 1,
            'price' => 12500,
        ]);

        $this->tester->seeInDatabase(Money::getFullTableName(), [
            'type' => Money::TYPE_INCOME,
            'direction' => 504,
        ]);
    }

    public function testPaymentForAnswer()
    {
        $userFactory = new UserFactory();
        $userAttributes = $userFactory->generateOne([
            'id' => 100,
            'balance' => 0,
        ]);

        $this->loadToDatabase(User::getFullTableName(), [$userAttributes]);
        $this->tester->seeInDatabase(User::getFullTableName(), ['id' => 100]);

        $questionFactory = new QuestionFactory();
        $questionAttributes = $questionFactory->generateOne([
            'id' => 300,
        ]);

        $this->loadToDatabase(Question::getFullTableName(), [$questionAttributes]);
        $this->tester->seeInDatabase(Question::getFullTableName(), ['id' => 300]);

        $answerFactory = new AnswerFactory();
        $answerAttributes = $answerFactory->generateOne([
            'id' => 200,
            'authorId' => 100,
            'questionId' => 300,
        ]);

        $this->loadToDatabase(Answer::getFullTableName(), [$answerAttributes]);
        $this->tester->seeInDatabase(Answer::getFullTableName(), ['id' => 200]);

        $requestData = [
            'label' => 'a-200',
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
            'balance' => 12500 * (1 - \YandexPaymentAnswer::SERVICE_COMMISSION),
        ]);

        $this->tester->seeInDatabase(Money::getFullTableName(), [
            'type' => Money::TYPE_INCOME,
            'direction' => 505,
            'value' => 12500,
        ]);

        $this->tester->seeInDatabase(TransactionCampaign::getFullTableName(), [
            'sum' => 12500 * (1 - \YandexPaymentAnswer::SERVICE_COMMISSION),
            'buyerId' => 100,
        ]);
    }

    public function testPaymentForChat()
    {
        $userFactory = new UserFactory();
        $userAttributes = $userFactory->generateOne([
            'id' => 100,
            'balance' => 0,
        ]);

        $this->loadToDatabase(User::getFullTableName(), [$userAttributes]);
        $this->tester->seeInDatabase(User::getFullTableName(), ['id' => 100]);

        $yuristAttributes = $userFactory->generateOne([
            'id' => 101,
            'balance' => 0,
        ]);

        $this->loadToDatabase(User::getFullTableName(), [$yuristAttributes]);
        $this->tester->seeInDatabase(User::getFullTableName(), ['id' => 101]);

        $chatFactory = new ChatFactory();
        $chatAttributes = $chatFactory->generateOne([
            'id' => 200,
            'user_id' => 100,
            'lawyer_id' => 101,
        ]);

        $this->loadToDatabase(Chat::getFullTableName(), [$chatAttributes]);
        $this->tester->seeInDatabase(Chat::getFullTableName(), ['id' => 200]);

        $requestData = [
            'label' => 'c-200',
            'amount' => 100,
        ];
        $secret = 'test_secret';
        $yandexRequestData = new YaPayConfirmRequest();
        $yandexRequestData->setAttributes($requestData);

        $paymentProcessor = new YandexPaymentResponseProcessor($yandexRequestData, $secret, false);
        $processResult = $paymentProcessor->process();

        $this->assertTrue($processResult);

        $this->tester->seeInDatabase(Money::getFullTableName(), [
            'type' => Money::TYPE_INCOME,
            'direction' => 505,
            'value' => 10000,
        ]);

        $this->tester->seeInDatabase(Chat::getFullTableName(), [
            'is_payed' => 1,
        ]);

        $this->tester->seeInDatabase(TransactionCampaign::getFullTableName(), [
            'sum' => 10000 * (1 - \YandexPaymentChat::SERVICE_COMMISSION),
            'buyerId' => 101,
        ]);
    }
}
