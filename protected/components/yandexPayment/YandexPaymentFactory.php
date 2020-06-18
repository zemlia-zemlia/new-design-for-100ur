<?php

namespace App\components\yandexPayment;

use App\models\YaPayConfirmRequest;
use YandexPaymentResponseProcessor;

class YandexPaymentFactory
{
    private $entityId;
    private $request;

    public function __construct(int $entityId, YaPayConfirmRequest $request)
    {
        $this->entityId = $entityId;
        $this->request = $request;
    }

    public function createPaymentClass($paymentType): YandexPaymentProcessorInterface
    {
        switch ($paymentType) {
            case YandexPaymentResponseProcessor::TYPE_USER:
                return new YandexPaymentProcessorUser($this->entityId, $this->request);
            case YandexPaymentResponseProcessor::TYPE_QUESTION:
                return new YandexPaymentProcessorQuestion($this->entityId, $this->request);
            case YandexPaymentResponseProcessor::TYPE_ANSWER:
                return new YandexPaymentProcessorAnswer($this->entityId, $this->request);
            case YandexPaymentResponseProcessor::TYPE_CHAT:
                return new YandexPaymentProcessorChat($this->entityId, $this->request);
        }
    }
}
