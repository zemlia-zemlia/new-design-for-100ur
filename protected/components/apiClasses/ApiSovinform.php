<?php

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * Класс для работы с API партнерки Sovinform
 */
class ApiSovinform implements ApiClassInterface
{

    protected $baseUrl = 'https://crm.sov-inform-buro.ru/api';
    protected $key; // наш API key в партнерской системе

    /** @var Client */
    protected $httpClient;
    protected $lead;

    public function __construct()
    {
        $this->key = Yii::app()->params['sovinform']['key'];
        $this->httpClient = new Client([
            'base_uri' => $this->baseUrl,
        ]);
    }

    /**
     * Отправка лида
     * @param Lead $lead
     * @return bool
     * @throws Exception
     */
    public function send(Lead $lead):bool
    {
        $data = [
            'key' => $this->key,
            'remoteid' => $lead->id,
            'name' => $lead->name,
            'phone' => $lead->phone,
            'city' => $lead->town->name,
            'question' => $lead->question,
        ];

        $apiResponse = $this->httpClient->post('add', [
            'form_params' => $data,
        ]);

        return $this->checkResponse($apiResponse, $lead);
    }

    /**
     * Получение статуса лида
     * @param Lead $lead
     */
    public function getStatus(Lead $lead)
    {

    }

    /**
     * Проверка ответа от API
     * @param ResponseInterface $apiResponse
     * @param Lead $lead
     * @return bool
     * @throws Exception
     */
    private function checkResponse(ResponseInterface $apiResponse, Lead $lead):bool
    {
        if ($apiResponse->getStatusCode() == 200) {
            if (!stristr($apiResponse->getBody(), 'error')) {
                LoggerFactory::getLogger()->log('Лид #' . $lead->id . ' отправлен в партнерку Sovinform', 'Lead', $lead->id);
                return true;
            }

            LoggerFactory::getLogger()->log('Лид #' . $lead->id . ' НЕ отправлен в партнерку Sovinform, ответ:' . CHtml::encode($apiResponse->getBody()), 'Lead', $lead->id);

        }

        return false;
    }
}
