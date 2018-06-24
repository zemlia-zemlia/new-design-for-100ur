<?php

/**
 * Класс для работы с API партнерки Lexprofit
 */
class ApiLexprofit implements ApiClassInterface
{

    protected $url = "http://api.lexprofit.ru/v1";
    protected $key = 250; // наш id в партнерской системе
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
     * отправка лида
     * @param Lead $lead 
     */
    public function send(Lead $lead)
    {
        $data = [
            'wm_id' => $this->key,
            'name' => $lead->name,
            'phone' => $lead->phone,
            'location' => $lead->town->name,
            'question' => $lead->question,
        ];

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($data));
        
        $apiResponse = curl_exec($this->curl);
        
        LoggerFactory::getLogger()->log('Отправляем лид #' . $lead->id . ' в партнерку Lexprofit: ' . json_encode($data), 'Lead', $lead->id);
        LoggerFactory::getLogger()->log('Ответ API Lexprofit: ' . $apiResponse, 'Lead', $lead->id);
                
        $apiResponseJSON = json_decode($apiResponse, true);

        curl_close($this->curl);
        
        return $this->checkResponse($apiResponseJSON, $lead);
    }
    
    /**
     * Разбор ответа API
     * @param type $apiResponse
     * @param type $lead
     * @return boolean
     */
    private function checkResponse($apiResponse, $lead)
    {
        if(sizeof($apiResponse) == 0) {
            return false;
        }
        
        if(is_array($apiResponse) && isset($apiResponse['success'])) {
            LoggerFactory::getLogger()->log('Лид #' . $lead->id . ' отправлен в партнерку Lexprofit', 'Lead', $lead->id);
            return true;
        } 
        
        if(isset($apiResponse['warning']) && isset($apiResponse['warning']['msg'])) {
            $errorMessage = $apiResponse['warning']['msg'];
        }

        if(isset($apiResponse['error']) && isset($apiResponse['error']['msg'])) {
            $errorMessage = $apiResponse['error']['msg'];
        }
        if(!$errorMessage) {
            $errorMessage = 'Неизвестная ошибка';
        }
        LoggerFactory::getLogger()->log('Ошибка при отправке лида #' . $lead->id . ' в партнерку Lexprofit: ' . $errorMessage, 'Lead', $lead->id);
        return false;
         
    }
}
