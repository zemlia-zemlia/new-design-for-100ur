<?php

namespace App\components\yandexPayment;

use Monolog\Logger;

abstract class AbstractYandexPayment implements YandexPaymentProcessorInterface
{
    /** @var Logger */
    protected $logger;

    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param int $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        if ($this->logger instanceof Logger) {
            $this->logger->log($level, $message, $context);
        }
    }
}
