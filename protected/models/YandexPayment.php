<?php


namespace App\models;


use Yii;

class YandexPayment extends \CActiveRecord
{
    const STATUS_PROCESSED = 1;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{yandex_payment}}';
    }

    /**
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'yandex_payment';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        return [
            ['label, operation_id, datetime', 'required'],
            ['label, operation_id', 'length', 'max' => 255],
        ];
    }
}
