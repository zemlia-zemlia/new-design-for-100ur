<?php

/**
 * This is the model class for table "{{contact}}".
 *
 * The followings are the available columns in table '{{contact}}':
 * @property string $id
 * @property string $name
 * @property integer $employeeId
 * @property string $phone
 * @property string $email
 * @property integer $status
 * @property integer $sourceId
 * @property string $question
 * @property string $question_date
 * @property integer $townId
 * @property integer $questionId
 * @property integer $addedById
 * @property integer $officeId
 * @property integer $leadStatus
 */
class Contact extends CActiveRecord
{
	
        const STATUS_LEAD = 0; // лид. нет договора, не пришел на встречу
        const STATUS_CLIENT = 1; // заключен договор
        const STATUS_ARCHIVE = 2; // в архиве
        const STATUS_BRAK = 3; // отбракован
        const STATUS_NABRAK = 4; // на отбраковку
        const STATUS_CONSULT = 5; // пришел на консультацию
        
        const LEAD_STATUS_DEFAULT = 0; // лид никуда не отправлен
        const LEAD_STATUS_SENT_CRM = 1; // лид отправлен в CRM
        const LEAD_STATUS_SENT_LEADIA = 2; // лид отправлен в Leadia
        
        public $date1, $date2; // диапазон дат, используемый при поиске

        /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Contact the static model class
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
		return '{{contact}}';
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
			array('employeeId, status, sourceId, townId, questionId, addedById, officeId', 'numerical', 'integerOnly'=>true),
			array('name, phone, email', 'length', 'max'=>255),
                        array('name','match','pattern'=>'/^([а-яa-zА-ЯA-Z0-9ёЁ\-. ])+$/u', 'message'=>'В имени могут присутствовать буквы, цифры, точка, дефис и пробел'),
                        array('phone','match','pattern'=>'/^([0-9\+\s])+$/u', 'message'=>'В номере телефона могут присутствовать только цифры и знак плюса'),
                        array('date1, date2','match','pattern'=>'/^([0-9\-])+$/u', 'message'=>'В датах могут присутствовать только цифры и знак плюса'),
                        array('email', 'email', 'message'=>'E-mail похож на ненастоящий, проверьте, пожалуйста, правильность набора'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, employeeId, phone, email, status, sourceId, question, question_date', 'safe', 'on'=>'search'),
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
                    'employee'      =>  array(self::BELONGS_TO, 'User', 'employeeId'),
                    'agreements'    =>  array(self::HAS_MANY, 'Agreement', 'contactId'),
                    'source'        =>  array(self::BELONGS_TO, 'Leadsource', 'sourceId'),
                    'town'          =>  array(self::BELONGS_TO, 'Town', 'townId'),
                    'office'        =>  array(self::BELONGS_TO, 'Office', 'officeId'),
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
			'employeeId'    => 'ID сотрудника',
                        'employee'      => 'Сотрудник',
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
                        'addedBy'       =>  'Кем добавлен',
                        'date1'         =>  'От',
                        'date2'         =>  'До',
                        'officeId'      =>  'Офис',
		);
	}

        // возвращает массив, ключами которого являются коды статусов, а значениями - названия статусов
        static public function getStatusesArray()
        {
            return array(
                self::STATUS_LEAD       =>  'Лид',
                self::STATUS_CLIENT     =>  'Клиент',
                self::STATUS_ARCHIVE    =>  'В архиве',
                self::STATUS_BRAK       =>  'Брак',
                self::STATUS_NABRAK     =>  'На отбраковку',
                self::STATUS_CONSULT    =>  'Пришел на консультацию',
            );
        }
        
        public function getStatusName()
        {
            $statusesArray = self::getStatusesArray();
            $statusName = $statusesArray[$this->status];
            return $statusName;
        }
        
        
        // возвращает массив, ключами которого являются коды статусов, а значениями - названия статусов
        static public function getLeadStatusesArray()
        {
            return array(
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
        
        
        // возвращает количество неразобранных контактов в офисе текущего пользователя
        public static function showNewContactsCount($cacheTime, $officeId = NULL)
        {
            if(!$officeId) {
                $officeId = Yii::app()->user->officeId;
            }
            $row = Yii::app()->db
                    ->cache((int)$cacheTime)
                    ->createCommand('SELECT COUNT(*) as counter FROM {{contact}} c  LEFT JOIN {{user}} u ON u.id=c.addedById WHERE c.status=0 AND c.employeeId=0 AND (u.role!='.User::ROLE_OPERATOR . ' OR c.addedById=0) AND c.officeId='.$officeId)
                    ->queryRow();
            return $row['counter'];
        }
        
        // возвращает количество контактов со статусом на отбраковку
        public static function showNaBrakContactsCount($cacheTime, $officeId = NULL)
        {
            if(!$officeId) {
                $officeId = Yii::app()->user->officeId;
            }
            $row = Yii::app()->db
                    ->cache((int)$cacheTime)
                    ->createCommand('SELECT COUNT(*) as counter FROM {{contact}} c WHERE c.status='.Contact::STATUS_NABRAK . '  AND c.officeId=' . $officeId)
                    ->queryRow();
            return $row['counter'];
        }
        
        // возвращает массив договоров, связанных с контактом, ключами которого являются id договоров, а значениями - их номера с датами
        public function getAgreementsIdsNames()
        {
            $agreements = $this->agreements;
            $agreementsArray = Array(0 => 'Не выбран');
            foreach($agreements as $agreement) {
                $agreementsArray[$agreement->id] = $agreement->number . '(' . $agreement->create_date . ')';
            }
            return $agreementsArray;
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

		$criteria->compare('t.id',$this->id,true);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('employeeId',$this->employeeId);
		$criteria->compare('t.phone',$this->phone,true);
		$criteria->compare('t.email',$this->email,true);
		$criteria->compare('t.status',$this->status);
                $criteria->compare('t.officeId',$this->officeId);
                $criteria->compare('t.sourceId',$this->sourceId);
                $criteria->compare('DATE(t.question_date)>',  CustomFuncs::invertDate($this->date1));
                $criteria->compare('DATE(t.question_date)<',  CustomFuncs::invertDate($this->date2));
                //$criteria->compare('t.addedById',$this->addedById);
                
                //$criteria->with = array('source','employee','agreements');
                $criteria->order = "t.id desc";
                
                
                $currentUser = User::model()->findByPk(Yii::app()->user->id);
            
                // если пользователь - админ, выбираем ВСЕ контакты
                if(!Yii::app()->user->checkAccess(User::ROLE_ROOT)) {
                    if(Yii::app()->user->checkAccess(User::ROLE_MANAGER)) {
                        // если менеджер, выбираем неприсвоенные контакты и присвоенные его подчиненным
                        // получим массив id подчиненных
                        $myEmployeesIds = $currentUser->myEmployeesIds();
                        $myEmployeesIds[] = 0; // добавление в поиск тех контактов, у кого id сотрудника=0
                        $criteria->addInCondition('t.employeeId',$myEmployeesIds);
                    } else {
                        if(Yii::app()->user->role == User::ROLE_JURIST) {
                            if(isset($_GET['my'])) {
                                // если передан GET параметр my, найдем только привязанные к юристу контакты
                                $criteria->addColumnCondition(array('t.employeeId' => Yii::app()->user->id));
                            }
                        }
                    }
                }
            
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'pagination'=>array(
                                    'pageSize'=>20,
                                ),
		));
	}
        
        protected function beforeDelete()
        {
            if(parent::beforeDelete()) {
                Agreement::model()->deleteAllByAttributes(array('contactId'=>$this->id));
                Event::model()->deleteAllByAttributes(array('contact_id'=>$this->id));
                return true;
            } else {
                return false;
            }
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
        
        //статическая версия функции преобразования телефонного номера
        public static function getValidPhoneStatic($phone) 
        {
            if($phone == '') return false;
            
            // удалим из номера все нецифровые символы
            $phone = preg_replace('/([^0-9])/i', '', $phone);
            $phone = substr($phone, -10, 10);
            $phone = '7' . $phone;
            
            return $phone;
        }
        
        
}