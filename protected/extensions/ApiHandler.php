<?php

/**
 * Класс для работы с API: обработка запросов и логирование.
 */
class ApiHandler {

    const LOG_FILE_NAME = 'api_log.txt';
    const LOG_TABLE = '{{apiLog}}';
    const MODE_FILE = 1; // писать лог в файл
    const MODE_DB = 2; // писать лог в базу

    protected $_request; // объект CHttpRequest
    protected $_requestStart; // время начала обработки запроса
    protected $_response; // массив с ответом

    /**
     * При создании объекта сохраняем время, чтобы в конце обработки сохранить время исполнения
     */
    public function __construct() {
        $this->_requestStart = microtime(true);
        $this->_request = Yii::app()->request;
    }

    /**
     * Возвращает разницу между временем инициализации объекта и текущим временем (продолжительность обработки запроса)
     * @return float Description Разница между временем инициализации объекта и текущим временем
     */
    public function getDuration() {
        if(!$this->_requestStart) {
            return 0;
        }
        
        return microtime(true) - $this->_requestStart;
    }

    /**
     * Выводит массив с ответом в формате JSON
     * @param array $response Массив с параметрами ответа
     */
    public function respond($response) {
        $this->_response = $response;
        $response['duration'] = $this->getDuration();
        echo json_encode($response);
        $this->log(self::MODE_DB);
        exit;
    }

    /**
     * Запись результата запроса в лог
     * @param int $mode Писать в файл или в базу
     */
    public function log($mode = self::MODE_FILE) {
        // определяем IP адрес клиента
        $clientIp = $this->_request->getUserHostAddress();
        
        // записываем данные в лог в зависимости от заданного способа
        
        switch ($mode){
            case self::MODE_DB:
                Yii::app()->db->createCommand()
                    ->insert(self::LOG_TABLE, array(
                        'ip'         => $clientIp,
                        'duration'   => $this->getDuration(),
                        'response'   => json_encode($this->_response),
                        'responseCode'   => $this->_response['code'],
                        'route'      => $this->_request->getPathInfo(),
                        'requestData' => CHtml::encode($this->_request->getRawBody()),
                    ));
                break;
            case self::MODE_FILE: default:
                
                break;
        }
    }

}

?>