<?php

/**
 * Класс для работы с API турбостраниц Яндекса
 * Class TurboApi.
 */
class TurboApi
{
    const HOST_ADDRESS = 'https://100yuristov.com/';
    const API_VERSION = 'v3.2';
    const API_BASE_URL = 'https://api.webmaster.yandex.net';

    private $userId;
    private $hostId;
    private $isDebug;
    private $mode;

    /**
     * @return mixed
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param mixed $mode
     *
     * @return TurboApi
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    private $token;
    private $authHeader;
    private $curlLink;
    private $uploadAddress;
    private $loadStatus;

    /**
     * @return mixed
     */
    public function getLoadStatus()
    {
        return $this->loadStatus;
    }

    /**
     * @return mixed
     */
    public function getCurlLink()
    {
        return $this->curlLink;
    }

    /**
     * @param mixed $curlLink
     *
     * @return TurboApi
     */
    public function setCurlLink($curlLink)
    {
        $this->curlLink = $curlLink;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUploadAddress()
    {
        return $this->uploadAddress;
    }

    /**
     * @param mixed $uploadAddress
     *
     * @return TurboApi
     */
    public function setUploadAddress($uploadAddress)
    {
        $this->uploadAddress = $uploadAddress;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     *
     * @return TurboApi
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHostId()
    {
        return $this->hostId;
    }

    /**
     * @param mixed $hostId
     *
     * @return TurboApi
     */
    public function setHostId($hostId)
    {
        $this->hostId = $hostId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getisDebug()
    {
        return $this->isDebug;
    }

    /**
     * @param mixed $isDebug
     *
     * @return TurboApi
     */
    public function setIsDebug($isDebug)
    {
        $this->isDebug = $isDebug;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     *
     * @return TurboApi
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthHeader()
    {
        return $this->authHeader;
    }

    /**
     * @param string $authHeader
     *
     * @return TurboApi
     */
    public function setAuthHeader($authHeader)
    {
        $this->authHeader = $authHeader;

        return $this;
    }

    /**
     * Возвращает адрес API.
     *
     * @return string
     */
    public function getApiURL()
    {
        return self::API_BASE_URL . '/' . self::API_VERSION;
    }

    public function __construct($token, $mode = 'DEBUG')
    {
        $this->token = $token;
        $this->mode = $mode;
        $this->authHeader = 'Authorization: OAuth ' . $this->token;
    }

    /**
     * Отправка запроса в API.
     *
     * @param string $method
     * @param string $route
     * @param mixed  $data
     * @param array  $headers
     *
     * @return array
     */
    private function sendRequest($method, $route, $headers = [], $data = null)
    {
        $url = $this->getApiURL() . $route;
        if ($this->getMode()) {
            $url .= '?mode=' . $this->getMode();
        }

        $ch = curl_init();
        $this->curlLink = $ch;
        curl_setopt($this->curlLink, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curlLink, CURLOPT_HEADER, false);
        curl_setopt($this->curlLink, CURLOPT_CONNECTTIMEOUT, 2);
        $requestHeaders = array_merge([$this->authHeader], $headers);
        print_r($requestHeaders);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
        curl_setopt($this->curlLink, CURLOPT_URL, $url);

        if ('POST' === $method) {
            curl_setopt($this->curlLink, CURLOPT_POST, 1);
            curl_setopt($this->curlLink, CURLOPT_POSTFIELDS, $data);
        }
        $jsonResponse = curl_exec($this->curlLink);
        $curlInfo = curl_getinfo($this->curlLink);
        curl_close($this->curlLink);

        return ['curlInfo' => $curlInfo, 'response' => $jsonResponse];
    }

    /**
     * Получение ID пользователя в вебмастере.
     *
     * @return mixed
     */
    public function requestUserId()
    {
        $responseRaw = $this->sendRequest('GET', '/user/');
        $apiResponse = $responseRaw['response'];
        $userId = json_decode($apiResponse, true)['user_id'];
        $this->userId = $userId;

        return $userId;
    }

    /**
     * Получение id хоста в вебмастере.
     *
     * @return string|null
     */
    public function requestHost()
    {
        if (!isset($this->userId)) {
            return null;
        }

        $responseRaw = $this->sendRequest('GET', '/user/' . $this->userId . '/hosts/');
        $apiResponse = $responseRaw['response'];
        $apiResponseArray = json_decode($apiResponse, true);

        foreach ($apiResponseArray['hosts'] as $host) {
            if (0 === strcmp($host['ascii_host_url'], self::HOST_ADDRESS)) {
                $this->hostId = $host['host_id'];

                return $host['host_id'];
            }
        }

        return null;
    }

    /**
     * Получение адреса для загрузки RSS.
     */
    public function requestUploadAddress()
    {
        if (!isset($this->userId) || !isset($this->hostId)) {
            return null;
        }

        $responseRaw = $this->sendRequest('GET', '/user/' . $this->userId . '/hosts/' . $this->hostId . '/turbo/uploadAddress/');
        $apiResponse = $responseRaw['response'];
        $apiResponseArray = json_decode($apiResponse, true);
        $this->uploadAddress = $apiResponseArray['upload_address'];

        return $this->uploadAddress;
    }

    /**
     *  Отправка RSS в турбо страницы.
     *
     * @param mixed $data
     *
     * @return string ID задачи
     *
     * @throws Exception
     */
    public function uploadRss($data)
    {
        if (!isset($this->uploadAddress)) {
            throw new Exception('Не задан адрес для отправки данных!');
        }

        $uploadRoute = explode(self::API_VERSION, $this->uploadAddress)[1];

        $responseRaw = $this->sendRequest('POST', $uploadRoute, ['Content-type: application/rss+xml'], $data);
        $apiResponse = $responseRaw['response'];
        $responseStatus = $responseRaw['curlInfo']['http_code'];

        print_r($responseStatus);
        print_r($apiResponse);

        if (202 == (int) $responseStatus) {
            return json_decode($apiResponse, true)['task_id'] . PHP_EOL;
        }
    }

    /**
     * Запрос информации об обработке задачи.
     *
     * @param $taskId
     *
     * @return string Статус обработки
     */
    public function getTask($taskId)
    {
        if (!isset($this->userId) || !isset($this->hostId)) {
            return null;
        }

        echo '/user/' . $this->userId . '/hosts/' . $this->hostId . '/turbo/tasks/' . $taskId . PHP_EOL;
        $responseRaw = $this->sendRequest('GET', '/user/' . $this->userId . '/hosts/' . $this->hostId . '/turbo/tasks/' . $taskId);
        $apiResponse = $responseRaw['response'];
        $apiResponseArray = json_decode($apiResponse, true);
        $this->loadStatus = $apiResponseArray['load_status'];

        print_r($responseRaw);

        return $this->loadStatus;
    }
}
