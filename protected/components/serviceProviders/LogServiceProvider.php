<?php

namespace App\components\serviceProviders;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Yii;

class LogServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'paymentLogger',
    ];

    public function register()
    {
        $paymentLoggerHandlers = [
            new RotatingFileHandler(Yii::app()->getRuntimePath() . '/logs/payment/payment.txt'),
        ];
        /** @var Logger $paymentLogger */
        $paymentLogger = Yii::app()->monolog->getNewLogger('payment', $paymentLoggerHandlers);

        $this->getContainer()->add('paymentLogger', $paymentLogger);
    }
}