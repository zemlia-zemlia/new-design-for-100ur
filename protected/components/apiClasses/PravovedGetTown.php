<?php

/**
 * Класс получения id города в базе Правоведа по названию.
 */
class PravovedGetTown
{
    const API_URL = 'https://pravoved.ru/rest/cities/';

    private static $instance;

    private function __construct()
    {
    }

    /**
     * Получение объекта-синглтона.
     *
     * @return type
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * Получение id города по имени.
     *
     * @param type $townName Название города
     *
     * @return int id города в базе Правоведа
     */
    public function getTownId($townName)
    {
        $apiPath = self::API_URL . '?prefix=' . urldecode($townName);
        $townsResponse = file_get_contents($apiPath); // ответ приходит в формате JSON

        if ('' == $townsResponse) {
            return 0;
        }

        $townsResponseDecoded = json_decode($townsResponse, true);

        if ($townsResponseDecoded['meta'] && $townsResponseDecoded['meta']['code'] && 200 == (int) $townsResponseDecoded['meta']['code']) {
            $firstTown = $townsResponseDecoded['data']['cities'][0];
            $firstTownId = $firstTown['id'];

            return (int) $firstTownId;
        }

        return 0;
    }
}
