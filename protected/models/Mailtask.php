<?php

namespace App\models;

use CActiveDataProvider;
use CActiveRecord;
use CDbCriteria;
use Yii;

/**
 * This is the model class for table "{{mailtask}}".
 *
 * The followings are the available columns in table '{{mailtask}}':
 *
 * @property int $id
 * @property string $startDate
 * @property int $status
 * @property int $mailId
 * @property string $email
 * @property int $userId
 */
class Mailtask extends CActiveRecord
{
    const STATUS_NOT_SENT = 0; // не отправлено
    const STATUS_SENT = 1; // отправлено

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{mailtask}}';
    }

    /**
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'mailtask';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['email', 'required'],
            ['status, mailId, userId', 'numerical', 'integerOnly' => true],
            ['email', 'length', 'max' => 255],
            ['startDate', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, startDate, status, mailId, email, userId', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'startDate' => 'Start Date',
            'status' => 'Status',
            'mailId' => 'Mail',
            'email' => 'Email',
            'userId' => 'User',
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     *                             based on the search/filter conditions
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('startDate', $this->startDate, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('mailId', $this->mailId);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('userId', $this->userId);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className active record class name
     *
     * @return Mailtask the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
