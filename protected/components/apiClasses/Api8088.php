<?php

namespace App\components\apiClasses;

use ApiClassInterface;
use App\models\Lead;
use CHtml;
use App\extensions\Logger\LoggerFactory;
use Yii;

/**
 * Класс для работы с API партнерки 8088.ru
 * Документация по API: https://partner.8088.ru/pub/send.txt.
 */
class Api8088 implements ApiClassInterface
{
    // @todo вынести параметры подключения в конфиг, передавать как зависимости
    protected $url = 'http://partner.8088.ru/query.php';
    protected $key; // наш id в партнерской системе
    protected $curl;
    protected $lead;

    public function __construct()
    {
        $this->key = Yii::app()->params['api8088']['key'];
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * отправка лида.
     *
     * @param Lead $lead
     * @return bool
     * @throws \Exception
     */
    public function send(Lead $lead)
    {
        $data = [
            'name' => $lead->name,
            'phone' => $lead->phone,
            'comment' => CHtml::encode($lead->question),
            'programID' => '3',
            'partnerID' => $this->key,
            'enc8' => 'utf-8',
        ];

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($data));

        $apiResponse = curl_exec($this->curl);

        LoggerFactory::getLogger()->log('Отправляем лид #' . $lead->id . ' в партнерку 8088: ' . json_encode($data), 'App\models\Lead', $lead->id);
        LoggerFactory::getLogger()->log('Ответ API 8088: ' . mb_strlen((string)$apiResponse) . ' символов', 'App\models\Lead', $lead->id);

        curl_close($this->curl);

        return $this->checkResponse($apiResponse, $lead);
    }

    /**
     * Разбор ответа API.
     *
     * @param string $apiResponse
     * @param Lead $lead
     *
     * @return bool
     * @throws \Exception
     */
    private function checkResponse($apiResponse, $lead)
    {
        if (!$apiResponse) {
            LoggerFactory::getLogger()->log('Ошибка при отправке лида #' . $lead->id . ' в партнерку 8088', 'App\models\Lead', $lead->id);

            return false;
        } else {
            LoggerFactory::getLogger()->log('Лид #' . $lead->id . ' отправлен в партнерку 8088', 'App\models\Lead', $lead->id);

            return true;
        }
    }
}
