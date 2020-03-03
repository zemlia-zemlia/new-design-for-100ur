<?php

/**
 * Класс для работы с транзакциями за лиды, купленные у вебмастеров
 * а также бонусы за пользователей в партнерской программе.
 *
 * The followings are the available columns in table '{{partnerTransaction}}':
 *
 * @property int    $id
 * @property int    $partnerId
 * @property int    $sourceId
 * @property string $sum
 * @property string $datetime
 * @property int    $leadId
 * @property int    $questionId
 * @property int    $userId
 * @property string $comment
 * @property int    $status
 */
class PartnerTransaction extends CActiveRecord
{
    const STATUS_COMPLETE = 1; // транзакция совершена
    const STATUS_PENDING = 2; // транзакция на рассмотрении
    const MIN_WITHDRAW = 100000; // минимальная сумма для вывода (в копейках)
    const MIN_WITHDRAW_REFERAL = 50000; // минимальная сумма для вывода по реферальной программе (в копейках)

    public $date1;
    public $date2; // используются при фильтрации

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{partnerTransaction}}';
    }

    /**
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'partnerTransaction';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['partnerId, sum', 'required', 'message' => 'Поле {attribute} не заполнено'],
            ['partnerId, sourceId, leadId, questionId, userId', 'numerical', 'integerOnly' => true],
            ['sum', 'length', 'max' => 10, 'message' => 'Сумма слишком большая'],
            ['comment', 'required', 'on' => 'pull', 'message' => 'Заполните поле с комментарием'],
            ['comment', 'length', 'max' => 255],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['partnerId, sourceId, sum, datetime, leadId, comment', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'partner' => [self::BELONGS_TO, 'User', 'partnerId'],
            'source' => [self::BELONGS_TO, 'Leadsource', 'sourceId'],
            'lead' => [self::BELONGS_TO, 'Lead', 'leadId'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'partnerId' => 'id вебмастера',
            'sourceId' => 'id источника',
            'sum' => 'Сумма',
            'datetime' => 'Дата и время',
            'leadId' => 'id лида',
            'questionId' => 'id вопроса',
            'userId' => 'id пользователя',
            'comment' => 'Комментарий',
            'type' => 'Тип',
        ];
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

    /**
     * Возвращает сумму всех транзакций вебмастеров.
     */
    public static function sumAll()
    {
        $sumRow = Yii::app()->db->createCommand()
                ->select('SUM(`sum`) totalSum')
                ->from('{{partnerTransaction}}')
                ->where('status=:status', [':status' => self::STATUS_COMPLETE])
                ->queryRow();

        return $sumRow['totalSum'];
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
        $criteria->compare('partnerId', $this->partnerId);
        $criteria->compare('sourceId', $this->sourceId);
        $criteria->compare('sum', $this->sum, true);
        $criteria->compare('datetime', $this->datetime, true);
        $criteria->compare('leadId', $this->leadId);
        $criteria->compare('comment', $this->comment, true);

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
     * @return PartnerTransaction the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Возвращает число заявок, находящихся в статусе На рассмотрении.
     */
    public static function getNewRequestsCount()
    {
        $counterRow = Yii::app()->db->cache(600)->createCommand()
                ->select('COUNT(*) counter')
                ->from('{{partnerTransaction}}')
                ->where('status = ' . self::STATUS_PENDING . ' AND sum<0')
                ->queryRow();

        return $counterRow['counter'];
    }
}
