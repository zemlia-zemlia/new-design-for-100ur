<?php

namespace App\components\apiClasses;

use ApiClassInterface;
use App\extensions\Logger\LoggerFactory;
use App\models\Lead;
use CHtml;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use Yii;

class ApiGainnet implements ApiClassInterface
{
    protected $baseUrl = 'https://gainnet.ru';
    protected $key; // наш API key в партнерской системе

    /** @var Client */
    protected $httpClient;
    protected $lead;

    public function __construct()
    {
        $this->key = Yii::app()->params['gainnet']['key'];
        $this->httpClient = new Client([
            'base_uri' => $this->baseUrl,
        ]);
    }

    /**
     * Отправка лида в партнерское API.
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function send(Lead $lead)
    {
        $data = [
            'id' => $this->key,
            'name' => $lead->name,
            'phone' => $lead->phone,
            'region_name' => $lead->town->region->capital->name,
            'text' => $lead->question,
        ];

        try {
            $apiResponse = $this->httpClient->post('/api/v1/addlead', [
                'form_params' => $data,
            ]);
        } catch (ClientException $e) {
            LoggerFactory::getLogger()->log('Лид #' . $lead->id . ' НЕ отправлен в партнерку Gainnet, возникло исключение:' . $e->getMessage(), 'Lead', $lead->id);

            return false;
        }

        return $this->checkResponse($apiResponse, $lead);
    }

    /**
     * Обработка ответа от API.
     *
     * @throws \Exception
     */
    private function checkResponse(ResponseInterface $apiResponse, Lead $lead): bool
    {
        if (200 == $apiResponse->getStatusCode()) {
            $responseBody = $apiResponse->getBody()->getContents();
            $responseBodyDecoded = json_decode($responseBody, true);

            if (true == $responseBodyDecoded['status'] && 'Success' == $responseBodyDecoded['message']) {
                LoggerFactory::getLogger()->log('Лид #' . $lead->id . ' отправлен в партнерку Gainnet', 'Lead', $lead->id);

                return true;
            }

            LoggerFactory::getLogger()->log('Лид #' . $lead->id . ' НЕ отправлен в партнерку Gainnet, ответ:' . CHtml::encode($responseBody), 'Lead', $lead->id);
        }

        return false;
    }
}
