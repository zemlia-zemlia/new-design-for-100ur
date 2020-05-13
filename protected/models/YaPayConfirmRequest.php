<?php

namespace App\models;

use CFormModel;
use Yii;

/**
 * Класс для хранения и валидации данных, пришедших в запросе подтверждения оплаты от Яндекса
 * Class App\models\YaPayConfirmRequest.
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
    public $unaccepted;
    public $zip;
    public $firstname;
    public $city;
    public $building;
    public $lastname;
    public $suite;
    public $phone;
    public $street;
    public $flat;
    public $fathersname;
    public $operation_label;
    public $email;

    /*
     * Пример запроса от Яндекса:
    [notification_type] => card-incoming
    [zip] =>
    [amount] => 2.94
    [firstname] =>
    [codepro] => false
    [withdraw_amount] => 3.00
    [city] =>
    [unaccepted] => false
    [label] => q-56565
    [building] =>
    [lastname] =>
    [datetime] => 2019-02-24T13:41:10Z
    [suite] =>
    [sender] =>
    [phone] =>
    [sha1_hash] => 3de0babc4ff0bf87791c1482ec124314adec3f5f
    [street] =>
    [flat] =>
    [fathersname] =>
    [operation_label] => 2404b2a0-0011-5000-a000-14508182aca3
    [operation_id] => 604330870735091012
    [currency] => 643
    [email] =>
     */

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
     *
     * @param bool $logResult
     * @return bool
     */
    public function validateHash(string $yandexSecret, $logResult = true): bool
    {
        $hash = $this->sha1_hash;

        $requestStringEncoded = $this->createHashFromRequestParams($yandexSecret, $logResult);

        return $hash === $requestStringEncoded;
    }

    /**
     * @param string $yandexSecret
     * @param bool $logResult
     * @return string
     */
    public function createHashFromRequestParams(string $yandexSecret, bool $logResult = true): string
    {
        $requestString = $this->notification_type . '&' .
            $this->operation_id . '&' .
            $this->amount . '&' .
            $this->currency . '&' .
            $this->datetime . '&' .
            $this->sender . '&' .
            $this->codepro . '&' .
            $yandexSecret . '&' .
            $this->label;

        $requestStringEncoded = sha1($requestString);

        if ($logResult == true) {
            Yii::log('Собранная строка для проверки: ' . $requestString, 'info', 'system.web');
            Yii::log('Зашифрованная строка для проверки: ' . $requestStringEncoded, 'info', 'system.web');
        }

        return $requestStringEncoded;
    }
}
