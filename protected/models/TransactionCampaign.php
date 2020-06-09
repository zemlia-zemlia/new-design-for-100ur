<?php

namespace App\models;

use CActiveDataProvider;
use CActiveRecord;
use CDbCriteria;
use CException;
use Yii;

/**
 * Модель для работы с транзакциями оплаты за лидов.
 *
 * The followings are the available columns in table '{{transactionCampaign}}':
 *
 * @property int    $id
 * @property int    $buyerId
 * @property int    $campaignId
 * @property string $time
 * @property int    $sum
 * @property string $description
 * @property int    $leadId
 * @property int    $type
 * @property int    $status
 */
class TransactionCampaign extends CActiveRecord
{
    // типы объектов, за которые начислена транзакция
    const TYPE_ANSWER = 1;
    const TYPE_JURIST_MONEYOUT = 2;

    const STATUS_COMPLETE = 1; // транзакция совершена
    const STATUS_PENDING = 2; // транзакция на рассмотрении
    const STATUS_HOLD = 3; // транзакция на рассмотрении
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
        return Yii::app()->db->tablePrefix . 'transactionCampaign';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['buyerId, sum, description', 'required'],
            ['campaignId, buyerId, status', 'numerical', 'integerOnly' => true],
            ['sum', 'numerical'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, campaignId, time, status, sum, description', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations(): array
    {
        return [
            'campaign' => [self::BELONGS_TO, Campaign::class, 'campaignId'],
            'partner' => [self::BELONGS_TO, User::class, 'buyerId'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'campaignId' => 'ID кампании',
            'time' => 'Время и дата',
            'sum' => 'Сумма',
            'description' => 'Описание',
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
        $criteria->compare('campaignId', $this->campaignId);
        $criteria->compare('time', $this->time, true);
        $criteria->compare('sum', $this->sum);
        $criteria->compare('description', $this->description, true);

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
     * @return TransactionCampaign the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * После сохранения транзакции записываем ее время пользователю.
     */
    protected function afterSave()
    {
        if ($this->buyerId) {
            Yii::app()->db->createCommand()
                ->update('{{user}}', ['lastTransactionTime' => date('Y-m-d H:i:s')], 'id = ' . $this->buyerId);
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
            ->where('status = ' . self::STATUS_PENDING . ' AND sum<0')
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
            self::STATUS_HOLD => 'Заморожены',
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
     * Одобрение заявки на вывод средств юриста.
     *
     * @param int $accountId С какого счета в кассе списывать средства
     *
     * @throws CException
     */
    public function approveRequest(int $accountId): bool
    {
        if ($this->partner->balance < $this->sum) {
            return false;
        }

        $this->status = self::STATUS_COMPLETE;

        $trans = Yii::app()->db->beginTransaction();

        if ($this->save()) {
            // меняем баланс пользователя
            $userBalanceSave = Yii::app()->db->createCommand('UPDATE {{user}} SET balance = balance-' . abs($this->sum) . ' WHERE id=' . $this->buyerId)->query();

            // если одобрили вывод средств, создаем транзакцию в кассе
            $moneyTransaction = new Money();
            $moneyTransaction->type = Money::TYPE_EXPENCE;
            $moneyTransaction->direction = 9; // выплаты юристам
            $moneyTransaction->accountId = $accountId;
            $moneyTransaction->value = abs($this->sum);
            $moneyTransaction->datetime = date('Y-m-d H:i:s');
            $moneyTransaction->comment = 'Выплата юристу id ' . $this->buyerId;
            $moneyTransactionSave = $moneyTransaction->save();

            if ($userBalanceSave && $moneyTransactionSave) {
                $trans->commit();

                return true;
            }
        }
        $trans->rollback();

        return false;
    }
}
