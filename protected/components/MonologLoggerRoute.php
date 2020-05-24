<?php

use Monolog\Logger;

class MonologLoggerRoute extends CLogRoute
{
    /** @var Logger */
    private $logger;

    public function init()
    {
        parent::init();

        $this->logger = Yii::app()->monolog->logger;
    }

    /**
     * @param array $logs
     * Каждый элемент массива - это массив вида
     * array(
     *   [0] => message (string)
     *   [1] => level (string)
     *   [2] => category (string)
     *   [3] => timestamp (float, obtained by microtime(true));
     */
    protected function processLogs($logs)
    {
        foreach ($logs as $log) {
            $this->log($log);
        }
    }

    /**
     * Записывает в лог запись формата Yii лога
     * @param array $logRecord
     * @return bool
     */
    protected function log(array $logRecord): bool
    {
        $level = $this->getLogLevel($logRecord[1]);
        $message = $logRecord[0];
        $context = $this->createContextArray($logRecord);

        return $this->logger->log($level, $message, $context);
    }

    /**
     * @param array $logRecord
     * @return array
     */
    protected function createContextArray(array $logRecord): array
    {
        $contextArray = [];

        $contextArray['category'] = $logRecord[2];

        if (preg_match('/CHttpException\.([0-9]+)/', $logRecord[2], $responseCodeMatches)) {
            $responseCode = $responseCodeMatches[1];
            $contextArray['responseCode'] = $responseCode;
        }

        return $contextArray;
    }

    /**
     * Преобразует обозначение уровня Yii  в значение Monolog
     * Например, error => 400
     * @param string $yiiLevel
     * @return int
     */
    protected function getLogLevel(string $yiiLevel): int
    {
        switch ($yiiLevel) {
            case 'info':
            case 'trace':
            case 'profile':
            default:
                return Logger::INFO;
            case 'warning':
                return Logger::WARNING;
            case 'error':
                return Logger::ERROR;
        }
    }
}