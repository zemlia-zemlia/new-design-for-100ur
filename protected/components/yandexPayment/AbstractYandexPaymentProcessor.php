<?php

namespace App\components\yandexPayment;

use App\models\YandexPayment;
use App\repositories\YandexPaymentRepository;
use Monolog\Logger;

abstract class AbstractYandexPaymentProcessor implements YandexPaymentProcessorInterface
{
    /** @var Logger */
    protected $logger;

    /** @var YandexPaymentRepository */
    protected $yandexPaymentRepository;

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

    /**
     * @param YandexPaymentRepository $yandexPaymentRepository
     */
    public function setYandexPaymentRepository(YandexPaymentRepository $yandexPaymentRepository): void
    {
        $this->yandexPaymentRepository = $yandexPaymentRepository;
    }

    /**
     * @param string $operationId
     * @return bool
     * @throws \CException
     */
    protected function checkIfPaymentExists(string $operationId): bool
    {
        return $this->yandexPaymentRepository->findProcessedPayment($operationId) !== false;
    }

    /**
     * Сохраняет в БД информацию об обработанном запросе от Яндекса
     * @param string $operationId
     * @param string $label
     */
    public function saveProcessedOperation(string $operationId, string $label):void
    {
        $yandexPayment = new YandexPayment();
        $yandexPayment->operation_id = $operationId;
        $yandexPayment->label = $label;
        $yandexPayment->status = YandexPayment::STATUS_PROCESSED;
        $yandexPayment->datetime = (new \DateTime())->format('Y-m-d H:i:s');
        $yandexPayment->save();
    }
}
