<?php

use App\tests\Helpers\YandexPaymentRequest;
use GuzzleHttp\Client;

class YandexPaymentTestCommand extends CConsoleCommand
{
    /**
     * Отправляет тестовый запрос об оплате
     * @param string $label
     * @param float $amount
     */
    public function actionPay(string $label, float $amount)
    {
        $client = new Client([
            'base_uri' => Yii::app()->createUrl('/user/balanceAddRequest'),
        ]);

        $requestParams = [
            'label' => $label,
            'amount' => $amount,
        ];

        $yandexPaymentRequester = new YandexPaymentRequest($client);

        try {
            $response = $yandexPaymentRequester->makeRequest($requestParams);
        } catch (\GuzzleHttp\Exception\ClientException $clientException) {
            echo 'Exception: ' . $clientException->getMessage();
        }

    }

    /**
     * Показ помощи
     */
    public function actionHelp()
    {
        echo 'Моделирование запроса подтверждения платежа от Яндекса:' . PHP_EOL;
        echo 'Usage: ./yiic yandexpaymenttest pay --label=value --amount=value' . PHP_EOL;
        echo 'label: буква-id оплачиваемой сущности' . PHP_EOL;
        echo 'u - user' . PHP_EOL;
        echo 'q - question' . PHP_EOL;
        echo 'a - answer' . PHP_EOL;
        echo 'c - chat' . PHP_EOL;
        echo 'amount: сумма платежа в рублях' . PHP_EOL;
    }
}
