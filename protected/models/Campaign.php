<?php

/**
 * Класс для работы с кампаниями покупателей лидов
 *
 * Доступные поля в таблице '{{campaign}}':
 * @property integer $id
 * @property integer $regionId
 * @property integer $timeFrom
 * @property integer $timeTo
 * @property integer $price
 * @property integer $leadsDayLimit
 * @property integer $realLimit
 * @property integer $brakPercent
 * @property integer $buyerId
 * @property integer $active
 * @property integer $sendEmail
 * @property string $lastTransactionTime
 * @property string $days
 * @property integer $sendToApi
 * @property string $apiClass
 * @property integer $type
 * 
 * @author Michael Krutikov m@mkrutikov.pro
 */
class Campaign extends CActiveRecord
{

    // Статусы активности кампании
    const ACTIVE_NO = 0;
    const ACTIVE_YES = 1;
    const ACTIVE_MODERATION = 2;
    const ACTIVE_ARCHIVE = 3;
    const TYPE_BUYERS = 0; // кампании покупателей (за лиды списываем деньги с баланса)
    const TYPE_PARTNERS = 1; // кампании партнерских программ (отправляем лиды, с баланса деньги не списываем)

    public $workDays;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{campaign}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('leadsDayLimit, brakPercent, buyerId, active', 'required', 'message' => 'Поле {attribute} должно быть заполнено'),
            array('regionId, townId, price, sendEmail, leadsDayLimit, realLimit, brakPercent, buyerId, active, timeFrom, timeTo, sendToApi, type', 'numerical', 'integerOnly' => true),
            array('price', 'validatePrice'),
            array('apiClass', 'validateApiClass'),
            array('days, apiClass', 'length', 'max' => 255),
            array('leadsDayLimit', 'compare', 'compareValue' => 10, 'operator' => '>=', 'message' => 'Лимит лидов должен быть не меньше 10'),
            array('workDays', 'type', 'type' => 'array'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, regionId, price, '
                . 'leadsDayLimit, brakPercent, buyerId, active', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Валидатор поля цена лида
     * @param type $attribute
     * @param type $params
     */
    public function validatePrice($attribute, $params)
    {
        if ($this->type == self::TYPE_BUYERS && $this->$attribute == 0) {
            $this->addError($attribute, 'Цена продажи в кампании покупателей должна быть больше нуля');
        }
    }

    /**
     * Валидатор поля Класс API
     * @param type $attribute
     * @param type $params
     */
    public function validateApiClass($attribute, $params)
    {
        if ($this->$attribute != '' && !@class_exists($this->$attribute)) {
            $this->addError($attribute, 'Класс для работы с API не найден');
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'buyer' => array(self::BELONGS_TO, 'User', 'buyerId'),
            'region' => array(self::BELONGS_TO, 'Region', 'regionId'),
            'town' => array(self::BELONGS_TO, 'Town', 'townId'),
            'leads' => array(self::HAS_MANY, 'Lead', 'campaignId'),
            'leadsToday' => array(self::HAS_MANY, 'Lead', 'campaignId',
                'condition' => 'DATE(leadsToday.deliveryTime)="' . date('Y-m-d') . '"',
            ),
            'leadsCount' => array(self::STAT, 'Lead', 'campaignId'),
            'leadsTodayCount' => array(self::STAT, 'Lead', 'campaignId',
                'condition' => 'DATE(t.deliveryTime)="' . date('Y-m-d') .
                '" AND leadStatus IN(' . Lead::LEAD_STATUS_SENT . ', ' .
                Lead::LEAD_STATUS_NABRAK . ', ' . Lead::LEAD_STATUS_RETURN . ')',
            ),
            'transactions' => array(self::HAS_MANY, 'TransactionCampaign', 'campaignId', 'order' => 'transactions.id DESC'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'regionId' => 'ID региона',
            'townId' => 'ID города',
            'region' => 'Регион',
            'town' => 'Город',
            'timeFrom' => 'Время работы от',
            'timeTo' => 'Время работы до',
            'price' => 'Цена лида',
            'balance' => 'Баланс',
            'leadsDayLimit' => 'Дневной лимит лидов',
            'realLimit' => 'Реальный лимит лидов',
            'brakPercent' => 'Процент брака',
            'buyerId' => 'ID покупателя',
            'active' => 'Активность',
            'sendEmail' => 'Отправлять лиды на Email',
            'lastTransactionTime' => 'Время последней транзакции',
            'days' => 'Рабочие дни',
            'workDays' => 'Рабочие дни',
            'sendToApi' => 'Отправлять через API',
            'apiClass' => 'Класс для работы с API',
            'type' => 'Тип',
        );
    }

    /**
     * Возвращает массив типов кампаний
     * @return type
     */
    public static function getTypes()
    {
        return [
            self::TYPE_BUYERS => 'Кампании покупателей',
            self::TYPE_PARTNERS => 'Кампании партнерских программ',
        ];
    }

    /**
     * Возвращает название типа кампании
     * @return type
     */
    public function getTypeName()
    {
        return self::getTypes()[$this->type];
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
        $criteria->compare('regionId', $this->regionId);
        $criteria->compare('timeFrom', $this->timeFrom);
        $criteria->compare('timeTo', $this->timeTo);
        $criteria->compare('price', $this->price);
        $criteria->compare('leadsDayLimit', $this->leadsDayLimit);
        $criteria->compare('brakPercent', $this->brakPercent);
        $criteria->compare('buyerId', $this->buyerId);
        $criteria->compare('active', $this->active);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function getActivityStatuses()
    {
        return array(
            self::ACTIVE_NO => 'Неактивна',
            self::ACTIVE_YES => 'Активна',
            self::ACTIVE_MODERATION => 'На проверке',
            self::ACTIVE_ARCHIVE => 'В архиве',
        );
    }

    public function getActiveStatusName()
    {
        $statuses = self::getActivityStatuses();
        return $statuses[$this->active];
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Campaign the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * находит список кампаний, подходящих для отправки заданного лида
     * 
     * @param int $leadId ID лида
     * @return int ID кампании для отправки лида 
     */
    public static function getCampaignsForLead($leadId, $returnArray = false)
    {
        // ограничим число кампаний, которые ищем
        $limit = 10;

        $lead = Lead::model()->findByPk($leadId);

        if (!$lead) {
            return false;
        }
        if (!$lead->town) {
            return false;
        }

        $campaigns = array();

        /**
         * Выбираем из базы активные кампании, настроенные на данный регион, город и время работы (время NOW())
         * сортировка по цене. Учитываем, что баланс владельца кампании должен быть больше цены лида
         * Цена лида в кампании также должна быть выше, чем цена покупки данного лида
         */
        // SELECT * FROM `crm_campaign` WHERE (`townId`=563 OR `regionId`=57) AND `timeFrom`<=16 AND `timeTo`>=16 AND active=1
        $campaignsRows = Yii::app()->db->createCommand()
                ->select('c.*, u.balance')
                ->from("{{campaign}} c")
                ->leftJoin("{{user}} u", "u.id = c.buyerId")
                ->where("(c.townId=:townId OR c.regionId=:regionId) AND c.timeFrom<=:hour AND c.timeTo>:hour AND c.active=1 AND u.balance>=c.price AND c.price>=:buyPrice AND c.type=:typeBuyer", array(
                    ':townId' => $lead->town->id,
                    ':regionId' => $lead->town->regionId,
                    ':hour' => (int) date('H'),
                    ':buyPrice' => (int) $lead->buyPrice,
                    ':typeBuyer' => self::TYPE_BUYERS,
                ))
                ->order('c.lastLeadTime ASC')
                ->limit($limit)
                ->queryAll();

        foreach ($campaignsRows as $campaign) {

            // Проверка, работает ли кампания в данный день недели
            $workDaysArray = explode(',', $campaign['days']);
            if (!in_array(date('N'), $workDaysArray)) {
                continue;
            }

            // находим дневной лимит кампании
            $dayLimit = ($campaign['realLimit'] != 0) ? $campaign['realLimit'] : $campaign['leadsDayLimit'];

            // находим, сколько лидов сегодня уже отправлено в кампанию
            $campaignTodayLeads = Yii::app()->db->createCommand()
                    ->select('COUNT(*) counter')
                    ->from("{{lead}} l")
                    ->where("DATE(deliveryTime)=:todayDate AND campaignId=:campaignId AND leadStatus IN(:status1, :status2, :status3)", array(
                        ':todayDate' => date('Y-m-d'),
                        ':campaignId' => $campaign['id'],
                        ':status1' => Lead::LEAD_STATUS_SENT,
                        ':status2' => Lead::LEAD_STATUS_NABRAK,
                        ':status3' => Lead::LEAD_STATUS_RETURN,
                    ))
                    ->queryRow();


            // если в кампанию сегодня отправлено лидов меньше, чем дневной лимит, добавляем в список кампаний
            if ($campaignTodayLeads['counter'] < $dayLimit) {
                $campaigns[] = $campaign;
            }
        }

        if (sizeof($campaigns)) {

            /*
             * Если нужно вернуть массив id кампаний, возвращаем его
             */
            if ($returnArray === true) {
                $campIds = array();
                foreach ($campaigns as $camp) {
                    $campIds[] = $camp['id'];
                }
                return $campIds;
            } else {
                /**
                 * Если нужен не массив, возвращаем id первой кампании
                 */
                return $campaigns[0]['id'];
            }
        }

        // Если не нашлось ни одной кампании, ищем кампанию партнерских программ для данного региона

        $partnerCampaignsRow = Yii::app()->db->createCommand()
                ->select('c.*')
                ->from("{{campaign}} c")
                ->where("(c.townId=:townId OR c.regionId=:regionId) AND c.timeFrom<=:hour AND c.timeTo>:hour AND c.active=1 AND c.type=:typePartner", array(
                    ':townId' => $lead->town->id,
                    ':regionId' => $lead->town->regionId,
                    ':hour' => (int) date('H'),
                    ':typePartner' => self::TYPE_PARTNERS,
                ))
                ->order('c.lastLeadTime ASC')
                ->queryRow();

        if ($partnerCampaignsRow) {
            return ($returnArray === true) ? [$partnerCampaignsRow['id']] : $partnerCampaignsRow['id'];
        }
    }

    /**
     * Поиск кампаний по id покупателя
     * 
     * @param type $buyerId id покупателя
     * @return array массив кампаний
     */
    public static function getCampaignsForBuyer($buyerId)
    {
        $criteria = new CDbCriteria;
        $criteria->order = "active DESC";
        $criteria->addColumnCondition(array('buyerId' => (int) $buyerId));

        $dependency = new CDbCacheDependency('SELECT COUNT(id) FROM {{campaign}}');

        $campaigns = self::model()->cache(600, $dependency)->findAll($criteria);
        //CustomFuncs::printr($campaigns);

        return $campaigns;
    }

    /**
     * Возвращает имя кампании (город + регион) по ее id
     * 
     * @param int $id id кампании
     * @return string имя кампании
     */
    public static function getCampaignNameById($id)
    {
        $campaign = self::model()->cache(600)->with('town', 'region')->findByPk($id);

        if (!is_null($campaign)) {
            return $campaign->town->name . '' . $campaign->region->name;
        }
    }

    /**
     * Возвращает массивы продажных городов и регионов, ключами которых являются коды городов и регионов
     * 
     * @return array Массив городов и регионов, пример:
     * array(
     * 'regions' => array(
     *      71 => 1, 
     *      104 => 1), 
     * 'towns' => array(
     *      598 => 1,
     *      830 => 1,
     * )
     * )
     */
    public static function getPayedTownsRegions($cacheTime = 600)
    {
        $payedRegions = array();
        $payedTowns = array();

        $campaignsRows = Yii::app()->db->cache($cacheTime)->createCommand()
                ->select('regionId, townId')
                ->from('{{campaign}} c')
                ->where('active=1 AND timeFrom<=HOUR(NOW()) AND timeTo>HOUR(NOW())')
                ->queryAll();

        foreach ($campaignsRows as $row) {
            if ($row['regionId'] != 0) {
                $payedRegions[$row['regionId']] = 1;
            }
            if ($row['townId'] != 0) {
                $payedTowns[$row['townId']] = 1;
            }
        }

        return array(
            'towns' => $payedTowns,
            'regions' => $payedRegions,
        );
    }

    /**
     * Метод, вызываемый перед сохранением кампании.
     * Проверяет заполненность только одного из свойств: город ИЛИ регион
     * 
     * @return boolean Пройдена ли проверка
     */
    protected function beforeSave()
    {
        if (false === parent::beforeSave()) {
            return false;
        }

        if ($this->regionId && $this->townId) {
            $this->addError('townId', 'Выберите город ИЛИ регион (не оба одновременно)');
        }

        if (!$this->regionId && !$this->townId) {
            $this->addError('townId', 'Выберите город ИЛИ регион (не оба одновременно)');
        }

        if (empty($this->errors)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Возвращает число кампаний в статусе На модерации
     * 
     * @return integer число кампаний
     */
    public static function getModerationCount()
    {
        $campaignsRow = Yii::app()->db->createCommand()
                ->select('COUNT(*) counter')
                ->from('{{campaign}}')
                ->where("active=:active", array(':active' => self::ACTIVE_MODERATION))
                ->queryRow();

        return $campaignsRow['counter'];
    }

    /**
     * Подсчитывает текущий процент брака определенную дату
     * @param string $date Дата, за которую нужна статистика, формат yyyy-mm-dd
     * @return integer Description
     */
    public function calculateCurrentBrakPercent($date = NULL)
    {
        if (is_null($date)) {
            $date = date('Y-m-d');
        }

        $campaign24hoursLeadsRows = Yii::app()->db->createCommand()
                ->select('leadStatus, COUNT(*) counter')
                ->from('{{lead}}')
                ->where('campaignId = ' . $this->id . ' AND DATE(deliveryTime)="' . $date . '"')
                ->group('leadStatus')
                ->queryAll();
        //CustomFuncs::printr($campaign24hoursLeadsRows);

        $totalLeads = 0;
        $brakLeads = 0;
        foreach ($campaign24hoursLeadsRows as $row) {
            if ($row['leadStatus'] == Lead::LEAD_STATUS_BRAK || $row['leadStatus'] == Lead::LEAD_STATUS_NABRAK) {
                $brakLeads += $row['counter'];
            }
            $totalLeads += $row['counter'];
        }

        //echo "Всего лидов за сутки: " . $totalLeads . '<br />';
        //echo "Брак + на отбраковке: " . $brakLeads . '<br />';
        if ($totalLeads > 0) {
            $brakPercent = round(($brakLeads / $totalLeads) * 100);
        } else {
            $brakPercent = 0;
        }
        return $brakPercent;
    }

    /**
     * Проверка, можно ли забраковать лид данной кампании в данный момент
     * @param string $date Дата, на которую считать процент брака
     * @return boolean
     */
    public function checkCanBrak($date = NULL)
    {
        $brakPercent = $this->calculateCurrentBrakPercent($date);
        //echo 'Процент брака: ' . $brakPercent . '<br />';
        //echo 'Допустимый процент брака: ' . $campaign->brakPercent . '<br />';

        if ($brakPercent >= $this->brakPercent) {
            return false;
        }
        return true;
    }

}
