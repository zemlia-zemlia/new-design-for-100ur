<?php

/**
 * Модель для работы с лидами 100 юристов
 *
 * Поля из таблицы '{{lead100}}':
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
 * 
 * @author Michael Krutikov m@mkrutikov.pro
 */
class Lead100 extends CActiveRecord
{
	public $date1, $date2; // диапазон дат, используемый при поиске
        public $newTownId; // для случая смены города при отбраковке
        
        // статусы лидов
        const LEAD_STATUS_DEFAULT = 0; // лид никуда не отправлен
        const LEAD_STATUS_SENT_CRM = 1; // лид отправлен в CRM
        const LEAD_STATUS_SENT_LEADIA = 2; // лид отправлен в Leadia
        const LEAD_STATUS_NABRAK = 3; // на отбраковке
        const LEAD_STATUS_BRAK = 4; // брак
        const LEAD_STATUS_RETURN = 5; // возврат с отбраковки
        const LEAD_STATUS_SENT = 6; // отправлен покупателю
        
        
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
        
        /*
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Lead100 the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{lead100}}';
	}

	/**
	 * @return array Правила валидации
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, phone, sourceId, question, townId', 'required','message'=>'Поле должно быть заполнено'),
			array('sourceId, townId, newTownId, questionId, leadStatus, addedById, type, campaignId, brakReason', 'numerical', 'integerOnly'=>true),
			array('price, buyPrice', 'numerical'),
                        array('deliveryTime', 'safe'),
                        array('name, phone, email, secretCode, brakComment', 'length', 'max'=>255),
			array('townId', 'match','not'=>true, 'pattern'=>'/^0$/', 'message'=>'Поле Город не заполнено'),
                        array('name','match','pattern'=>'/^([а-яa-zА-ЯA-Z0-9ёЁ\-., ])+$/u', 'message'=>'В имени могут присутствовать буквы, цифры, точка, дефис и пробел', 'except'=>'parsing'),
                        array('phone','match','pattern'=>'/^([а-яa-zА-ЯA-Z0-9ёЁ\+\(\)\s \-])+$/u', 'message'=>'В номере телефона могут присутствовать только цифры и знак плюса'),
                        array('email', 'email', 'message'=>'E-mail похож на ненастоящий, проверьте, пожалуйста, правильность набора'),
                        array('date1, date2','match','pattern'=>'/^([0-9\-])+$/u', 'message'=>'В датах могут присутствовать только цифры и знак плюса'),
			
                        // The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, phone, sourceId, question, question_date, townId, leadStatus', 'safe', 'on'=>'search'),
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
                    'source'        =>  array(self::BELONGS_TO, 'Leadsource100', 'sourceId'),
                    'town'          =>  array(self::BELONGS_TO, 'Town', 'townId'),
                    'campaign'      =>  array(self::BELONGS_TO, 'Campaign', 'campaignId'),
		);
	}

	/**
	 * @return array Наименования атрибутов (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'            => 'ID',
			'name'          => 'Имя',
			'phone'         => 'Телефон',
			'email'         => 'Email',
			'leadStatus'    => 'Статус',
			'source'        => 'Источник',
                        'sourceId'      => 'Источник',
			'question'      => 'Вопрос',
			'question_date' => 'Дата первого обращения',
                        'townId'        => 'ID города',
                        'town'          => 'Город',
                        'questionId'    => 'ID связанного вопроса',
                        'type'          => 'Тип',
                        'deliveryTime'  =>  'Время отправки покупателю',
                        'price'         =>  'Цена',
                        'campaignId'    =>  'ID кампании',
                        'lastLeadTime'  =>  'Время отправки последнего лида',
                        'secretCode'    =>  'Секретный код',
                        'brakComment'   =>  'Комментарий отбраковки',
                        'brakReason'    =>  'Причина отбраковки',
                        'buyPrice'      =>  'Цена покупки',
                        'date1'         =>  'От',
                        'date2'         =>  'До',
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
                self::LEAD_STATUS_DEFAULT           =>  'не обработан',
                self::LEAD_STATUS_SENT_CRM          =>  'в CRM',
                self::LEAD_STATUS_SENT_LEADIA       =>  'в Leadia',
                self::LEAD_STATUS_SENT              =>  'выкуплен',
                self::LEAD_STATUS_NABRAK            =>  'на отбраковке',
                self::LEAD_STATUS_BRAK              =>  'брак',
                self::LEAD_STATUS_RETURN            =>  'не принят к отбраковке',
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
                self::TYPE_QUESTION     =>  'вопрос',
                self::TYPE_CALL         =>  'запрос звонка',
                self::TYPE_DOCS         =>  'заказ документов',
                self::TYPE_YURIST       =>  'поиск юриста',
                self::TYPE_INCOMING_CALL   =>  'входящий звонок',
                self::TYPE_SERVICES     =>  'юридические услуги',
                
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
                self::BRAK_REASON_BAD_QUESTION  =>  'не юридический вопрос',
                self::BRAK_REASON_BAD_NUMBER    =>  'неверный номер',
                self::BRAK_REASON_BAD_REGION    =>  'не тот регион',
                self::BRAK_REASON_SPAM          =>  'спам',
                
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
         * Отправляет лид в кампанию с заданным id
         * 
         * @param int $campaignId id кампании
         * @return boolean true - удалось отправить лид, false - не удалось
         */
        public function sendToCampaign($campaignId)
        {
            $campaign = Campaign::model()->findByPk($campaignId);
            
            if(!$campaign) {
                return false;
            }
                      
            
            $this->price = $campaign->price;
            $this->deliveryTime = date('Y-m-d H:i:s');
            $this->campaignId = $campaign->id;
            
            
            // отправляем покупателям
            $this->leadStatus = self::LEAD_STATUS_SENT;

            
            
            // списываем средства с баланса
            if($campaign->balance < $this->price) {
                // на балансе кампании недостаточно средств
                return false;
            } else {

                $campaign->balance -= $this->price;

            }

            // записываем данные о снятии средств со счета кампании
            $transaction = new TransactionCampaign;
            $transaction->sum = -$this->price;
            $transaction->campaignId = $campaign->id;
            $transaction->description = 'Списание за лид ID=' . $this->id;
            
            // пытаемся отправить лид по почте
            if($campaign->sendEmail) {
                if($this->sendByEmail($campaign->id)) {
                    if($this->save()){
                        $campaign->save();
                        if(!$transaction->save()){
                            Yii::log("Не удалось сохранить транзакцию за продажу лида " . $this->id, 'error', 'system.web.CCommand');
                            //CustomFuncs::printr($transaction->errors);
                        }

                        return true;
                    } else {
                        Yii::log("Не удалось сохранить лид " . $this->id . " при продаже", 'error', 'system.web.CCommand');
                        return false;
                    }
                } else {
                    // не удалось отправить письмо
                    Yii::log("Не удалось отправить письмо покупателю лида " . $this->id, 'error', 'system.web.CCommand');
                    return false;
                }
            }
                

            
        }

        /**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('sourceId',$this->sourceId);
		$criteria->compare('question',$this->question,true);
		$criteria->compare('question_date',$this->question_date,true);
		$criteria->compare('townId',$this->townId);
		$criteria->compare('leadStatus',$this->leadStatus);
                $criteria->compare('DATE(t.question_date)>',  CustomFuncs::invertDate($this->date1));
                $criteria->compare('DATE(t.question_date)<',  CustomFuncs::invertDate($this->date2));
                
                $criteria->order = 'id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
            if($campaignId) {
                $campaign = Campaign::model()->with('buyer')->findByPk($campaignId);
            }
            
            $mailer = new GTMail();
            $mailer->subject = "Заявка город " . $this->town->name . " (" . $this->town->region->name . ")";
            $mailer->message = "<h3>Заявка на консультацию</h3>";
            $mailer->message .= "<p>Имя: " . CHtml::encode($this->name) . ",</p>";
            $mailer->message .= "<p>Город: " . CHtml::encode($this->town->name). " (" . $this->town->region->name . ")" . "</p>";
            $mailer->message .= "<p>Телефон: " . $this->phone . "</p>";
            $mailer->message .= "<p>Сообщение:<br />" . CHtml::encode($this->question) . "</p>"; 
            
            $mailer->message .= "<hr /><p>"
                    . "<a style='display:inline-block; padding:5px 10px; border:#999 1px solid; color:#666; background-color:#fff; text-decoration:none;' href='https://100yuristov.com/site/brakLead/?code=" . $this->secretCode . "'>Отбраковка</a>"
                    . "</p>";
            
            $mailer->email = $campaign->buyer->email;
            
            if($mailer->sendMail()) {
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
            if($noCampaign) {
                $condition = "leadStatus=:status AND campaignId!=0";
            } else {
                $condition = "leadStatus=:status";
            }
            $counterRow =Yii::app()->db->cache(60)->createCommand()
                    ->select('COUNT(*) counter')
                    ->from("{{lead100}}")
                    ->where($condition, array(":status"=>(int)$status))
                    ->queryRow();
            $counter = $counterRow['counter'];
            return $counter;
        }
        
        /**
         * возвращает количество лидов с таким же номером телефона, добавленных не более $timeframe секунд назад
         * 
         * @param int $timeframe временной интеркал (сек.)
         * @return int количество лидов 
         */
        public function findDublicates($timeframe = 600)
        {
            $dublicatesRow = Yii::app()->db->createCommand()
                    ->select("COUNT(*) counter")
                    ->from("{{lead100}}")
                    ->where("phone=:phone AND question_date>=NOW()-INTERVAL :timeframe SECOND", array(":phone"=>$this->phone, ":timeframe"=>$timeframe))
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
            $this->phone = Question::normalizePhone($this->phone);
            if($this->secretCode == '') {
                $this->secretCode = md5(time().$this->phone.strlen($this->question).mt_rand(100000,999999));
            }
            return parent::beforeSave();
        }
}