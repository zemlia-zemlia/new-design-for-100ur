<?php

namespace App\components\apiClasses;

use ApiClassInterface;
use App\models\Lead;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Yii;

/**
 * Класс-заглушка, чтобы не отправлять данные из среды разработки в реальные партнерки
 * данные будут отправляться в лог-файл
 * Class ApiTestHandler.
 */
class ApiTestHandler implements ApiClassInterface
{
    /** @var Logger */
    protected $logger;

    public function __construct()
    {
        $this->logger = new Logger('api');
        $rotateHandler = new RotatingFileHandler(
            Yii::getPathOfAlias('application.runtime.partner_program') . '/test.log',
            30,
            Logger::INFO
        );
        $this->logger->pushHandler($rotateHandler);
    }

    /**
     * @return bool
     */
    public function send(Lead $lead)
    {
        $this->logger->addInfo('Отправка лида в заглушку партнерки', $lead->attributes);

        return true;
    }
}
