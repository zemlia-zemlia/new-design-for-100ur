<?php

/**
 * Класс для хранения и валидации данных, пришедших в запросе подтверждения оплаты от Яндекса
 * Class YaPayConfirmRequest
 */
class YaPayConfirmRequest extends CFormModel
{
    // поля с данными от Яндекс денег
    public $sha1_hash;
    public $notification_type;
    public $operation_id;
    public $amount;
    public $withdraw_amount;
    public $currency;
    public $datetime;
    public $sender;
    public $codepro;
    public $label;

    public function rules()
    {
        return [
            // username and password are required
            ['email, password', 'required', 'message' => 'Поле {attribute} не может быть пустым'],
            // rememberMe needs to be a boolean
            ['codepro, unaccepted', 'boolean'],
            ['notification_type, operation_id, currency, datetime, sender, label, sha1_hash', 'length', 'max' => 255],
            ['amount, withdraw_amount', 'numerical'],
        ];
    }

    /**
     * @param string $yandexSecret
     * @return bool
     */
    public function validateHash(string $yandexSecret): bool
    {
        $hash = $this->sha1_hash;

        $requestString = $this->notification_type . '&' .
            $this->operation_id . '&' .
            $this->amount . '&' .
            $this->currency . '&' .
            $this->datetime . '&' .
            $this->sender . '&' .
            $this->codepro . '&' .
            $yandexSecret . '&' .
            $this->label;

        Yii::log('Собранная строка для проверки: ' . $requestString, 'info', 'system.web');

        $requestStringEncoded = sha1($requestString);

        Yii::log('Зашифрованная строка для проверки: ' . $requestStringEncoded, 'info', 'system.web');

        return $hash === $requestStringEncoded;
    }
}
