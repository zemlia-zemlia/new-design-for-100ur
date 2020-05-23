<?php

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

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