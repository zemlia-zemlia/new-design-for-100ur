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

    /** @var string Обозначение сущности, которую оплачивают */
    private $paymentType;

    /** @var int ID оплачиваемой сущности */
    private $entityId;

    /** @var string Кодовая строка для проверки хеша запроса */
    private $yandexSecret;

    /** @var string[] массив с ошибками */
    private $errors = [];

    public function __construct(YaPayConfirmRequest $request, string $yandexSecret)
    {
        $this->request = $request;
        $this->yandexSecret = $yandexSecret;
    }

    /**
     * Обработка запроса
     * @return bool
     */
    public function process():bool
    {
        // разбираем данные, которые пришли от Яндекса
        $label = $this->request->label;

        if (is_null($this->detectPaymentType($label))) {
            $this->addError('Некоректный тип плачиваемой сущности');
            return false;
        };

        if ($this->request->validateHash($this->yandexSecret) !== true) {
            $this->addError('Запрос не прошел проверку на целостность');
            return false;
        }

        // данные от яндекса не подделаны, можно зачислять бабло

        if ($this->paymentType == 'user') {
            $paymentProcessor = new YandexPaymentUser($this->entityId, $this->request);
        } elseif ($this->paymentType == 'question') {
            $paymentProcessor = new YandexPaymentQuestion($this->entityId, $this->request);
        }
        try {
            return $paymentProcessor->process();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Получает тип сущности, за которую платят и ее id
     * @param string $label
     * @return string|null
     */
    private function detectPaymentType($label): ?string
    {
        preg_match('/([a-z]{1})\-([0-9]+)/', $label, $labelMatches);

        if (!$labelMatches[1]) {
            return null;
        }

        $this->entityId = (int)$labelMatches[2];

        if ($this->entityId == 0) {
            return null;
        }

        switch ($labelMatches[1]) {
            case 'u':
                $this->paymentType = 'user';
                break;
            case 'q':
                $this->paymentType = 'question';
                break;
            default:
                return null;
        }

        Yii::log('Субъект оплаты: ' . $this->paymentType, 'info', 'system.web');

        return $this->paymentType;
    }

    /**
     * Добавление ошибки
     * @param string $error
     */
    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
