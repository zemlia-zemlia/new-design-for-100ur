<?php

namespace App\models;

use CActiveDataProvider;
use CActiveRecord;
use CDbCacheDependency;
use CDbCriteria;
use CHtml;
use GTMail;
use Yii;

/**
 * Класс для работы с кампаниями покупателей лидов.
 *
 * Доступные поля в таблице '{{campaign}}':
 *
 * @property int    $id
 * @property int    $regionId
 * @property int    $timeFrom
 * @property int    $timeTo
 * @property int    $price
 * @property int    $leadsDayLimit
 * @property int    $realLimit
 * @property int    $brakPercent
 * @property int    $buyerId
 * @property int    $active
 * @property int    $sendEmail
 * @property string $lastTransactionTime
 * @property string $days
 * @property int    $sendToApi
 * @property string $apiClass
 * @property int    $type
 * @property int    $balance
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
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'campaign';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['leadsDayLimit, brakPercent, buyerId, active', 'required', 'message' => 'Поле {attribute} должно быть заполнено'],
            ['regionId, townId, price, sendEmail, leadsDayLimit, realLimit, brakPercent, buyerId, active, timeFrom, timeTo, sendToApi, type', 'numerical', 'integerOnly' => true],
            ['price', 'validatePrice'],
            ['apiClass', 'validateApiClass'],
            ['days, apiClass', 'length', 'max' => 255],
            ['leadsDayLimit', 'compare', 'compareValue' => 10, 'operator' => '>=', 'message' => 'Лимит лидов должен быть не меньше 10'],
            ['workDays', 'type', 'type' => 'array'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, regionId, price, '
                . 'leadsDayLimit, brakPercent, buyerId, active', 'safe', 'on' => 'search', ],
        ];
    }

    /**
     * Валидатор поля цена лида.
     *
     * @param string $attribute
     * @param array  $params
     */
    public function validatePrice($attribute, $params)
    {
        if (self::TYPE_BUYERS == $this->type && $this->$attribute == 0) {
            $this->addError($attribute, 'Цена продажи в кампании покупателей должна быть больше нуля');
        }
    }

    /**
     * Валидатор поля Класс API.
     *
     * @param string $attribute
     * @param array  $params
     */
    public function validateApiClass($attribute, $params)
    {
        if ($this->$attribute != '' && !@class_exists('App\\components\\apiClasses\\' . $this->$attribute)) {
            $this->addError($attribute, 'Класс для работы с API не найден');
        }
    }

    /**
     * @return array relational rules
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'buyer' => [self::BELONGS_TO, User::class, 'buyerId'],
            'region' => [self::BELONGS_TO, Region::class, 'regionId'],
            'town' => [self::BELONGS_TO, Town::class, 'townId'],
            'leads' => [self::HAS_MANY, Lead::class, 'campaignId'],
            'leadsToday' => [self::HAS_MANY, Lead::class, 'campaignId',
                'condition' => 'DATE(leadsToday.deliveryTime)="' . date('Y-m-d') . '"',
            ],
            'leadsCount' => [self::STAT, Lead::class, 'campaignId'],
            'leadsTodayCount' => [self::STAT, Lead::class, 'campaignId',
                'condition' => 'DATE(t.deliveryTime)="' . date('Y-m-d') .
                    '" AND leadStatus IN(' . Lead::LEAD_STATUS_SENT . ', ' .
                    Lead::LEAD_STATUS_NABRAK . ', ' . Lead::LEAD_STATUS_RETURN . ')',
            ],
            'transactions' => [self::HAS_MANY, TransactionCampaign::class, 'campaignId', 'order' => 'transactions.id DESC'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
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
        ];
    }

    /**
     * Возвращает массив типов кампаний.
     *
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::TYPE_BUYERS => 'Кампании покупателей',
            self::TYPE_PARTNERS => 'Кампании партнерских программ',
        ];
    }

    /**
     * Возвращает название типа кампании.
     *
     * @return string
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
     *                             based on the search/filter conditions
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('regionId', $this->regionId);
        $criteria->compare('timeFrom', $this->timeFrom);
        $criteria->compare('timeTo', $this->timeTo);
        $criteria->compare('price', $this->price * 100);
        $criteria->compare('leadsDayLimit', $this->leadsDayLimit);
        $criteria->compare('brakPercent', $this->brakPercent);
        $criteria->compare('buyerId', $this->buyerId);
        $criteria->compare('active', $this->active);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    public static function getActivityStatuses()
    {
        return [
            self::ACTIVE_NO => 'Неактивна',
            self::ACTIVE_YES => 'Активна',
            self::ACTIVE_MODERATION => 'На проверке',
            self::ACTIVE_ARCHIVE => 'В архиве',
        ];
    }

    public function getActiveStatusName()
    {
        $statuses = self::getActivityStatuses();

        return $statuses[$this->active];
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className active record class name
     *
     * @return Campaign the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * находит список кампаний, подходящих для отправки заданного лида.
     *
     * @param int  $leadId      ID лида
     * @param bool $returnArray
     *
     * @return int ID кампании для отправки лида
     *
     * @throws \CException
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

        $campaigns = [];

        /**
         * Выбираем из базы активные кампании, настроенные на данный регион, город и время работы (время NOW())
         * сортировка по цене. Учитываем, что баланс владельца кампании должен быть больше цены лида
         * Цена лида в кампании также должна быть выше, чем цена покупки данного лида.
         */
        // SELECT * FROM `crm_campaign` WHERE (`townId`=563 OR `regionId`=57) AND `timeFrom`<=16 AND `timeTo`>=16 AND active=1
        $campaignsRows = Yii::app()->db->createCommand()
            ->select('c.*, u.balance')
            ->from('{{campaign}} c')
            ->leftJoin('{{user}} u', 'u.id = c.buyerId')
            ->where('(c.townId=:townId OR c.regionId=:regionId) AND c.timeFrom<=:hour AND c.timeTo>:hour AND c.active=1 AND u.balance>=c.price AND c.price>=:buyPrice AND c.type=:typeBuyer', [
                ':townId' => $lead->town->id,
                ':regionId' => $lead->town->regionId,
                ':hour' => (int) date('H'),
                ':buyPrice' => (int) $lead->buyPrice,
                ':typeBuyer' => self::TYPE_BUYERS,
            ])
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
            $dayLimit = (0 != $campaign['realLimit']) ? $campaign['realLimit'] : $campaign['leadsDayLimit'];

            // находим, сколько лидов сегодня уже отправлено в кампанию
            $campaignTodayLeads = Yii::app()->db->createCommand()
                ->select('COUNT(*) counter')
                ->from('{{lead}} l')
                ->where('DATE(deliveryTime)=:todayDate AND campaignId=:campaignId AND leadStatus IN(:status1, :status2, :status3)', [
                    ':todayDate' => date('Y-m-d'),
                    ':campaignId' => $campaign['id'],
                    ':status1' => Lead::LEAD_STATUS_SENT,
                    ':status2' => Lead::LEAD_STATUS_NABRAK,
                    ':status3' => Lead::LEAD_STATUS_RETURN,
                ])
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
            if (true === $returnArray) {
                $campIds = [];
                foreach ($campaigns as $camp) {
                    $campIds[] = $camp['id'];
                }

                return $campIds;
            } else {
                /*
                 * Если нужен не массив, возвращаем id первой кампании
                 */
                return $campaigns[0]['id'];
            }
        }

        // Если не нашлось ни одной кампании, ищем кампанию партнерских программ для данного региона

        $partnerCampaignsCommand = Yii::app()->db->createCommand()
            ->select('c.*')
            ->from('{{campaign}} c')
            ->where('(c.townId=:townId OR c.regionId=:regionId) AND c.timeFrom<=:hour AND c.timeTo>:hour AND c.active=1 AND c.type=:typePartner', [
                ':townId' => $lead->town->id,
                ':regionId' => $lead->town->regionId,
                ':hour' => (int) date('H'),
                ':typePartner' => self::TYPE_PARTNERS,
            ])
            ->order('c.lastLeadTime ASC');

        // Если лид поступил менее 3 часов назад, не продаем его в партнерки с ценой ниже цены покупки
        if ((new \DateTime($lead->question_date)) > (new \DateTime())->modify('-3 hours')) {
            $partnerCampaignsCommand->andWhere('c.price >= :buyPrice', [
                ':buyPrice' => $lead->buyPrice,
            ]);
        }

        $partnerCampaignsRow = $partnerCampaignsCommand->queryRow();

        if ($partnerCampaignsRow) {
            return (true === $returnArray) ? [$partnerCampaignsRow['id']] : $partnerCampaignsRow['id'];
        }
    }

    /**
     * Поиск кампаний по id покупателя.
     *
     * @param int  $buyerId id покупателя
     * @param bool $active
     *
     * @return array массив активных кампаний
     */
    public static function getCampaignsForBuyer($buyerId, $active = true)
    {
        $criteria = new CDbCriteria();
        $criteria->order = 'id DESC';
        $criteria->addColumnCondition(['buyerId' => (int) $buyerId]);
        $criteria->addCondition(true == $active ? 'active = ' . self::ACTIVE_YES : 'active != ' . self::ACTIVE_YES);

        $dependency = new CDbCacheDependency('SELECT COUNT(id) FROM {{campaign}}');

        $campaigns = self::model()->cache(600, $dependency)->findAll($criteria);

        return $campaigns;
    }

    /**
     * Поиск неактивных кампаний по id покупателя.
     *
     * @param int $buyerId id покупателя
     *
     * @return array массив не активных кампаний
     */
    public static function getCampaignsForBuyerNoActive($buyerId)
    {
        return static::getCampaignsForBuyer($buyerId, false);
    }

    /**
     * Возвращает имя кампании (город + регион) по ее id.
     *
     * @param int $id id кампании
     *
     * @return string имя кампании
     */
    public static function getCampaignNameById($id)
    {
        $campaign = self::model()->cache(600)->with('town', 'region')->findByPk($id);

        if (!is_null($campaign)) {
            $campaignName = '';
            if ($campaign->town instanceof Town) {
                $campaignName .= $campaign->town->name;
            }
            if ($campaign->region instanceof Region) {
                $campaignName .= ' ' . $campaign->region->name;
            }

            return trim($campaignName);
        }
    }

    /**
     * Возвращает массивы продажных городов и регионов, ключами которых являются коды городов и регионов.
     *
     * @return array Массив городов и регионов, пример:
     *               array(
     *               'regions' => array(
     *               71 => 1,
     *               104 => 1),
     *               'towns' => array(
     *               598 => 1,
     *               830 => 1,
     *               )
     *               )
     */
    public static function getPayedTownsRegions($cacheTime = 600)
    {
        $payedRegions = [];
        $payedTowns = [];

        $campaignsRows = Yii::app()->db->cache($cacheTime)->createCommand()
            ->select('regionId, townId')
            ->from('{{campaign}} c')
            ->where('active=1 AND timeFrom<=HOUR(NOW()) AND timeTo>HOUR(NOW())')
            ->queryAll();

        foreach ($campaignsRows as $row) {
            if (0 != $row['regionId']) {
                $payedRegions[$row['regionId']] = 1;
            }
            if (0 != $row['townId']) {
                $payedTowns[$row['townId']] = 1;
            }
        }

        return [
            'towns' => $payedTowns,
            'regions' => $payedRegions,
        ];
    }

    /**
     * Метод, вызываемый перед сохранением кампании.
     * Проверяет заполненность только одного из свойств: город ИЛИ регион.
     *
     * @return bool Пройдена ли проверка
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
     * Возвращает число кампаний в статусе На модерации.
     *
     * @return int число кампаний
     */
    public static function getModerationCount()
    {
        $campaignsRow = Yii::app()->db->createCommand()
            ->select('COUNT(*) counter')
            ->from('{{campaign}}')
            ->where('active=:active', [':active' => self::ACTIVE_MODERATION])
            ->queryRow();

        return $campaignsRow['counter'];
    }

    /**
     * Подсчитывает текущий процент брака определенную дату.
     *
     * @param string $date Дата, за которую нужна статистика, формат yyyy-mm-dd
     *
     * @return int Description
     */
    public function calculateCurrentBrakPercent($date = null)
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

        $totalLeads = 0;
        $brakLeads = 0;
        foreach ($campaign24hoursLeadsRows as $row) {
            if (Lead::LEAD_STATUS_BRAK == $row['leadStatus'] || Lead::LEAD_STATUS_NABRAK == $row['leadStatus']) {
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
     *
     * @param string $date Дата, на которую считать процент брака
     *
     * @return bool
     */
    public function checkCanBrak($date = null)
    {
        $brakPercent = $this->calculateCurrentBrakPercent($date);
        //echo 'Процент брака: ' . $brakPercent . '<br />';
        //echo 'Допустимый процент брака: ' . $campaign->brakPercent . '<br />';

        if ($brakPercent >= $this->brakPercent) {
            return false;
        }

        return true;
    }

    public function getFullApiClass(): string
    {
        return '' != $this->apiClass ? 'App\\components\\apiClasses\\' . $this->apiClass : '';
    }

    /**
     * Отправка кампании в архив с уведомлением пользователя.
     *
     * @return bool Результат сохранения записи
     */
    public function sendToArchive()
    {
        $this->active = self::ACTIVE_ARCHIVE;

        if ($this->save()) {
            if ($this->sendArchiveNotification()) {
                return true;
            }
        } else {
            Yii::log('Ошибка при архивации кампании #' . $this->id, 'error', 'system.web');
        }

        return false;
    }

    /**
     * Отправка уведомления пользователю о том, что его кампания отправлен в архив.
     */
    public function sendArchiveNotification()
    {
        $buyer = $this->buyer;

        if (0 == $buyer->active100) {
            return false;
        }

        // в письмо вставляем ссылку на кампанию
        $campaignLink = Yii::app()->createUrl('/buyer/buyer/leads/campaign', ['id' => $this->id]);

        /*  проверим, есть ли у пользователя заполненное поле autologin, если нет,
         *  генерируем код для автоматического логина при переходе из письма
         * если есть, вставляем существующее значение
         * это сделано, чтобы не создавать новую строку autologin при наличии старой
         * и дать возможность залогиниться из любого письма, содержащего актуальную строку autologin
         */
        $autologinString = (isset($buyer->autologin) && '' != $buyer->autologin) ? $buyer->autologin : $buyer->generateAutologinString();

        if (!$buyer->autologin) {
            $buyer->autologin = $autologinString;
            if (!$buyer->save()) {
                Yii::log('Не удалось сохранить строку autologin пользователю ' . $buyer->email . ' с уведомлением о переносе кампании в архив ' . $this->id, 'error', 'system.web.User');
            }
        }

        $campaignLink .= '&autologin=' . $autologinString;

        $mailer = new GTMail();
        $mailer->subject = CHtml::encode($buyer->name) . ', Ваша кампания отправлена в архив';
        $mailer->message = '<h1>Ваша кампания отправлена в архив</h1>
            <p>Здравствуйте, ' . CHtml::encode($buyer->name) . '<br /><br />' .
            CHtml::link('Ваша кампания', $campaignLink) . ' отправлена в архив, так как по ней не совершалось никаких действий более 15 дней.</p>';

        // отправляем письмо на почту пользователя
        $mailer->email = $buyer->email;

        if ($mailer->sendMail()) {
            Yii::log('Отправлено письмо пользователю ' . $buyer->email . ' с уведомлением о переносе кампании в архив ' . $this->id, 'info', 'system.web.User');

            return true;
        } else {
            // не удалось отправить письмо
            Yii::log('Не удалось отправить письмо пользователю ' . $buyer->email . ' с уведомлением о переносе кампании в архив ' . $this->id, 'error', 'system.web.User');

            return false;
        }
    }
}
