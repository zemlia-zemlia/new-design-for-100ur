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
            'location' => $lead->town,
            'question' => $lead->question,
        ];

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($data));
        
        $apiResponse = json_decode(curl_exec($this->curl), true);
        curl_close($this->curl);
        
        return $this->checkResponse($apiResponse, $lead);
    }
    
    private function checkResponse($apiResponse, $lead)
    {
        if($apiResponse['success']) {
            LoggerFactory::getLogger()->log('Лид #' . $lead->id . ' отправлен в партнерку Lexprofit', 'Lead', $lead->id);
            return true;
        } else {
            if($apiResponse['warning'] && $apiResponse['warning']['msg']) {
                $errorMessage = $apiResponse['warning']['msg'];
            }
            
            if($apiResponse['error'] && $apiResponse['error']['msg']) {
                $errorMessage = $apiResponse['error']['msg'];
            }
            if(!$errorMessage) {
                $errorMessage = 'Неизвестная ошибка';
            }
            LoggerFactory::getLogger()->log('Ошибка при отправке лида #' . $lead->id . ' в партнерку Lexprofit: ' . $errorMessage, 'Lead', $lead->id);
            return false;
        } 
    }
}
