<?php

/*
 * Класс для работы с Яндекс кассой
 */

class YandexKassa
{
    // параметры, полученные POST запросом от Яндекс кассы
    public $params;

    const PAYMENT_LOG_FILE = '/protected/runtime/payment_log.txt';

    public function __construct($params)
    {
        $this->params = $params;
    }

    // проверяет контрольную сумму запроса, поступившего от кассы
    public static function checkMd5($params)
    {
        // action;orderSumAmount;orderSumCurrencyPaycash;orderSumBankPaycash;shopId;invoiceId;customerNumber;shopPassword

        // md5, пришедшая от Яндекса
        $sourceMd5 = $params['md5'];

        // указатель на файл для записи лога
        $paymentLog = fopen($_SERVER['DOCUMENT_ROOT'] . self::PAYMENT_LOG_FILE, 'a+');

        $shopPassword = Yii::app()->params['yandexShopPassword'];

        $md5 = strtoupper(md5($params['action'] . ';' . $params['orderSumAmount'] . ';' . $params['orderSumCurrencyPaycash'] . ';' . $params['orderSumBankPaycash'] . ';' . $params['shopId'] . ';' . $params['invoiceId'] . ';' . $params['customerNumber'] . ';' . $shopPassword));

        if ($sourceMd5 == $md5) {
            return true;
        } else {
            return false;
        }
    }

    // формирует и отправляет ответ для Яндекс кассы в ответ на запрос проверки платежа
    public function formResponse($code = 0, $message = '', $responseType = 'checkOrder')
    {
        $performedDatetime = date('c');
        $response = '<?xml version="1.0" encoding="UTF-8"?><' . $responseType . 'Response performedDatetime="' . $performedDatetime .
            '" code="' . $code . '" ' . ('' != $message ? 'message="' . $message . '"' : '') . ' invoiceId="' . $this->params['invoiceId'] . '" shopId="' . $this->params['shopId'] . '"/>';

        echo $response;
    }

    // отмечает вопрос как оплаченный и записывает его цену
    public function payQuestion()
    {
        $question = Question::model()->findByPk($this->params['customerNumber']);
        $question->setScenario('pay');
        $rate = $this->params['orderSumAmount'];
        $rateWithoutComission = $this->params['shopSumAmount'];

        //Yii::log('question id = ' . $question . ', rate = ' . $rate, 'info', 'system.web.CController');
        $paymentLog = fopen($_SERVER['DOCUMENT_ROOT'] . YandexKassa::PAYMENT_LOG_FILE, 'a+');
        fwrite($paymentLog, 'question id = ' . $question->id . ', rate = ' . $rate);

        if (!$question) {
            return false;
        }

        $question->payed = 1;
        $question->price = (int) $rate;

        // отправка уведомления и запись транзакции в кассу
        $question->vipNotification($rateWithoutComission);

        if ($question->save()) {
            return true;
        }
        fwrite($paymentLog, 'question not saved ' . json_encode($question->errors));

        return false;
    }
}
