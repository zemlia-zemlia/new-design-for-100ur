<?php

/**
 * Класс для обработки запросов от Яндекс денег об успешной оплате
 *
 * Class YandexPaymentResponseProcessor
 */
class YandexPaymentResponseProcessor
{
    /** @var CHttpRequest */
    private $request;

    public function __construct(CHttpRequest $request)
    {
        $this->request = $request;
    }
}
