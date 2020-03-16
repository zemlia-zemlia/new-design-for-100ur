<?php

use App\models\Lead;

/**
 * Класс для работы с API Leadia.
 */
class ApiLeadia implements ApiClassInterface
{
    protected $url = 'http://cloud1.leadia.org/lead.php';
    protected $key = 13550; // наш id в партнерской системе
    protected $curl;
    protected $lead;
    protected $testMode = false;

    public function __construct()
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        if (YII_DEV == true) {
            $this->testMode = true;
        }
    }

    /**
     * отправка лида.
     *
     * @param Lead $lead
     */
    public function send(Lead $lead)
    {
        $data = [
            'form_page' => 'https://100yuristov.com',
            'referer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
            'client_ip' => '',
            'userid' => $this->key,
            'product' => 'lawyer',
            'template' => 'default',
            'key' => '',
            'first_last_name' => (false == $this->testMode) ? CHtml::encode($lead->name) : 'тест',
            'phone' => $lead->phone,
            'email' => $lead->email,
            'region' => $lead->town->name,
            'question' => CHtml::encode($lead->question),
            'subaccount' => '',
        ];

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($data));

        $apiResponse = curl_exec($this->curl);

        LoggerFactory::getLogger()->log('Отправляем лид #' . $lead->id . ' в партнерку Leadia: ' . json_encode($data), 'App\models\Lead', $lead->id);
        LoggerFactory::getLogger()->log('Ответ API Leadia: ' . $apiResponse, 'App\models\Lead', $lead->id);

        $apiResponseJSON = json_decode($apiResponse, true);

        curl_close($this->curl);

        return $this->checkResponse($apiResponseJSON, $lead);
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
        if (0 == sizeof($apiResponse)) {
            return false;
        }

        if (is_array($apiResponse) && isset($apiResponse['status']) && 0 == strcmp($apiResponse['status'], 'ok')) {
            LoggerFactory::getLogger()->log('Лид #' . $lead->id . ' отправлен в партнерку Leadia', 'App\models\Lead', $lead->id);

            return true;
        }

        LoggerFactory::getLogger()->log('Ошибка при отправке лида #' . $lead->id . ' в партнерку Leadia', 'App\models\Lead', $lead->id);

        return false;
    }
}
