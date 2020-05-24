<?php

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\ElasticSearchHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Elastica\Client;

/**
 * Обертка над Monolog
 * При инициализации приложения создает объект-синглтон Monolog
 * Class MonologComponent
 */
class MonologComponent extends CApplicationComponent
{
    /** @var Logger */
    public $logger;

    /** @var int Если установить в 0, никуда логировать не будем */
    public $isEnabled = 1;

    /** @var int логируем ли в Elastic */
    public $isElasticEnabled = 0;

    /**
     * При инициализации приложения создаем объект логирования по умолчанию
     */
    public function init()
    {
        parent::init();

        $this->logger = new Logger('app');

        if ($this->isEnabled == 1) {
            $this->logger->pushHandler(new RotatingFileHandler(
                Yii::getPathOfAlias('application.runtime.monolog') . '/app.log',
                30,
                Logger::DEBUG
            ));
        }

        /** @todo Создавать объекты через контейнер */
        if ($this->isElasticEnabled == 1) {
            $client = new Client([
                'host' => '127.0.0.1',
            ]);
            $options = [
                'index' => 'logs100yuristov_' . date('Y-m-d'),
                'type' => 'elastic_doc_type',
            ];
            $elasticSearchHandler = new ElasticsearchHandler($client, $options);
            $this->logger->pushHandler($elasticSearchHandler);
        }
    }

    /**
     * Создает и возвращает объект-логгер
     * @param string $loggerName
     * @param AbstractProcessingHandler[] $logHandlers
     * @return Logger
     */
    public function getNewLogger(string $loggerName, array $logHandlers): Logger
    {
        $logger = new Logger($loggerName);

        foreach ($logHandlers as $handler) {
            $logger->pushHandler($handler);
        }

        return $logger;
    }
}