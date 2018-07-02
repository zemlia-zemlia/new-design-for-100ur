<?php

/**
 * Модель для работы с лидами 100 юристов
 *
 * Поля из таблицы '{{lead}}':
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property integer $sourceId
 * @property string $question
 * @property string $question_date
 * @property integer $townId
 * @property integer $leadStatus
 * @property integer $type
 * @property integer $campaignId
 * @property float $price
 * @property string $deliveryTime
 * @property string $lastLeadTime
 * @property integer $brakReason
 * @property string $brakComment
 * @property string $secretCode
 * @property integer $buyPrice
 * @property integer $buyerId
 * 
 * @author Michael Krutikov m@mkrutikov.pro
 */
class Lead extends CActiveRecord
{

    public $date1, $date2; // диапазон дат, используемый при поиске
    public $newTownId; // для случая смены города при отбраковке
    public $regionId;
    public $testMode = 0; // режим тестирования в форме создания лида
    public $categoriesId; // для формы создания/редактирования лида
    public $agree = 1; // согласие на обработку персональных данных

    // статусы лидов

    const LEAD_STATUS_DEFAULT = 0; // лид никуда не отправлен
    const LEAD_STATUS_SENT_CRM = 1; // лид отправлен в CRM
    const LEAD_STATUS_SENT_LEADIA = 2; // лид отправлен в Leadia
    const LEAD_STATUS_NABRAK = 3; // на отбраковке
    const LEAD_STATUS_BRAK = 4; // брак
    const LEAD_STATUS_RETURN = 5; // возврат с отбраковки
    const LEAD_STATUS_SENT = 6; // отправлен покупателю
    const LEAD_STATUS_DUPLICATE = 7; // дубль (этот автор уже отправлял нам вопрос в последние N часов)
    const LEAD_STATUS_PREMODERATION = 8; // на премодерации
    // типы лидов
    const TYPE_QUESTION = 1; // вопрос (по умолч.)
    const TYPE_CALL = 2; // запрос звонка
    const TYPE_DOCS = 3; // запрос документов
    const TYPE_YURIST = 4; // поиск юриста / адвоката
    const TYPE_INCOMING_CALL = 5; // входящий звонок
    const TYPE_SERVICES = 6; // запрос юридических услуг
    // причины отбраковки
    const BRAK_REASON_BAD_QUESTION = 1; // не юридический вопрос
    const BRAK_REASON_BAD_NUMBER = 2; // неверный номер
    const BRAK_REASON_BAD_REGION = 3; // не тот регион
    const BRAK_REASON_SPAM = 4; // спам
    
    // коды результатов сохранения лида при продаже
    const SAVE_RESULT_CODE_OK = 0; // лид продан и сохранен
    const SAVE_RESULT_CODE_PARTNER_REJECT = 10; // партнерское апи не приняло заявку
    const SAVE_RESULT_CODE_ERROR = 500; // другая ошибка продажи

    /*
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Lead the static model class
     */

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{lead}}';
    }

    /**
     * @return array Правила валидации
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, phone, sourceId, townId, town', 'required', 'message' => 'Поле {attribute} должно быть заполнено'),
            array('sourceId, townId, newTownId, questionId, leadStatus, addedById, type, campaignId, brakReason, buyerId', 'numerical', 'integerOnly' => true),
            array('question', 'required', 'message' => 'Поле {attribute} не заполнено', 'except' => ['createCall']),
            array('price, buyPrice, regionId, testMode', 'numerical'),
            array('deliveryTime, categoriesId', 'safe'),
            array('question', 'safe', 'on' => ['createCall']),
            array('agree', 'compare', 'compareValue' => 1, 'on' => array('create', 'createCall'), 'message' => 'Вы должны согласиться на обработку персональных данных'),
            array('name, phone, email, secretCode, brakComment', 'length', 'max' => 255),
            array('townId', 'match', 'not' => true, 'pattern' => '/^0$/', 'message' => 'Поле Город не заполнено'),
            array('name', 'match', 'pattern' => '/^([а-яА-Я0-9ёЁ\-., ])+$/u', 'message' => 'В имени могут присутствовать русские буквы, цифры, точка, дефис и пробел', 'except' => 'parsing'),
            array('phone', 'match', 'pattern' => '/^([0-9]{11})+$/u', 'message' => 'В номере телефона могут присутствовать только цифры'),
            array('email', 'email', 'message' => 'E-mail похож на ненастоящий, проверьте, пожалуйста, правильность набора'),
            array('date1, date2', 'match', 'pattern' => '/^([0-9\-])+$/u', 'message' => 'В датах могут присутствовать только цифры и знак плюса'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, phone, sourceId, question, question_date, townId, leadStatus', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array Связи с другими моделями
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'source' => array(self::BELONGS_TO, 'Leadsource', 'sourceId'),
            'town' => array(self::BELONGS_TO, 'Town', 'townId'),
            'campaign' => array(self::BELONGS_TO, 'Campaign', 'campaignId'),
            'questionObject' => array(self::BELONGS_TO, 'Question', 'questionId'),
            'categories' => array(self::MANY_MANY, 'QuestionCategory', '{{lead2category}}(leadId, cId)'),
            'buyer' => array(self::BELONGS_TO, 'User', 'buyerId'),
        );
    }

    /**
     * @return array Наименования атрибутов (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Имя',
            'phone' => 'Телефон',
            'email' => 'Email',
            'leadStatus' => 'Статус',
            'source' => 'Источник',
            'sourceId' => 'Источник',
            'question' => 'Вопрос',
            'question_date' => 'Дата',
            'townId' => 'ID города',
            'town' => 'Город',
            'regionId' => 'Регион',
            'questionId' => 'ID связанного вопроса',
            'type' => 'Тип',
            'deliveryTime' => 'Время отправки покупателю',
            'price' => 'Цена',
            'campaignId' => 'ID кампании',
            'lastLeadTime' => 'Время отправки последнего лида',
            'secretCode' => 'Секретный код',
            'brakComment' => 'Комментарий отбраковки',
            'brakReason' => 'Причина отбраковки',
            'buyPrice' => 'Цена покупки',
            'date1' => 'От',
            'date2' => 'До',
            'testMode' => 'Режим тестирования API',
            'categories' => 'Категории права',
            'agree' => 'Согласие на обработку персональных данных',
            'buyerId' => 'id покупателя',
        );
    }

    /**
     * Возвращает массив, ключами которого являются коды статусов, а значениями - названия статусов
     * 
     * @return array Массив статусов (код статуса => название)
     */
    static public function getLeadStatusesArray()
    {
        return array(
            self::LEAD_STATUS_DEFAULT => 'не обработан',
            self::LEAD_STATUS_SENT_CRM => 'в CRM',
            self::LEAD_STATUS_SENT_LEADIA => 'в Leadia',
            self::LEAD_STATUS_SENT => 'выкуплен',
            self::LEAD_STATUS_NABRAK => 'на отбраковке',
            self::LEAD_STATUS_BRAK => 'брак',
            self::LEAD_STATUS_RETURN => 'не принят к отбраковке',
            self::LEAD_STATUS_DUPLICATE => 'дубль',
            self::LEAD_STATUS_PREMODERATION => 'недолид',
        );
    }

    /**
     * Возвращает название статуса объекта
     * 
     * @return string статус объекта
     */
    public function getLeadStatusName()
    {
        $statusesArray = self::getLeadStatusesArray();
        $statusName = $statusesArray[$this->leadStatus];
        return $statusName;
    }

    /**
     * возвращает массив, ключами которого являются коды типов, а значениями - названия
     * 
     * @return array Массив типов лидов (код => название)
     */
    static public function getLeadTypesArray()
    {
        return array(
            self::TYPE_QUESTION => 'вопрос',
            self::TYPE_CALL => 'запрос звонка',
            self::TYPE_DOCS => 'заказ документов',
            self::TYPE_YURIST => 'поиск юриста',
            self::TYPE_INCOMING_CALL => 'входящий звонок',
            self::TYPE_SERVICES => 'юридические услуги',
        );
    }

    /**
     * Возвращает название типа лида
     * 
     * @return string тип лида
     */
    public function getLeadTypeName()
    {
        $typesArray = self::getLeadTypesArray();
        $typeName = $typesArray[$this->type];
        return $typeName;
    }

    /**
     * возвращает массив, ключами которого являются коды причин отбраковки, а значениями - названия
     * 
     * @return array массив причин отбраковки (код => наименование)
     */
    static public function getBrakReasonsArray()
    {
        return array(
            self::BRAK_REASON_BAD_QUESTION => 'не юридический вопрос',
            self::BRAK_REASON_BAD_NUMBER => 'неверный номер',
            self::BRAK_REASON_BAD_REGION => 'не тот регион',
            self::BRAK_REASON_SPAM => 'спам',
        );
    }

    /**
     * Возвращает причину отбраковки для лида
     * 
     * @return string Причина отбраковки
     */
    public function getReasonName()
    {
        $reasonsArray = self::getBrakReasonsArray();
        $reasonName = $reasonsArray[$this->brakReason];
        return $reasonName;
    }

    /**
     * Проверяет на существование покупателя и кампанию
     * @param type $buyerId id покупателя
     * @param type $campaignId id кампании
     * @return boolean | array Массив (User, Campaign) или false если оба объекта не найдены
     */
    private function checkBuyerAndCampaign($buyerId, $campaignId)
    {
        if ($buyerId == 0 && $campaignId == 0) {
            return false;
        }

        $campaign = Campaign::model()->findByPk($campaignId);

        if ($campaignId != 0 && is_null($campaign)) {
            return false;
        }

        // если указана кампания, но не указан покупатель, берем покупателя из кампании
        if ($buyerId == 0) {
            $buyerId = $campaign->buyerId;
        }
        $buyer = User::model()->findByPk($buyerId);

        if (!$buyer) {
            return false;
        }

        return [
            'buyer' => $buyer,
            'campaign' => $campaign,
        ];
    }

    /**
     * Сохранение проданного лида и связанных данных
     * @param type $buyer
     * @param type $transaction
     * @param type $campaign
     * @return integer Код результата сохранения
     */
    private function saveSoldLead($buyer, $transaction, $campaign)
    {
        if (!$this->save()) {
            $leadSaved = false;
            Yii::log("Не удалось сохранить лид " . $this->id, 'error', 'system.web.CCommand');
        } else {
            $leadSaved = true;
        }

        // сохранение покупателя. Важно сохранить его ДО сохранения транзакции, чтобы записалось время последней транзакции в пользователе
        if (!$buyer->save(false)) {
            $buyerSaved = false;
            Yii::log("Не удалось сохранить покупателя при продаже лида " . $buyer->id, 'error', 'system.web.CCommand');
        } else {
            $buyerSaved = true;
        }

        // сохранение транзакции за лид
        if (isset($transaction) && !$transaction->save()) {
            $transactionSaved = false;
            Yii::log("Не удалось сохранить транзакцию за продажу лида " . $this->id, 'error', 'system.web.CCommand');
        } else {
            $transactionSaved = true;
        }

        if ($campaign && $campaign->type == Campaign::TYPE_PARTNERS && $campaign->sendToApi == 1 && class_exists($campaign->apiClass)) {
            $apiClass = ApiClassFactory::getApiClass($campaign->apiClass);
            $leadSentToPartner = $apiClass->send($this);
        }

        if ($buyerSaved != false && $leadSaved != false && $transactionSaved != false && $leadSentToPartner !== false) {
            return self::SAVE_RESULT_CODE_OK;
        } 
        
        if($leadSentToPartner === false) {
            // если партнерское апи не приняло лид
            return self::SAVE_RESULT_CODE_PARTNER_REJECT;
        }
        
        return self::SAVE_RESULT_CODE_ERROR; // код ошибки по умолчанию
    }

    /**
     * Отправка лида в кампанию по почте
     * @param type $campaign
     * @return boolean
     */
    private function sendToCampaignByMail(Campaign $campaign)
    {
        // Если определена кампания и в ней стоит настройка Отправлять лиды на почту
        if ($campaign && $campaign->sendEmail) {
            return $this->sendByEmail($campaign->id);
        }
        return false;
    }

    /**
     * Продажа лида покупателю
     * @param integer $buyerId id покупателя
     * @param integer $campaignId id кампании
     * @return boolean результат
     */
    public function sellLead($buyerId = 0, $campaignId = 0)
    {
        // получаем объекты покупателя и кампании
        $buyerAndCampaignResult = $this->checkBuyerAndCampaign($buyerId, $campaignId);
        if ($buyerAndCampaignResult === false) {
            return false;
        }

        $campaign = $buyerAndCampaignResult['campaign'];
        $buyer = $buyerAndCampaignResult['buyer'];

        $leadPrice = ($campaignId && $campaign && $campaign->price) ? $campaign->price : (int) $this->calculatePrices()[1];

        if ($campaign && $campaign->type == Campaign::TYPE_PARTNERS) {
            // У лидов продаваемых в партнерки цена продажи 0
            $leadPrice = 0;
        }

        if ($buyer->balance < $leadPrice) {
            // на балансе покупателя недостаточно средств
            return false;
        } else {
            // списываем деньги со счета покупателя
            $buyer->balance -= $leadPrice;
        }

        $transactionTime = date('Y-m-d H:i:s');

        if ($leadPrice > 0) {
            $transaction = new TransactionCampaign();
            $transaction->buyerId = $buyerId;
            $transaction->campaignId = $campaignId;
            $transaction->sum = -$leadPrice;
            $transaction->description = 'Покупка заявки #' . $this->id;
            $transaction->time = $transactionTime;
        } else {
            $buyer->lastTransactionTime = $transactionTime;
        }

        $this->leadStatus = self::LEAD_STATUS_SENT;
        $this->buyerId = $buyerId;
        $this->price = $leadPrice;
        $this->deliveryTime = $transactionTime;
        // записываем в лид кампанию
        $this->campaignId = $campaignId;

        // Сохранение через транзакцию: если хоть один из компонентов не сохранился, отменяем операцию

        $dbTransaction = $this->dbConnection->beginTransaction();
        try {
            // сохранение лида
            $soldLeadReultCode = $this->saveSoldLead($buyer, $transaction, $campaign);
            
            if ($soldLeadReultCode === self::SAVE_RESULT_CODE_OK) {
                $dbTransaction->commit();
                // записываем в кампанию время отправки последнего лида
                Yii::app()->db->createCommand()->update('{{campaign}}', array('lastLeadTime' => date('Y-m-d H:i:s')), 'id=:id', array(':id' => $campaign->id));

                $this->sendToCampaignByMail($campaign);
            } else {
                // если что-то не сохранилось, откатываем транзакцию
                $dbTransaction->rollback();
            }

            // Если при отправке лида в партнерское API вернулась ошибка, помечаем лид как дубль
            if ($soldLeadReultCode == self::SAVE_RESULT_CODE_PARTNER_REJECT) {
                $this->leadStatus = self::LEAD_STATUS_DUPLICATE;
                $this->save();
            }
        } catch (Exception $e) {
            $dbTransaction->rollback();
            throw $e;
        }

        // Если лид был куплен у вебмастера, переведем ему деньги
        $this->payWebmaster();
        $this->logSoldLead($buyer, $campaign);
        return true;
    }

    /**
     * Запись в лог информации о проданном лиде
     * @param type $buyer
     * @param type $campaign
     */
    private function logSoldLead(User $buyer, Campaign $campaign)
    {
        $logMessage = 'Лид #' . $this->id . ' продан ';
        if ($campaign) {
            $logMessage .= 'в кампанию #' . $campaign->id . '(' . Campaign::getCampaignNameById($campaign->id) . ')';
            if ($buyer) {
                $logMessage .= ': ' . $buyer->name;
            }
        } else if ($buyer != 0 && $buyer) {
            $logMessage .= 'покупателю #' . $buyerId . ' (' . $buyer->getShortName() . ')';
        }
        LoggerFactory::getLogger('db')->log($logMessage, 'Lead', $this->id);
    }
    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('sourceId', $this->sourceId);
        $criteria->compare('question', $this->question, true);
        $criteria->compare('question_date', $this->question_date, true);
        $criteria->compare('townId', $this->townId);
        $criteria->compare('type', $this->type);
        $criteria->compare('leadStatus', $this->leadStatus);
        $criteria->compare('DATE(t.question_date)>', CustomFuncs::invertDate($this->date1));
        $criteria->compare('DATE(t.question_date)<', CustomFuncs::invertDate($this->date2));

        // если применялся поиск по региону
        if ($this->regionId) {
            $criteria->with = array('town' => array('condition' => 'town.regionId=' . $this->regionId), 'town.region');
        } else {
            $criteria->with = array('town', 'town.region');
        }

        $criteria->order = 't.id DESC';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));
    }

    /**
     * Отправка лида по почте
     * 
     * @param int $campaignId id кампании
     * @return boolean
     */
    public function sendByEmail($campaignId = 0)
    {
        if ($campaignId) {
            $campaign = Campaign::model()->with('buyer')->findByPk($campaignId);
        } 
        
        if($campaignId == 0 || is_null($campaign)){
            return false;
        }

        $mailer = new GTMail();
        $mailer->subject = "Заявка город " . $this->town->name . " (" . $this->town->region->name . ")";
        $mailer->message = "<h3>Заявка на консультацию</h3>";
        $mailer->message .= "<p>Имя: " . CHtml::encode($this->name) . ",</p>";
        $mailer->message .= "<p>Город: " . CHtml::encode($this->town->name) . " (" . $this->town->region->name . ")" . "</p>";
        $mailer->message .= "<p>Телефон: " . $this->phone . "</p>";
        $mailer->message .= "<p>Уникальный код заявки: " . $this->secretCode . "</p>";
        $mailer->message .= "<p>Сообщение:<br />" . CHtml::encode($this->question) . "</p>";

        // Вставляем ссылку на отбраковку только если у кампании процент брака больше нуля
        if ($campaign->brakPercent > 0) {
            $mailer->message .= "<hr /><p>"
                    . "<a style='display:inline-block; padding:5px 10px; border:#999 1px solid; color:#666; background-color:#fff; text-decoration:none;' href='https://100yuristov.com/site/brakLead/?code=" . $this->secretCode . "'>Отбраковка</a>"
                    . "</p>";
        }

        $mailer->email = $campaign->buyer->email;

        if ($mailer->sendMail()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Возвращает количество лидов с определенным статусом
     * 
     * @param int $status статус
     * @param boolean $noCampaign считать ли лиды без кампании
     * @return int количество лидов
     */
    public static function getStatusCounter($status, $noCampaign = true)
    {
        if ($noCampaign) {
            $condition = "leadStatus=:status AND campaignId!=0";
        } else {
            $condition = "leadStatus=:status";
        }
        $counterRow = Yii::app()->db->cache(60)->createCommand()
                ->select('COUNT(*) counter')
                ->from("{{lead}}")
                ->where($condition, array(":status" => (int) $status))
                ->queryRow();
        $counter = $counterRow['counter'];
        return $counter;
    }

    /**
     * возвращает количество лидов с таким же номером телефона и городом, добавленных не более $timeframe секунд назад
     * 
     * @param int $timeframe временной интеркал (сек.)
     * @return int количество лидов 
     */
    public function findDublicates($timeframe = 600)
    {
        $dublicatesRow = Yii::app()->db->createCommand()
                ->select("COUNT(*) counter")
                ->from("{{lead}}")
                ->where("phone=:phone AND townId=:townId AND question_date>=NOW()-INTERVAL :timeframe SECOND", array(":phone" => $this->phone, ":townId" => $this->townId, ":timeframe" => $timeframe))
                ->queryRow();

        //CustomFuncs::printr($dublicatesRow['counter']);

        return $dublicatesRow['counter'];
    }

    /**
     * Метод, вызываемый перед сохранением объекта
     * 
     * @return boolean 
     */
    protected function beforeSave()
    {
        // удаляем из номера телефона все нецифровые символы
        $this->phone = Question::normalizePhone($this->phone);

        // создаем поле Секретный код, чтобы покупатель лида мог работать с ним, перейдя по ссылке из письма
        if ($this->secretCode == '') {
            $this->secretCode = md5(time() . $this->phone . strlen($this->question) . mt_rand(100000, 999999));
        }

        if ($this->isNewRecord) {
            // проверка на дубли работает только для новых записей
            // если за последние 24 часа были лиды с таким же номером телефона, ставим лиду статус Дубль
            if ($this->findDublicates(86400) > 0) {
                //$this->leadStatus = self::LEAD_STATUS_DUPLICATE;
                return false;
            }
        }

        // при переводе лида в статус Брак из другого статуса удаляем у вебмастера транзакцию по этому лиду
        if ($this->leadStatus == self::LEAD_STATUS_BRAK) {
            $oldStatusRow = Yii::app()->db->createCommand()
                    ->select('leadStatus')
                    ->from('{{lead}}')
                    ->where('id=:id', array(':id' => $this->id))
                    ->queryRow();
            // старый статус
            $oldStatus = $oldStatusRow['leadStatus'];

            if ($oldStatus !== $this->leadStatus) {
                $removeTransactionResult = Yii::app()->db->createCommand()
                        ->delete('{{partnerTransaction}}', 'leadId=:leadId', array(':leadId' => $this->id));
            }
        }


        return parent::beforeSave();
    }

    /**
     * Метод, автоматически вызываемый после сохранения лида
     */
    protected function afterSave()
    {
        parent::afterSave();

        if (!$this->isNewRecord) {
            return;
        }

        if ($this->leadStatus != Lead::LEAD_STATUS_DEFAULT) {
            return;
        }

        LoggerFactory::getLogger('db')->log('Создан лид #' . $this->id . ', ' . $this->town->name, 'Lead', $this->id);

        // после сохранения лида ищем для него кампанию
        $campaignId = Campaign::getCampaignsForLead($this->id);
        // если кампания найдена, отправляем в нее лид
        if ($campaignId) {
            // установим свойство isNewRecord = false, чтобы обновить, а не создать копию лида при продаже 
            $this->setIsNewRecord(false);
            $this->sellLead(0, $campaignId);
        }
    }

    /**
     * Возвращает статистику проданных лидов для покупателя или кампании
     */
    public static function getStatsByPeriod($dateFrom, $dateTo, $buyerId = 0, $campaignId = 0)
    {
        // Нужно обязательно указать либо покупателя, либо кампанию
        if ($buyerId === 0 && $campaignId === 0) {
            return false;
        }

        $leadsCommand = Yii::app()->db->createCommand()
                ->select('id, price, DATE(deliveryTime) date')
                ->from("{{lead}}")
                ->order("date")
                ->where("DATE(deliveryTime) >= :dateFrom AND DATE(deliveryTime) <= :dateTo AND leadStatus IN (:status1, :status2, :status3)", array(':dateFrom' => $dateFrom, ':dateTo' => $dateTo, ':status1' => self::LEAD_STATUS_SENT, ':status2' => self::LEAD_STATUS_RETURN, ':status3' => self::LEAD_STATUS_NABRAK));

        // если выборка по покупателю, найдем лиды, проданные ему или в его кампании
        if ($buyerId) {
            $buyer = User::model()->with('campaigns')->findByPk($buyerId);
            $campaignsIds = array();
            foreach ($buyer->campaigns as $camp) {
                $campaignsIds[] = $camp->id;
            }

            $leadsCommand->andWhere('buyerId=:buyerId OR campaignId IN (:campaignsIds)', [
                ':buyerId' => $buyerId,
                ':campaignsIds' => $campaignsIds,
            ]);
        }

        // если по кампании
        if ($campaignId) {
            $leadsCommand->andWhere("campaignId = :campaignId", array(':campaignId' => (int) $campaignId));
        }

        $leadsRows = $leadsCommand->queryAll();
        $leads = [];

        foreach ($leadsRows as $row) {
            $leads['dates'][$row['date']]['count'] ++;
            $leads['dates'][$row['date']]['sum'] += $row['price'];
            $leads['total'] ++;
            $leads['sum'] += $row['price'];
        }

        return $leads;
    }

    /**
     * Вычисляет базовые цены покупки и продажи для лида
     * 
     * @return array Массив с двумя ценами [0 => цена покупки, 1 => цена продажи]
     */
    public function calculatePrices()
    {
        $regionBuyPrice = 20;
        $regionSellPrice = 50;
        $townBuyPrice = 0;
        $townSellPrice = 0;

        $town = $this->town;
        if ($town) {
            $region = $this->town->region;
            $townBuyPrice = $town->buyPrice;
            $townSellPrice = $town->sellPrice;
        }

        if ($region) {
            $regionBuyPrice = $region->buyPrice;
            $regionSellPrice = $region->sellPrice;
        }

        // цена города приоритетнее цены региона

        if ($townBuyPrice == 0) {
            $townBuyPrice = $regionBuyPrice;
        }

        $townSellPrice = $townBuyPrice * Yii::app()->params['priceCoeff'];


        return array(0 => $townBuyPrice, 1 => $townSellPrice);
    }

    /**
     * Создает транзакцию оплаты вебмастеру, приславшему нам лид
     * @return boolean
     */
    protected function payWebmaster()
    {
        if ($this->source && $this->source->user && $this->buyPrice > 0) {
            $sourceUser = $this->source->user;
            $priceCoeff = !is_null($sourceUser) ? $sourceUser->priceCoeff : 1; // коэффициент, на который умножается цена покупки лида
            // запишем транзакцию за лид
            $partnerTransaction = new PartnerTransaction;
            $partnerTransaction->sum = $this->buyPrice * $priceCoeff;
            $partnerTransaction->leadId = $this->id;
            $partnerTransaction->sourceId = $this->sourceId;
            $partnerTransaction->partnerId = $this->source->user->id;
            $partnerTransaction->comment = "Начисление за лид #" . $this->id;
            if (!$partnerTransaction->save()) {
                Yii::log("Не удалось сохранить транзакцию за покупку лида " . $this->id . ' ' . print_r($partnerTransaction->errors), 'error', 'system.web.CCommand');
            } else {
                return true;
            }
        }
        return false;
    }

    public function leadRequiresModerationStatus()
    {
        // найдем объект источника лидов для данной папки
        $source = $this->source;
        if (!$source) {
            return self::LEAD_STATUS_DEFAULT;
        }

        return ($source->moderation == 0) ? self::LEAD_STATUS_DEFAULT : self::LEAD_STATUS_PREMODERATION;
    }

}
