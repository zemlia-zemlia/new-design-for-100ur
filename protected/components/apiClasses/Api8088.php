<?php

/**
 * Класс для работы с API партнерки 8088.ru
 * Документация по API: https://partner.8088.ru/pub/send.txt.
 */
class Api8088 implements ApiClassInterface
{
    protected $url = 'http://partner.8088.ru/query.php';
    protected $key = 13969; // наш id в партнерской системе
    protected $curl;
    protected $lead;

    public function __construct()
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * отправка лида.
     *
     * @param Lead $lead
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

        LoggerFactory::getLogger()->log('Отправляем лид #' . $lead->id . ' в партнерку 8088: ' . json_encode($data), 'Lead', $lead->id);
        LoggerFactory::getLogger()->log('Ответ API 8088: ' . mb_strlen((string) $apiResponse) . ' символов', 'Lead', $lead->id);

        curl_close($this->curl);

        return $this->checkResponse($apiResponse, $lead);
    }

    /**
     * Разбор ответа API.
     *
     * @param type $apiResponse
     * @param type $lead
     *
     * @return bool
     */
    private function checkResponse($apiResponse, $lead)
    {
        if (!$apiResponse) {
            LoggerFactory::getLogger()->log('Ошибка при отправке лида #' . $lead->id . ' в партнерку 8088', 'Lead', $lead->id);

            return false;
        } else {
            LoggerFactory::getLogger()->log('Лид #' . $lead->id . ' отправлен в партнерку 8088', 'Lead', $lead->id);

            return true;
        }
    }
}
