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
 * @property integer $type
 * @property integer $status
 */
class TransactionCampaign extends CActiveRecord
{
    // типы объектов, за которые начислена транзакция
    const TYPE_ANSWER = 1;
    const TYPE_JURISN_MONEYOUT = 9;

    const STATUS_COMPLETE = 1; // транзакция совершена
    const STATUS_PENDING = 2; // транзакция на рассмотрении
    const MIN_WITHDRAW = 30000; // минимальная сумма для вывода (в копейках)



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
        return Yii::app()->db->tablePrefix . 'transactioncampaign';
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
                array('campaignId, buyerId, status', 'numerical', 'integerOnly'=>true),
                array('sum', 'numerical'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, campaignId, time, status, sum, description', 'safe', 'on'=>'search'),
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
            'partner' => [self::BELONGS_TO, 'User', 'buyerId'],

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


    /**
     * Возвращает массив типов транзакций.
     *
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::TYPE_ANSWER => 'Ответ',
        ];
    }

    /**
     * Возвращает название типа транзакции.
     */
    public function getType()
    {
        $allTypes = self::getTypes();

        return $allTypes[$this->type];
    }

    /**
     * Возвращает число заявок, находящихся в статусе На рассмотрении.
     */
    public static function getNewRequestsCount()
    {
        $counterRow = Yii::app()->db->cache(600)->createCommand()
            ->select('COUNT(*) counter')
            ->from('{{transactionCampaign}}')
            ->where('status = '.self::STATUS_PENDING.' AND sum<0')
            ->queryRow();

        return $counterRow['counter'];
    }
    /**
     * Возвращает массив статусов транзакций.
     *
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_COMPLETE => 'Совершена',
            self::STATUS_PENDING => 'На проверке',
        ];
    }

    /**
     * Возвращает название статуса транзакции.
     */
    public function getStatus()
    {
        $allStatuses = self::getStatuses();

        return $allStatuses[$this->status];
    }

}
