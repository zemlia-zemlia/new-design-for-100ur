<?php

namespace App\models;

use App\components\ApiClassFactory;
use App\helpers\DateHelper;
use App\helpers\PhoneHelper;
use App\helpers\StringHelper;
use App\notifiers\LeadNotifier;
use CActiveDataProvider;
use CActiveRecord;
use CDbCriteria;
use Exception;
use App\extensions\Logger\LoggerFactory;
use Yii;
use YurcrmClient\YurcrmClient;
use YurcrmClient\YurcrmResponse;

/**
 * Модель для работы с лидами 100 юристов.
 *
 * Поля из таблицы '{{lead}}':
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property int $sourceId
 * @property string $question
 * @property string $question_date
 * @property int $townId
 * @property int $leadStatus
 * @property int $type
 * @property int $campaignId
 * @property int $price
 * @property string $deliveryTime
 * @property string $lastLeadTime
 * @property int $brakReason
 * @property string $brakComment
 * @property string $secretCode
 * @property int $buyPrice
 * @property int $buyerId
 *
 * @author Michael Krutikov m@mkrutikov.pro
 */
class Lead extends CActiveRecord
{
    public $date1;
    public $date2; // диапазон дат, используемый при поиске
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

    /** @var ApiClassFactory */
    protected $apiClassFactory;

    /** @var YurcrmClient */
    protected $yurcrmClient;

    /** @var LeadNotifier */
    protected $notifier;

    public function init()
    {
        $this->apiClassFactory = new ApiClassFactory();
        $this->notifier = new LeadNotifier(Yii::app()->mailer, $this);
    }

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
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'lead';
    }

    /**
     * @return array Правила валидации
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['name, phone, sourceId, townId, town', 'required', 'message' => 'Поле {attribute} должно быть заполнено'],
            ['sourceId, townId, newTownId, questionId, leadStatus, addedById, type, campaignId, brakReason, buyerId', 'numerical', 'integerOnly' => true],
            ['question', 'required', 'message' => 'Поле {attribute} не заполнено', 'except' => ['createCall']],
            ['price, buyPrice, regionId, testMode', 'numerical'],
            ['deliveryTime, categoriesId', 'safe'],
            ['question', 'safe', 'on' => ['createCall']],
            ['agree', 'compare', 'compareValue' => 1, 'on' => ['create', 'createCall'], 'message' => 'Вы должны согласиться на обработку персональных данных'],
            ['name, phone, email, secretCode, brakComment', 'length', 'max' => 255],
            ['townId', 'match', 'not' => true, 'pattern' => '/^0$/', 'message' => 'Поле Город не заполнено'],
            ['name', 'match', 'pattern' => '/^([а-яА-Я0-9ёЁ_a-zA-Z\-., ])+$/u', 'message' => 'В имени могут присутствовать буквы, цифры, точка, дефис и пробел', 'except' => 'parsing, brak, update'],
            ['phone', 'match', 'pattern' => '/^([0-9]{11})+$/u', 'message' => 'В номере телефона могут присутствовать только цифры'],
            ['email', 'email', 'message' => 'E-mail похож на ненастоящий, проверьте, пожалуйста, правильность набора'],
            ['date1, date2', 'match', 'pattern' => '/^([0-9\-])+$/u', 'message' => 'В датах могут присутствовать только цифры'],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['id, name, phone, sourceId, question, question_date, townId, leadStatus', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array Связи с другими моделями
     */
    public function relations():array
    {
        return [
            'source' => [self::BELONGS_TO, Leadsource::class, 'sourceId'],
            'town' => [self::BELONGS_TO, Town::class, 'townId'],
            'campaign' => [self::BELONGS_TO, Campaign::class, 'campaignId'],
            'questionObject' => [self::BELONGS_TO, Question::class, 'questionId'],
            'categories' => [self::MANY_MANY, QuestionCategory::class, '{{lead2category}}(leadId, cId)'],
            'buyer' => [self::BELONGS_TO, User::class, 'buyerId'],
        ];
    }

    /**
     * @return array Наименования атрибутов (name=>label)
     */
    public function attributeLabels()
    {
        return [
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
        ];
    }

    /**
     * @param ApiClassFactory $apiClassFactory
     *
     * @return Lead
     */
    public function setApiClassFactory(ApiClassFactory $apiClassFactory): Lead
    {
        $this->apiClassFactory = $apiClassFactory;

        return $this;
    }

    /**
     * Возвращает массив, ключами которого являются коды статусов, а значениями - названия статусов.
     *
     * @return array Массив статусов (код статуса => название)
     */
    public static function getLeadStatusesArray()
    {
        return [
            self::LEAD_STATUS_DEFAULT => 'не обработан',
            self::LEAD_STATUS_SENT_CRM => 'в CRM',
            self::LEAD_STATUS_SENT_LEADIA => 'в Leadia',
            self::LEAD_STATUS_SENT => 'выкуплен',
            self::LEAD_STATUS_NABRAK => 'на отбраковке',
            self::LEAD_STATUS_BRAK => 'брак',
            self::LEAD_STATUS_RETURN => 'не принят к отбраковке',
            self::LEAD_STATUS_DUPLICATE => 'дубль',
            self::LEAD_STATUS_PREMODERATION => 'недолид',
        ];
    }

    /**
     * Возвращает название статуса объекта.
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
     * возвращает массив, ключами которого являются коды типов, а значениями - названия.
     *
     * @return array Массив типов лидов (код => название)
     */
    public static function getLeadTypesArray()
    {
        return [
            self::TYPE_QUESTION => 'вопрос',
            self::TYPE_CALL => 'запрос звонка',
            self::TYPE_DOCS => 'заказ документов',
            self::TYPE_YURIST => 'поиск юриста',
            self::TYPE_INCOMING_CALL => 'входящий звонок',
            self::TYPE_SERVICES => 'юридические услуги',
        ];
    }

    /**
     * Возвращает название типа лида.
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
     * возвращает массив, ключами которого являются коды причин отбраковки, а значениями - названия.
     *
     * @return array массив причин отбраковки (код => наименование)
     */
    public static function getBrakReasonsArray()
    {
        return [
            self::BRAK_REASON_BAD_QUESTION => 'не юридический вопрос',
            self::BRAK_REASON_BAD_NUMBER => 'неверный номер',
            self::BRAK_REASON_BAD_REGION => 'не тот регион',
            self::BRAK_REASON_SPAM => 'спам',
        ];
    }

    /**
     * Возвращает причину отбраковки для лида.
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
     * @return YurcrmClient
     */
    public function getYurcrmClient(): YurcrmClient
    {
        return $this->yurcrmClient;
    }

    /**
     * @param YurcrmClient $yurcrmClient
     *
     * @return Lead
     */
    public function setYurcrmClient(YurcrmClient $yurcrmClient): Lead
    {
        $this->yurcrmClient = $yurcrmClient;

        return $this;
    }

    /**
     * @return LeadNotifier
     */
    public function getNotifier(): LeadNotifier
    {
        return $this->notifier;
    }

    /**
     * @param LeadNotifier $notifier
     */
    public function setNotifier(LeadNotifier $notifier): void
    {
        $this->notifier = $notifier;
    }

    /**
     * Проверяет на существование покупателя и кампанию.
     *
     * @param User|null $buyer id покупателя
     * @param Campaign|null $campaign id кампании
     *
     * @return bool | array Массив (User, App\models\Campaign) или false если оба объекта не найдены
     */
    private function checkBuyerAndCampaign(?User $buyer, ?Campaign $campaign)
    {
        if (null == $buyer && null == $campaign) {
            return false;
        }

        // если указана кампания, но не указан покупатель, берем покупателя из кампании
        if (null == $buyer) {
            $buyerId = $campaign->buyerId;
            $buyer = User::model()->findByPk($buyerId);
        }

        if (!$buyer) {
            return false;
        }

        return [
            'buyer' => $buyer,
            'campaign' => $campaign,
        ];
    }

    /**
     * Сохранение проданного лида и связанных данных.
     *
     * @param User $buyer
     * @param TransactionCampaign|null $transaction
     * @param Campaign $campaign
     *
     * @return int Код результата сохранения
     */
    private function saveSoldLead($buyer, $transaction, $campaign)
    {
        $leadSentToPartner = null;

        if (!$this->save()) {
            $leadSaved = false;
            Yii::log('Не удалось сохранить лид ' . $this->id, 'error', 'system.web.CCommand');
            if (YII_DEBUG === true) {
                StringHelper::printr($this->errors);
            }
        } else {
            $leadSaved = true;
        }

        // сохранение покупателя. Важно сохранить его ДО сохранения транзакции, чтобы записалось время последней транзакции в пользователе
        if (!$buyer->save(false)) {
            $buyerSaved = false;
            Yii::log('Не удалось сохранить покупателя при продаже лида ' . $buyer->id, 'error', 'system.web.CCommand');
        } else {
            $buyerSaved = true;
        }

        // сохранение транзакции за лид
        if (isset($transaction) && !is_null($transaction) && !$transaction->save()) {
            $transactionSaved = false;
            Yii::log('Не удалось сохранить транзакцию за продажу лида ' . $this->id, 'error', 'system.web.CCommand');
        } else {
            $transactionSaved = true;
        }

        if ($campaign && Campaign::TYPE_PARTNERS == $campaign->type && 1 == $campaign->sendToApi && class_exists($campaign->getFullApiClass())) {
            $apiClass = $this->apiClassFactory->getApiClass($campaign->apiClass);
            $leadSentToPartner = $apiClass->send($this);
        }

        if (false != $buyerSaved && false != $leadSaved && false != $transactionSaved && false !== $leadSentToPartner) {
            return self::SAVE_RESULT_CODE_OK;
        }

        if (false === $leadSentToPartner) {
            // если партнерское апи не приняло лид
            return self::SAVE_RESULT_CODE_PARTNER_REJECT;
        }

        return self::SAVE_RESULT_CODE_ERROR; // код ошибки по умолчанию
    }

    /**
     * Отправка лида в кампанию по почте.
     *
     * @param Campaign $campaign
     *
     * @return bool
     */
    private function sendToCampaignByMail(Campaign $campaign)
    {
        // Если определена кампания и в ней стоит настройка Отправлять лиды на почту
        if ($campaign && $campaign->sendEmail) {
            return $this->notifier->send($campaign);
        }

        return false;
    }

    /**
     * Продажа лида покупателю.
     *
     * @param User|null $buyer покупатель
     * @param Campaign|null $campaign кампания
     *
     * @return bool результат
     */
    public function sellLead(?User $buyer = null, ?Campaign $campaign = null)
    {
        // получаем объекты покупателя и кампании
        $buyerAndCampaignResult = $this->checkBuyerAndCampaign($buyer, $campaign);

        if (false === $buyerAndCampaignResult) {
            return false;
        }

        $campaign = $buyerAndCampaignResult['campaign'];
        $buyer = $buyerAndCampaignResult['buyer'];

        $leadPrice = ($campaign && $campaign->price) ? $campaign->price : (int)$this->calculatePrices()[1];

        if ($campaign && Campaign::TYPE_PARTNERS == $campaign->type) {
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
            $transaction->buyerId = $buyer->id;
            $transaction->campaignId = $campaign->id;
            $transaction->sum = -$leadPrice;
            $transaction->description = 'Покупка заявки #' . $this->id;
            $transaction->time = $transactionTime;
            $transaction->leadId = $this->id;
        } else {
            $transaction = null;
            $buyer->lastTransactionTime = $transactionTime;
        }

        $this->leadStatus = self::LEAD_STATUS_SENT;
        $this->buyerId = $buyer->id;
        $this->price = $leadPrice;
        $this->deliveryTime = $transactionTime;
        // записываем в лид кампанию
        $this->campaignId = $campaign->id;

        // Сохранение через транзакцию: если хоть один из компонентов не сохранился, отменяем операцию

        $dbTransaction = $this->dbConnection->beginTransaction();
        try {
            // сохранение лида
            $soldLeadResultCode = $this->saveSoldLead($buyer, $transaction, $campaign);

            if (self::SAVE_RESULT_CODE_OK === $soldLeadResultCode) {
                $dbTransaction->commit();
                // записываем в кампанию время отправки последнего лида
                Yii::app()->db->createCommand()->update('{{campaign}}', ['lastLeadTime' => date('Y-m-d H:i:s')], 'id=:id', [':id' => $campaign->id]);
            } else {
                // если что-то не сохранилось, откатываем транзакцию
                $dbTransaction->rollback();
            }

            // Если при отправке лида в партнерское API вернулась ошибка, помечаем лид как дубль
            if (self::SAVE_RESULT_CODE_PARTNER_REJECT == $soldLeadResultCode) {
                $this->leadStatus = self::LEAD_STATUS_DUPLICATE;
                $this->save();

                return false;
            }
        } catch (Exception $e) {
            $dbTransaction->rollback();
            throw $e;
        }

        /** @var YurcrmResponse $yurcrmResult */
        $yurcrmResult = $this->sendToYurCRM($buyer);

        if (self::SAVE_RESULT_CODE_OK === $soldLeadResultCode) {
            if (!is_null($yurcrmResult)) {
                // Если успешно отправили лид в Yurcrm, уведомляем об этом покупателя
                $yurcrmResultDecoded = json_decode($yurcrmResult->getResponse(), true);

                if (200 == (int)$yurcrmResultDecoded['status'] && isset($yurcrmResultDecoded['data']['id'])) {
                    $crmLeadId = (int)$yurcrmResultDecoded['data']['id'];
                    $this->notifier->sendYurcrmNotification($buyer, $crmLeadId);
                }

                LoggerFactory::getLogger('db')->log('Лид отправлен в Yurcrm. Код ответа: ' . $yurcrmResult->getHttpCode() . '. Ответ: ' . $yurcrmResult->getResponse(), 'Lead', $this->id);
            } else {
                $this->sendToCampaignByMail($campaign);
            }
            // Если лид был куплен у вебмастера, переведем ему деньги
            $this->payWebmaster();
            $this->logSoldLead($buyer, $campaign);

            return true;
        }

        return false;
    }

    /**
     * Запись в лог информации о проданном лиде.
     *
     * @param User $buyer
     * @param Campaign $campaign
     * @param int $saveResult код результата сохранения лида
     */
    private function logSoldLead(User $buyer, Campaign $campaign)
    {
        $logMessage = 'Лид #' . $this->id . ' продан ';
        if ($campaign) {
            $logMessage .= 'в кампанию #' . $campaign->id . '(' . Campaign::getCampaignNameById($campaign->id) . ')';
            if ($buyer) {
                $logMessage .= ': ' . $buyer->name;
            }
        } elseif (0 != $buyer && $buyer) {
            $logMessage .= 'покупателю #' . $buyer->id . ' (' . $buyer->getShortName() . ')';
        }

        LoggerFactory::getLogger('db')->log($logMessage, 'Lead', $this->id);
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions
     */
    public function search()
    {
        $criteria = new CDbCriteria();

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('t.phone', $this->phone, true);
        $criteria->compare('t.sourceId', $this->sourceId);
        $criteria->compare('t.question', $this->question, true);
        $criteria->compare('t.question_date', $this->question_date, true);
        $criteria->compare('t.townId', $this->townId);
        $criteria->compare('t.type', $this->type);
        $criteria->compare('t.leadStatus', $this->leadStatus);
        $criteria->compare('DATE(t.question_date)>', DateHelper::invertDate($this->date1));
        $criteria->compare('DATE(t.question_date)<', DateHelper::invertDate($this->date2));

        // если применялся поиск по региону
        if ($this->regionId) {
            $criteria->with = ['town' => ['condition' => 'town.regionId=' . $this->regionId], 'town.region'];
        } else {
            $criteria->with = ['town', 'town.region'];
        }

        $criteria->order = 't.id DESC';

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
    }

    /**
     * Возвращает количество лидов с определенным статусом
     *
     * @param int $status статус
     * @param bool $noCampaign считать ли лиды без кампании
     *
     * @return int количество лидов
     */
    public static function getStatusCounter($status, $noCampaign = true)
    {
        if ($noCampaign) {
            $condition = 'leadStatus=:status AND campaignId!=0';
        } else {
            $condition = 'leadStatus=:status';
        }
        $counterRow = Yii::app()->db->cache(60)->createCommand()
            ->select('COUNT(*) counter')
            ->from('{{lead}}')
            ->where($condition, [':status' => (int)$status])
            ->queryRow();
        $counter = $counterRow['counter'];

        return $counter;
    }

    /**
     * возвращает количество лидов с таким же номером телефона и городом, добавленных не более $timeframe секунд назад.
     *
     * @param int $timeframe временной интервал (сек.)
     *
     * @return int количество лидов
     */
    public function findDublicates($timeframe = 86400)
    {
        $dublicatesRow = Yii::app()->db->createCommand()
            ->select('COUNT(*) counter')
            ->from('{{lead}}')
            ->where('phone=:phone AND townId=:townId AND question_date>=NOW()-INTERVAL :timeframe SECOND', [':phone' => $this->phone, ':townId' => $this->townId, ':timeframe' => $timeframe])
            ->queryRow();

        return $dublicatesRow['counter'];
    }

    /**
     * Метод, вызываемый перед сохранением объекта.
     *
     * @return bool
     */
    protected function beforeSave()
    {
        // удаляем из номера телефона все нецифровые символы
        $this->phone = PhoneHelper::normalizePhone($this->phone);

        // создаем поле Секретный код, чтобы покупатель лида мог работать с ним, перейдя по ссылке из письма
        if ('' == $this->secretCode) {
            $this->secretCode = md5(time() . $this->phone . strlen($this->question) . mt_rand(100000, 999999));
        }

        if ($this->isNewRecord) {
            // проверка на дубли работает только для новых записей
            // если за последние 24 часа были лиды с таким же номером телефона, ставим лиду статус Дубль
            if ($this->findDublicates(86400) > 0) {
                $this->leadStatus = self::LEAD_STATUS_DUPLICATE;
            }
        }

        // при переводе лида в статус Брак из другого статуса удаляем у вебмастера транзакцию по этому лиду
        if (self::LEAD_STATUS_BRAK == $this->leadStatus) {
            $oldStatusRow = Yii::app()->db->createCommand()
                ->select('leadStatus')
                ->from('{{lead}}')
                ->where('id=:id', [':id' => $this->id])
                ->queryRow();
            // старый статус
            $oldStatus = $oldStatusRow['leadStatus'];

            if ($oldStatus !== $this->leadStatus) {
                $removeTransactionResult = Yii::app()->db->createCommand()
                    ->delete('{{partnerTransaction}}', 'leadId=:leadId', [':leadId' => $this->id]);
            }
        }

        return parent::beforeSave();
    }

    /**
     * Метод, автоматически вызываемый после сохранения лида.
     */
    protected function afterSave()
    {
        parent::afterSave();

        if (!$this->isNewRecord) {
            return;
        }

        if (Lead::LEAD_STATUS_DEFAULT != $this->leadStatus) {
            return;
        }

        LoggerFactory::getLogger('db')->log('Создан лид #' . $this->id . ', ' . $this->town->name, 'Lead', $this->id);

        if (true == Yii::app()->params['sellLeadAfterCreating']) {
            $this->findCampaignAndSell();
        }
    }

    protected function findCampaignAndSell()
    {
        // после сохранения лида ищем для него кампанию
        $campaignId = Campaign::getCampaignsForLead($this->id);
        $campaign = Campaign::model()->findByPk($campaignId);

        // если кампания найдена, отправляем в нее лид
        if ($campaign instanceof Campaign) {
            // установим свойство isNewRecord = false, чтобы обновить, а не создать копию лида при продаже
            $this->setIsNewRecord(false);
            $this->sellLead(null, $campaign);
        }
    }

    /**
     * Возвращает статистику проданных лидов для покупателя или кампании.
     */
    public static function getStatsByPeriod($dateFrom, $dateTo, $buyerId = 0, $campaignId = 0)
    {
        // Нужно обязательно указать либо покупателя, либо кампанию
        if (0 === $buyerId && 0 === $campaignId) {
            return false;
        }

        $leadsCommand = Yii::app()->db->createCommand()
            ->select('id, price, DATE(deliveryTime) date')
            ->from('{{lead}}')
            ->order('date')
            ->where('DATE(deliveryTime) >= :dateFrom AND DATE(deliveryTime) <= :dateTo AND leadStatus IN (:status1, :status2, :status3)', [':dateFrom' => $dateFrom, ':dateTo' => $dateTo, ':status1' => self::LEAD_STATUS_SENT, ':status2' => self::LEAD_STATUS_RETURN, ':status3' => self::LEAD_STATUS_NABRAK]);

        // если выборка по покупателю, найдем лиды, проданные ему или в его кампании
        if ($buyerId) {
            $buyer = User::model()->with('campaigns')->findByPk($buyerId);
            $campaignsIds = [];
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
            $leadsCommand->andWhere('campaignId = :campaignId', [':campaignId' => (int)$campaignId]);
        }

        $leadsRows = $leadsCommand->queryAll();
        $leads = [];

        foreach ($leadsRows as $row) {
            ++$leads['dates'][$row['date']]['count'];
            $leads['dates'][$row['date']]['sum'] += $row['price'];
            ++$leads['total'];
            $leads['sum'] += $row['price'];
        }

        return $leads;
    }

    /**
     * Вычисляет базовые цены покупки и продажи для лида.
     *
     * @return array Массив с двумя ценами [0 => цена покупки, 1 => цена продажи]
     */
    public function calculatePrices()
    {
        // Все цены в копейках
        $regionBuyPrice = 2000;
        $townBuyPrice = 0;

        $town = $this->town;
        if ($town) {
            $region = $this->town->region;
            $townBuyPrice = $town->buyPrice;
        }

        if ($region) {
            $regionBuyPrice = $region->buyPrice;
        }

        // цена города приоритетнее цены региона

        if (0 == $townBuyPrice) {
            $townBuyPrice = $regionBuyPrice;
        }

        $townSellPrice = $townBuyPrice * Yii::app()->params['priceCoeff'];

        return [0 => $townBuyPrice, 1 => $townSellPrice];
    }

    /**
     * Создает транзакцию оплаты вебмастеру, приславшему нам лид.
     *
     * @return bool
     */
    protected function payWebmaster()
    {
        if ($this->source && $this->source->user && $this->buyPrice > 0) {
            $sourceUser = $this->source->user;

            // запишем транзакцию за лид
            $partnerTransaction = new PartnerTransaction();
            $partnerTransaction->sum = ($sourceUser->id == Yii::app()->params['webmaster100yuristovId'])
                ? 0
                : $this->buyPrice;
            $partnerTransaction->leadId = $this->id;
            $partnerTransaction->sourceId = $this->sourceId;
            $partnerTransaction->partnerId = $sourceUser->id;
            $partnerTransaction->comment = 'Начисление за лид #' . $this->id;
            if (!$partnerTransaction->save()) {
                Yii::log('Не удалось сохранить транзакцию за покупку лида ' . $this->id . ' ' . print_r($partnerTransaction->errors), 'error', 'system.web.CCommand');
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

        return (0 == $source->moderation) ? self::LEAD_STATUS_DEFAULT : self::LEAD_STATUS_PREMODERATION;
    }

    /**
     * Отправка лида в Yurcrm.
     *
     * @param User $buyer покупатель
     *
     * @return YurcrmResponse|null Ответ от CRM
     *
     * @throws Exception
     */
    private function sendToYurCRM($buyer)
    {
        if ('' == $buyer->yurcrmToken || 0 == $buyer->yurcrmSource) {
            return null;
        }

        $yurcrmClient = $buyer->getYurcrmClient();

        if (!($buyer->getYurcrmClient() instanceof YurcrmClient)) {
            throw new \Exception('YurCRM client is not initialized');
        }

        $yurcrmClient->setRoute('contact/create')
            ->setData([
                'contact[name]' => $this->name,
                'contact[sourceId]' => $buyer->yurcrmSource,
                'contact[phone]' => $this->phone,
                'contact[question]' => $this->question,
                'contact[email]' => $this->email,
                'contact[townId]' => $this->townId,
                'contact[externalId]' => $this->id,
            ]);

        $createLeadResult = $yurcrmClient->send();

        return $createLeadResult;
    }
}
