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
 */
class Lead extends CActiveRecord
{
	
        const LEAD_STATUS_DEFAULT = 0; // лид никуда не отправлен
        const LEAD_STATUS_SENT_CRM = 1; // лид отправлен в CRM
        const LEAD_STATUS_SENT_LEADIA = 2; // лид отправлен в Leadia
        
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
			array('name, phone, sourceId, question', 'required','message'=>'Поле {attribute} должно быть заполнено'),
			array('sourceId, townId, questionId, leadStatus, addedById', 'numerical', 'integerOnly'=>true),
			array('name, phone, email', 'length', 'max'=>255),
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
			'status'        => 'Статус',
			'source'        => 'Источник',
                        'sourceId'      => 'Источник',
			'question'      => 'Вопрос',
			'question_date' => 'Дата первого обращения',
                        'townId'        => 'ID города',
                        'town'          => 'Город',
                        'questionId'    => 'ID связанного вопроса',
		);
	}
        
        
        // возвращает массив, ключами которого являются коды статусов, а значениями - названия статусов
        static public function getLeadStatusesArray()
        {
            return array(
                self::LEAD_STATUS_DEFAULT           =>  'не обработан',
                self::LEAD_STATUS_SENT_CRM          =>  'в CRM',
                self::LEAD_STATUS_SENT_LEADIA       =>  'в Leadia',
            );
        }
        
        public function getLeadStatusName()
        {
            $statusesArray = self::getLeadStatusesArray();
            $statusName = $statusesArray[$this->leadStatus];
            return $statusName;
        }
        
        
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
        
        public function sendByEmail()
        {
            $mailer = new GTMail();
            $mailer->subject = "Заявка город " . $this->town->name . " (" . $this->town->ocrug . ")";
            $mailer->message = "<h3>Заявка на консультацию</h3>";
            $mailer->message .= "<p>Имя: " . CHtml::encode($this->name) . ", город: " . CHtml::encode($this->town->name). " (" . $this->town->ocrug . ")" . "</p>";
            $mailer->message .= "<p>Телефон: " . $this->phone . "</p>";
            $mailer->message .= "<p>Сообщение:<br />" . CHtml::encode($this->question) . "</p>";
            
            $mailer->message .= "<p><strong>Квалификация брака:</strong><br />
                Отправьте в теме ответа на письмо с лидом номер для квалификации брака:<br />
                1 = Другой регион.<br />
                2 = Повтор заявки.<br />
                3 = Неверный номер.<br />
                4 = Спам.</p>";
           

            $mailer->email = Yii::app()->params['leadsEmail'];
            
            if($mailer->sendMail()) {
                return true;
            } else {
                return false;
            }
        }
}