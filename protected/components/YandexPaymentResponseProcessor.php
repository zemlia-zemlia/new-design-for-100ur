<?php

use App\models\YaPayConfirmRequest;

/**
 * Класс для обработки запросов от Яндекс денег об успешной оплате.
 *
 * Class YandexPaymentResponseProcessor
 */
class YandexPaymentResponseProcessor
{
    const TYPE_USER = 'user';
    const TYPE_QUESTION = 'question';
    const TYPE_ANSWER = 'answer';
    const TYPE_CHAT = 'chat';

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
     * Обработка запроса.
     *
     * @return bool
     */
    public function process(): bool
    {
        // разбираем данные, которые пришли от Яндекса
        $label = $this->request->label;

        if (is_null($this->detectPaymentType($label))) {
            $this->addError('Некоректный тип плачиваемой сущности');

            return false;
        }

        if (true !== $this->request->validateHash($this->yandexSecret)) {
            $this->addError('Запрос не прошел проверку на целостность');

            return false;
        }

        // данные от яндекса не подделаны, можно зачислять бабло
        $paymentProcessorFactory = new YandexPaymentFactory($this->entityId, $this->request);
        $paymentProcessor = $paymentProcessorFactory->createPaymentClass($this->paymentType);

        try {
            return $paymentProcessor->process();
        } catch (\Exception $e) {
            Yii::log('Ошибка при обработке платежа: ' . $e->getMessage(), 'error', 'system.web');
            return false;
        }
    }

    /**
     * Получает тип сущности, за которую платят и ее id.
     *
     * @param string $label
     *
     * @return string|null
     */
    private function detectPaymentType($label): ?string
    {
        preg_match('/([a-z]{1})\-([0-9]+)/', $label, $labelMatches);

        if (!$labelMatches[1]) {
            return null;
        }

        $this->entityId = (int) $labelMatches[2];

        if (0 == $this->entityId) {
            return null;
        }

        switch ($labelMatches[1]) {
            case 'u':
                $this->paymentType = self::TYPE_USER;
                break;
            case 'q':
                $this->paymentType = self::TYPE_QUESTION;
                break;
            case 'a':
                $this->paymentType = self::TYPE_ANSWER;
                break;
            case 'c':
                $this->paymentType = self::TYPE_CHAT;
                break;
            default:
                return null;
        }

        Yii::log('Субъект оплаты: ' . $this->paymentType, 'info', 'system.web');

        return $this->paymentType;
    }

    /**
     * Добавление ошибки.
     *
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
