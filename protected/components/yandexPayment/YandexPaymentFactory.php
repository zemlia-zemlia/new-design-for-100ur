<?php

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
        if (YandexPaymentResponseProcessor::TYPE_USER == $paymentType) {
            return new YandexPaymentUser($this->entityId, $this->request);
        } elseif (YandexPaymentResponseProcessor::TYPE_QUESTION == $paymentType) {
            return new YandexPaymentQuestion($this->entityId, $this->request);
        } elseif (YandexPaymentResponseProcessor::TYPE_ANSWER == $paymentType) {
            return new YandexPaymentAnswer($this->entityId, $this->request);
        }
    }
}
