<?php

namespace App\models;

use CActiveDataProvider;
use CActiveRecord;
use CDbCriteria;
use App\models\ChatMessages;
use Yii;

/**
 * This is the model class for table "{{chat}}".
 *
 * The followings are the available columns in table '{{chat}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $lawyer_id
 * @property integer $is_payed
 * @property string $transaction_id
 * @property integer $created
 * @property integer $is_closed
 * @property integer $is_confirmed
 * @property string $chat_id
 * @property User user
 * @property User $lawyer
 * @property integer $is_petition
 */
class Chat extends CActiveRecord
{

    /**
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'chat';
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{chat}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            ['user_id, created', 'required'],
            ['user_id, lawyer_id, is_payed, created, is_closed, is_confirmed', 'numerical', 'integerOnly' => true],
            ['transaction_id, chat_id', 'length', 'max' => 255],
            ['is_petition', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, user_id, lawyer_id, is_payed, transaction_id, created, is_confirmed ,is_closed, chat_id', 'safe', 'on' => 'search'],
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => [self::BELONGS_TO, User::class, 'user_id'],
            'lawyer' => [self::BELONGS_TO, User::class, 'lawyer_id'],
            'messages' => [self::HAS_MANY, ChatMessages::class, 'chat_id'],
        );
    }

    public function getCountMessages(){
        return ChatMessages::model()->count('chat_id = ' . $this->id . ' AND is_read = 0 AND user_id != ' . \Yii::app()->user->id);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'lawyer_id' => 'Lawyer',
            'is_payed' => 'Оплачен',
            'transaction_id' => 'Transaction',
            'created' => 'Created',
            'is_closed' => 'Закрыт',
            'chat_id' => 'Chat',
            'is_confirmed' => 'Подтверждено',
            'is_petition' => 'Жалоба'
        );
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
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('lawyer_id', $this->lawyer_id);
        $criteria->compare('is_payed', $this->is_payed);
        $criteria->compare('transaction_id', $this->transaction_id, true);
        $criteria->compare('created', $this->created);
        $criteria->compare('is_closed', $this->is_closed);
        $criteria->compare('is_petition', $this->is_petition);
        $criteria->compare('is_confirmed', $this->is_confirmed);
        $criteria->compare('chat_id', $this->chat_id, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Chat the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getNotConfirmeds(){

        return $this::model()->findAll('is_confirmed IS NULL');
    }

    public function getConfirmeds(){
        return $this::model()->findAll('is_confirmed = 1');
    }

    public function getCloseds(){
        return $this::model()->findAll('is_closed = 1');
    }

    public function getPetitions(){
        return $this::model()->findAll('is_petition = 1');
    }
    public function getNotPayed(){
        return $this::model()->findAll('is_confirmed = 1 AND is_payed IS NULL');
    }



}
