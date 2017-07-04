<?php

namespace stoyuristov;

/**
 * Клиент для работы с API сервиса 100 Юристов
 * @author Михаил Крутиков <m@mkrutikov.pro>
 */
class StoYuristovClient {

    protected $_appId; // идентификатор кампании партнера
    protected $_secretKey; // секретный ключ кампании
    protected $_curlLink; // линк Curl
    protected $_signature; // подпись запроса
    protected $_apiUrlTest = 'http://100juristov/api/sendLead';
    protected $_apiUrl = 'https://100yuristov.com/question/sendLead/';
    
    // параметры лида
    public $name;
    public $phone;
    public $question;
    public $town;
    public $email;
    public $testMode = 0; // 0|1 Включение / выключение тестового режима

    /**
     * Конструктор
     * 
     * @param integer $appId
     * @param string $secretKey
     */
    public function __construct($appId, $secretKey) {
        $this->_appId = $appId;
        $this->_secretKey = $secretKey;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_apiUrlTest);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);

        $this->_curlLink = $ch;
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $lead);
    }

    /**
     * Вычисление сигнатуры
     */
    protected function _calculateSignature() {
        $this->_signature = md5($this->name . $this->phone . $this->town . $this->question . $this->_appId . $this->_secretKey);
    }

    /**
     * Проверка данных перед отправкой в API на стороне клиента
     * @return mixed Результат проверки. true если все в 
     */
    protected function _validate() {
        $errors = array();
        
        if(!$this->name) {
            $errors[] = "Не указано имя";
        }
        if(!$this->phone) {
            $errors[] = "Не указан номер телефона";
        }
        if(!$this->question) {
            $errors[] = "Не указан текст вопроса";
        }
        if(!$this->town) {
            $errors[] = "Не указан город";
        }
        
        if(empty($errors)) {
            return true;
        } else {
            return $errors;
        }
    }
    
    /**
     * Возвращает массив параметров для POST запроса
     * 
     * @return array Массив параметров
     */
    protected function _getParams()
    {
        return array(
            'name'          => $this->name,
            'phone'         => $this->phone,
            'email'         => $this->email,
            'town'          => $this->town,
            'question'      => $this->question,
            'appId'         => $this->_appId,
            'signature'     => $this->_signature,
        );
    }

    
    /**
     * Отправляет лид в api
     * 
     * @return array Массив с результатом. Элементы массива:
     * * code - код ответа. 200 - ОК, остальные коды - ошибки
     * * message - сообщение от api
     */
    public function send() {
        // проверяем данные
        if (($errors = $this->_validate()) !== true) {
            return array("message" => "Некорректные данные", 'errors' => $errors);
        }
        
        // Создаем запрос с POST параметрами
        curl_setopt($this->_curlLink, CURLOPT_POSTFIELDS, $this->_getParams());
        $jsonResponse = curl_exec($this->_curlLink);
        $curlInfo = curl_getinfo($this->_curlLink);
        curl_close($this->_curlLink);

        if ($jsonResponse !== false && $curlInfo['http_code'] == 200) {
//          CustomFuncs::printr($curlInfo);
//          echo 'Ответ сервера';

            $response = json_decode($jsonResponse, true);
            //echo '<textarea style="width:500px;" rows="10">' . $jsonResponse . '</textarea>';
            //CustomFuncs::printr($jsonResponse);
//          CustomFuncs::printr($response);
//          exit;

            return $response;
            
        } else {
            return array("message" => "Ошибка при отправке лида на сервер");
        }
        
    }

}
