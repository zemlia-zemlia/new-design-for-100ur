<?php

/**
 * Модель для работы с транзакциями оплаты за лидов
 *
 * The followings are the available columns in table '{{transactionCampaign}}':
 * @property integer $id
 * @property integer $buyerId
 * @property integer $campaignId
 * @property string $time
 * @property integer $sum
 * @property string $description
 * @property integer $leadId
 */
class TransactionCampaign extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{transactionCampaign}}';
    }

    /**
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'transactionCampaign';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                array('buyerId, sum, description', 'required'),
                array('campaignId, buyerId', 'numerical', 'integerOnly'=>true),
                array('sum', 'numerical'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, campaignId, time, sum, description', 'safe', 'on'=>'search'),
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
            'campaign'     =>  array(self::BELONGS_TO, 'Campaign', 'campaignId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
                'id'            => 'ID',
                'campaignId'    => 'ID кампании',
                'time'          => 'Время и дата',
                'sum'           => 'Сумма',
                'description'   => 'Описание',
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

        $criteria=new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('campaignId', $this->campaignId);
        $criteria->compare('time', $this->time, true);
        $criteria->compare('sum', $this->sum);
        $criteria->compare('description', $this->description, true);

        return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TransactionCampaign the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * После сохранения транзакции записываем ее время пользователю
     */
    protected function afterSave()
    {
        if ($this->buyerId) {
            Yii::app()->db->createCommand()
                ->update("{{user}}", array('lastTransactionTime' => date("Y-m-d H:i:s")), 'id = ' . $this->buyerId);
        }
        parent::afterSave();
    }
}
