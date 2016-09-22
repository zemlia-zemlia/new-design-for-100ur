<?php

/**
 * This is the model class for table "{{lead}}".
 *
 * The followings are the available columns in table '{{lead}}':
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
 */
class Lead extends CActiveRecord
{
	
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
        
        // причины отбраковки
        const BRAK_REASON_BAD_QUESTION = 1;
        const BRAK_REASON_BAD_NUMBER = 2;
        const BRAK_REASON_BAD_REGION = 3;
        const BRAK_REASON_SPAM = 4;
        
        // не юридический вопрос
        // неверный номер
        // не тот регион
        // спам
        
        
        /*
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Lead the static model class
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
		return '{{lead}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, phone, sourceId, question, townId', 'required','message'=>'Поле должно быть заполнено'),
			array('sourceId, townId, questionId, leadStatus, addedById, type, campaignId, brakReason', 'numerical', 'integerOnly'=>true),
			array('price', 'numerical'),
                        array('deliveryTime', 'safe'),
                        array('name, phone, email', 'length', 'max'=>255),
                        array('email', 'required', 'on'=>'create'),
			array('townId', 'match','not'=>true, 'pattern'=>'/^0$/', 'message'=>'Поле Город не заполнено'),
                        array('name','match','pattern'=>'/^([а-яa-zА-ЯA-Z0-9ёЁ\-., ])+$/u', 'message'=>'В имени могут присутствовать буквы, цифры, точка, дефис и пробел', 'except'=>'parsing'),
                        array('phone','match','pattern'=>'/^([а-яa-zА-ЯA-Z0-9ёЁ\+\(\)\s \-])+$/u', 'message'=>'В номере телефона могут присутствовать только цифры и знак плюса'),
                        array('email', 'email', 'message'=>'E-mail похож на ненастоящий, проверьте, пожалуйста, правильность набора'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, phone, sourceId, question, question_date, townId, leadStatus', 'safe', 'on'=>'search'),
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
                    'source'        =>  array(self::BELONGS_TO, 'Leadsource', 'sourceId'),
                    'town'          =>  array(self::BELONGS_TO, 'Town', 'townId'),
                    'campaign'      =>  array(self::BELONGS_TO, 'Campaign', 'campaignId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
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
		);
	}
        
        
        // возвращает массив, ключами которого являются коды статусов, а значениями - названия статусов
        static public function getLeadStatusesArray()
        {
            return array(
                self::LEAD_STATUS_DEFAULT           =>  'не обработан',
                self::LEAD_STATUS_SENT_CRM          =>  'в CRM',
                self::LEAD_STATUS_SENT_LEADIA       =>  'в Leadia',
                self::LEAD_STATUS_SENT              =>  'отправлен',
                self::LEAD_STATUS_NABRAK            =>  'на отбраковку',
                self::LEAD_STATUS_BRAK              =>  'брак',
                self::LEAD_STATUS_RETURN            =>  'возврат',
            );
        }
        
        public function getLeadStatusName()
        {
            $statusesArray = self::getLeadStatusesArray();
            $statusName = $statusesArray[$this->leadStatus];
            return $statusName;
        }
        
        
        // возвращает массив, ключами которого являются коды типов, а значениями - названия
        static public function getLeadTypesArray()
        {
            return array(
                self::TYPE_QUESTION     =>  'вопрос',
                self::TYPE_CALL         =>  'запрос звонка',
                self::TYPE_DOCS         =>  'заказ документов',
                self::TYPE_YURIST       =>  'поиск юриста',
                self::TYPE_INCOMING_CALL   =>  'входящий звонок',
                
            );
        }
        
        public function getLeadTypeName()
        {
            $typesArray = self::getLeadTypesArray();
            $typeName = $typesArray[$this->type];
            return $statusName;
        }
        
        // возвращает массив, ключами которого являются коды типов, а значениями - названия
        static public function getBrakReasonsArray()
        {
            return array(
                self::BRAK_REASON_BAD_QUESTION  =>  'не юридический вопрос',
                self::BRAK_REASON_BAD_NUMBER    =>  'неверный номер',
                self::BRAK_REASON_BAD_REGION    =>  'не тот регион',
                self::BRAK_REASON_SPAM          =>  'спам',
                
            );
        }
        
        public function getReasonName()
        {
            $reasonsArray = self::getBrakReasonsArray();
            $reasonName = $reasonsArray[$this->brakReason];
            return $reasonName;
        }
        
        
        
        // УСТАРЕВШАЯ ФУНКЦИЯ
        // отправляет лид в Lidea
        public function sendToLeadia($testMode = false)
        {
            $leadData = array();
            $leadiaUrl = "http://cloud1.leadia.ru/lead.php";
            
            $leadData['form_page'] = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            $leadData['referer'] = $_SERVER['HTTP_REFERER'];
            $leadData['client_ip'] = $_SERVER['REMOTE_ADDR'];
            $leadData['userid'] = '5702';
            $leadData['product'] = 'lawyer';
            $leadData['template'] = 'default';
            $leadData['key'] = '';
            $leadData['first_last_name'] = ($testMode == false)? CHtml::encode($this->name):"тест";
            $leadData['phone'] = $this->phone;
            $leadData['email'] = $this->email;
            $leadData['region'] = $this->town->name;
            $leadData['question'] = CHtml::encode($this->question);
            $leadData['subaccount'] = '';
            
            
            //url-ify the data for the POST
            foreach($leadData as $key=>$value) { 
                $fields_string .= $key.'='.$value.'&'; 
            }
            rtrim($fields_string, '&');
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $leadiaUrl);
            curl_setopt($ch,CURLOPT_POST, count($leadData));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);
            
            return true;
        }
        
         // УСТАРЕВШАЯ ФУНКЦИЯ
         // отправляет лид в офис или в лид-сервис
        public function sendLead()
        {
            // регионы, за которые платит Leadia
            $leadiaRegions = Yii::app()->params['leadiaRegions'];
            
            // проверяем, попадает ли регион лида в список регионов Leadea
            if(in_array($this->town->ocrug, $leadiaRegions)) {
                //echo "Отправляем лид " . $this->id . " в Leadia..<br />";
                if($this->sendToLeadia()) {
                    $this->leadStatus = self::LEAD_STATUS_SENT_LEADIA;
                    if($this->save()) {
                        return true;
                    } else {
                        //CustomFuncs::printr($this->errors);
                        //Yii::log('Не удалось сохранить лид id=' . $this->id, 'error', 'application.models.lead');
                        return false;
                    }
                } else {
                    $this->leadStatus = self::LEAD_STATUS_ERROR;
                    if($this->save()) {
                        return true;
                    } else {
                        return false;
                    }
                }
            } elseif($this->town->ocrug == 'Московская область') {
                $this->leadStatus = self::LEAD_STATUS_SENT_CRM;
                $contact = new Contact;
                $contact->name = trim($this->name);
                $this->name = trim($this->name);
                $contact->phone = Contact::getValidPhoneStatic($this->phone);
                $contact->email = $this->email;
                $contact->sourceId = $this->sourceId;
                $contact->question = trim($this->question);
                $contact->question_date = $this->question_date;
                $contact->townId = $this->townId;
                $contact->addedById = $this->addedById;
                
                $contact->officeId = 1; // Павелецкая
                if($contact->save()){
                    $this->contactId = $contact->id;
                    $this->save();
                    return true;
                } else {
                    echo "Не удалось сохранить лид " . $this->id . '<br />';
                    CustomFuncs::printr($contact->errors);
                    return false;
                } 
            }
        }
        
        
        // отправляет лид в кампанию
        public function sendToCampaign($campaignId)
        {
            $campaign = Campaign::model()->findByPk($campaignId);
            
            if(!$campaign) {
                return false;
            }
            
            // определим, является ли источником лида источник, который мы не продаем
           
            $isFreeSource = (in_array($this->sourceId, Yii::app()->params['freeSources']))?true:false;
            
            $this->price = $campaign->price;
            $this->deliveryTime = date('Y-m-d H:i:s');
            $this->campaignId = $campaign->id;
            
            // если лид нужно отправить в CRM KC ZAKON
            if(in_array($campaign->id, Yii::app()->params['kc_zakon_campaigns'])) {
                $this->leadStatus = self::LEAD_STATUS_SENT_CRM;
                $contact = new Contact;
                $contact->name = $this->name;
                $contact->phone = Contact::getValidPhoneStatic($this->phone);
                $contact->email = $this->email;
                $contact->sourceId = $this->sourceId;
                $contact->question = $this->question;
                $contact->question_date = $this->question_date;
                $contact->townId = $this->townId;
                $contact->addedById = $this->addedById;
                
                $contact->officeId = 1; // Павелецкая
                if($contact->save()){
                    $this->contactId = $contact->id;
                } else {
                    echo "Не удалось сохранить контакт " . $contact->id . '<br />';
                    CustomFuncs::printr($contact->errors);
                    return false;
                } 
            } else {
                // не отправили лид в CRM, отправляем покупателям
                $this->leadStatus = self::LEAD_STATUS_SENT;
            }
            
            
            // НЕ списываем средства с баланса, если лид с бесплатных источников и отправляется в кампании КЦ
            if(!($isFreeSource == true && in_array($campaign->id, Yii::app()->params['kc_zakon_campaigns']))) {
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
            }
            
            if($this->save()){
                $campaign->save();
                if(!$transaction->save()){
                    CustomFuncs::printr($transaction->errors);
                }
                if($campaign->sendEmail) {
                    $this->sendByEmail($campaign->id);
                }
                return true;
            } else {
                return false;
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function sendByEmail($campaignId = 0)
        {
            if($campaignId) {
                $campaign = Campaign::model()->with('buyer')->findByPk($campaignId);
            }
            
            $mailer = new GTMail();
            $mailer->subject = "Заявка город " . $this->town->name . " (" . $this->town->region->name . ")";
            $mailer->message = "<h3>Заявка на консультацию</h3>";
            $mailer->message .= "<p>Имя: " . CHtml::encode($this->name) . ", город: " . CHtml::encode($this->town->name). " (" . $this->town->region->name . ")" . "</p>";
            $mailer->message .= "<p>Телефон: " . $this->phone . "</p>";
            $mailer->message .= "<p>Сообщение:<br />" . CHtml::encode($this->question) . "</p>";          
            
            $mailer->email = $campaign->buyer->email;
            
            if($mailer->sendMail()) {
                return true;
            } else {
                return false;
            }
        }
        
        public static function getStatusCounter($status, $noCampaign = true)
        {
            if($noCampaign) {
                $condition = "leadStatus=:status AND campaignId!=0";
            } else {
                $condition = "leadStatus=:status";
            }
            $counterRow =Yii::app()->db->cache(60)->createCommand()
                    ->select('COUNT(*) counter')
                    ->from("{{lead}}")
                    ->where($condition, array(":status"=>(int)$status))
                    ->queryRow();
            $counter = $counterRow['counter'];
            return $counter;
        }
        
        // возвращает количество лидов с таким же номером телефона, добавленных не более $timeframe секунд назад
        public function findDublicates($timeframe = 600)
        {
            $dublicatesRow = Yii::app()->db->createCommand()
                    ->select("COUNT(*) counter")
                    ->from("{{lead}}")
                    ->where("phone=:phone AND question_date>=NOW()-INTERVAL :timeframe SECOND", array(":phone"=>$this->phone, ":timeframe"=>$timeframe))
                    ->queryRow();
            
            //CustomFuncs::printr($dublicatesRow['counter']);
            
            return $dublicatesRow['counter'];
        }
}