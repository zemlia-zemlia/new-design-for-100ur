<?php

namespace App\models;

use CActiveDataProvider;
use CActiveRecord;
use CDbCriteria;

/**
 * This is the model class for table "{{chat}}".
 *
 * The followings are the available columns in table '{{chat}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $layer_id
 * @property integer $is_payed
 * @property string $transaction_id
 * @property integer $created
 * @property integer $is_closed
 * @property string $chat_id
 * @property User user
 * @property User $layer
 */
class Chat extends CActiveRecord
{
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
            array('user_id, created', 'required'),
            array('user_id, layer_id, is_payed, created, is_closed', 'numerical', 'integerOnly' => true),
            array('transaction_id, chat_id', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, layer_id, is_payed, transaction_id, created, is_closed, chat_id', 'safe', 'on' => 'search'),
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
            'layer' => [self::BELONGS_TO, User::class, 'layer_id'],
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'layer_id' => 'Layer',
            'is_payed' => 'Is Payed',
            'transaction_id' => 'Transaction',
            'created' => 'Created',
            'is_closed' => 'Is Closed',
            'chat_id' => 'Chat',
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
        $criteria->compare('layer_id', $this->layer_id);
        $criteria->compare('is_payed', $this->is_payed);
        $criteria->compare('transaction_id', $this->transaction_id, true);
        $criteria->compare('created', $this->created);
        $criteria->compare('is_closed', $this->is_closed);
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
}
