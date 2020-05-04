<?php

namespace App\tests\integration\components;

use App\models\YaPayConfirmRequest;
use Tests\integration\BaseIntegrationTest;
use YandexPaymentResponseProcessor;

class YandexPaymentResponseProcessorTest extends BaseIntegrationTest
{
    public function testProcessPaymentForUserBalance()
    {
        $requestData = [

        ];
        $secret = 'test_secret';
        $yandexRequestData = new YaPayConfirmRequest();
        $yandexRequestData->setAttributes($requestData);

        $paymentProcessor = new YandexPaymentResponseProcessor($yandexRequestData, $secret);
        $processResult = $paymentProcessor->process();

        $this->assertTrue($processResult);
    }
}
