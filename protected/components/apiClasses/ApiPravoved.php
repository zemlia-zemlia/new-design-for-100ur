<?php

use App\models\Lead;

/**
 * Класс для работы с API партнерки Pravoved.
 */
class ApiPravoved implements ApiClassInterface
{
    protected $url = 'https://pravoved.ru/polling/';
    protected $townApiUrl = 'https://pravoved.ru/rest/cities/';
    protected $key = '24b554b143cf6c8dbbd0b55c4e7bd395'; // наш id в партнерской системе
    protected $curl;
    protected $lead;

    public function __construct()
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HEADER, false);
    }

    /**
     * отправка лида.
     *
     * @param Lead $lead
     */
    public function send(Lead $lead)
    {
        $townId = $this->getTownId($lead->town->name);

        $requestData = [
            'd' => 'jsonp',
            'event_type' => 'lead_market_lead',
            'edata[type]' => 'addLeadCd',
            'edata[type_id]' => '0',
            'edata[city_id]' => $townId,
            'edata[name]' => $lead->name,
            'edata[phone]' => mb_substr($lead->phone, 1, 10, 'utf-8'),
            'edata[user_agent]' => '',
            'edata[question_text]' => $lead->question,
            'cd-referral' => $this->key,
            'putm_content' => '',
            'putm_medium' => '',
        ];

        LoggerFactory::getLogger()->log('Отправляем лид #' . $lead->id . ' в партнерку Pravoved', 'App\models\Lead', $lead->id);

        $apiUrl = $this->url . '?' . http_build_query($requestData);
        curl_setopt($this->curl, CURLOPT_URL, $apiUrl);

        // получаем ответ от Правоведа GET запросом
        $apiResponse = curl_exec($this->curl);

        LoggerFactory::getLogger()->log('Ответ API Pravoved: ' . CHtml::encode($apiResponse), 'App\models\Lead', $lead->id);

        curl_close($this->curl);

        return $this->checkResponse($apiResponse, $lead);
    }

    /**
     * Возвращает id города в базе Правоведа.
     *
     * @param type $townName Название города
     */
    private function getTownId($townName)
    {
        $pravovedTownGetter = PravovedGetTown::getInstance();

        return $pravovedTownGetter->getTownId($townName);
    }

    /**
     * Анализирует ответ от API.
     *
     * @param string $apiResponse
     * @param Lead   $lead
     *
     * @return bool
     */
    private function checkResponse($apiResponse, $lead)
    {
        if (41 == strlen($apiResponse)) {
            LoggerFactory::getLogger()->log('Лид #' . $lead->id . ' отправлен в партнерку Pravoved', 'App\models\Lead', $lead->id);

            return true;
        }

        LoggerFactory::getLogger()->log('Ошибка при отправке лида #' . $lead->id . ' в партнерку Pravoved', 'App\models\Lead', $lead->id);

        return false;
    }
}
