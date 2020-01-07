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
        if ($paymentType == YandexPaymentResponseProcessor::TYPE_USER) {
            return new YandexPaymentUser($this->entityId, $this->request);
        } elseif ($paymentType == YandexPaymentResponseProcessor::TYPE_QUESTION) {
            return new YandexPaymentQuestion($this->entityId, $this->request);
        } elseif ($paymentType == YandexPaymentResponseProcessor::TYPE_ANSWER) {
            return new YandexPaymentAnswer($this->entityId, $this->request);
        }
    }
}
