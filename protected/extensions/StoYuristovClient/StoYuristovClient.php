<?php

/**
 * Клиент для работы с API сервиса 100 Юристов.
 *
 * @author Михаил Крутиков <m@mkrutikov.pro>
 */
class StoYuristovClient
{
    protected $_appId; // идентификатор кампании партнера
    protected $_secretKey; // секретный ключ кампании
    protected $_curlLink; // линк Curl
    protected $_signature; // подпись запроса
    protected $_apiUrlTest = 'http://100yuristov/api/sendLead/';
    protected $_apiUrl = 'https://100yuristov.com/api/sendLead/';
    protected $_testMode; // 0|1 Включение / выключение тестового режима
    // параметры лида
    public $name;
    public $phone;
    public $question;
    public $town;
    public $email;
    public $type;

    /**
     * Конструктор
     *
     * @param int    $appId
     * @param string $secretKey
     */
    public function __construct($appId, $secretKey, $testMode = 0)
    {
        $this->_appId = $appId;
        $this->_secretKey = $secretKey;
        $this->_testMode = $testMode;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, (1 == $this->_testMode) ? $this->_apiUrlTest : $this->_apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);

        $this->_curlLink = $ch;
    }

    /**
     * Вычисление сигнатуры.
     */
    protected function _calculateSignature()
    {
        $this->_signature = md5($this->name . $this->phone . $this->town . $this->question . $this->_appId . $this->_secretKey);
    }

    /**
     * Проверка данных перед отправкой в API на стороне клиента.
     *
     * @return mixed Результат проверки. true если все в
     */
    protected function _validate()
    {
        $errors = [];

        if (!$this->name) {
            $errors[] = 'Не указано имя';
        }
        if (!$this->phone) {
            $errors[] = 'Не указан номер телефона';
        }
        if (!$this->question) {
            $errors[] = 'Не указан текст вопроса';
        }
        if (!$this->town) {
            $errors[] = 'Не указан город';
        }

        if (empty($errors)) {
            return true;
        } else {
            return $errors;
        }
    }

    /**
     * Возвращает массив параметров для POST запроса.
     *
     * @return array Массив параметров
     */
    protected function _getParams()
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'town' => $this->town,
            'type' => $this->type,
            'question' => $this->question,
            'appId' => $this->_appId,
            'signature' => $this->_signature,
            'testMode' => $this->_testMode,
        ];
    }

    /**
     * Отправляет лид в api.
     *
     * @return array Массив с результатом. Элементы массива:
     *               * code - код ответа. 200 - ОК, остальные коды - ошибки
     *               * message - сообщение от api
     */
    public function send()
    {
        // проверяем данные
        if (true !== ($errors = $this->_validate())) {
            return ['message' => 'Некорректные данные', 'errors' => $errors];
        }

        // вычисляем сигнатуру
        $this->_calculateSignature();

        // Создаем запрос с POST параметрами
        curl_setopt($this->_curlLink, CURLOPT_POSTFIELDS, $this->_getParams());
        $jsonResponse = curl_exec($this->_curlLink);
        $curlInfo = curl_getinfo($this->_curlLink);
        curl_close($this->_curlLink);

        if (false !== $jsonResponse) {
            // Возвращаем ответ от API в виде ассоциативного массива (code => код_ответа, message => текст ответа)
            $response = json_decode($jsonResponse, true);

            return $response;
        } else {
            return ['message' => 'Ошибка при отправке лида на сервер'];
        }
    }
}
