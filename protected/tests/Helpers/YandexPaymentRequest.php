<?php

namespace App\tests\Helpers;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * Эмуляция запроса от Яндекса с данными о зачислении денег
 * Class YandexPaymentRequest.
 */
class YandexPaymentRequest
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    private function getDemoRequestData(): array
    {
        return [
            'notification_type' => 'p2p-incoming',
            'bill_id' => '',
            'amount' => 4.98,
            'codepro' => false,
            'withdraw_amount' => 5.00,
            'unaccepted' => false,
            'label' => 'c-1',
            'datetime' => '2020-05-02T15:43:37Z',
            'sender' => '41001295604461',
            'sha1_hash' => '179e6706b683ce3e3aa47c83639fdd9d5a8a414e',
            'operation_label' => '263fa8dd-0011-5000-a000-13def588cdf6',
            'operation_id' => 641749417579024004,
            'currency' => 643,
        ];
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function makeRequest(array $requestData = null): ResponseInterface
    {
        $requestData = array_merge($this->getDemoRequestData(), $requestData) ?? $this->getDemoRequestData();

        $response = $this->client->request('POST', '/user/balanceAddRequest', [
            'form_params' => $requestData,
        ]);

        return $response;
    }
}
