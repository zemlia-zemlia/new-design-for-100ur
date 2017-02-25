<?php

/**
 * This is the model class for table "{{question}}".
 *
 * The followings are the available columns in table '{{question}}':
 * @property integer $id
 * @property integer $number
 * @property string $questionText
 * @property integer $categoryId
 * @property string $categoryName
 * @property string $title
 * @property string $townId
 * @property string $authorName
 * @property integer $status
 * @property string $phone
 * @property string $email
 * @property integer $leadStatus
 * @property integer $authorId
 * @property integer $price
 * @property integer $payed
 * @property string $sessionId
 */
class Question extends CActiveRecord
{
    
        const STATUS_NEW = 0;
        const STATUS_MODERATED = 1;
        const STATUS_PUBLISHED = 2;
        const STATUS_SPAM = 3;
        const STATUS_CHECK = 4;
        const STATUS_PRESAVE = 5;
        
        
        const LEAD_STATUS_SENT_CRM = 1;
        const LEAD_STATUS_SENT_LEADIA = 2;
        
        const LEVEL_1 = 1;
        const LEVEL_2 = 2;
        const LEVEL_3 = 3;
        
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Question the static model class
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
		return '{{question}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('questionText, authorName', 'required', 'message'=>'{attribute} не заполнен'),
			array('phone', 'required', 'on'=>'create', 'message'=>'Поле {attribute} должно быть заполнено'),
			array('townId', 'required', 'except'=>array('preSave'), 'message'=>'Поле {attribute} должно быть заполнено'),
                        array('number, categoryId, status, publishedBy, authorId, price, payed', 'numerical', 'integerOnly'=>true),
			array('categoryName, sessionId', 'length', 'max'=>255),
                        array('authorName, title','match','pattern'=>'/^([а-яa-zА-ЯA-Z0-9ёЁ\-., ])+$/u', 'message'=>'В {attribute} могут присутствовать буквы, цифры, точка, дефис и пробел'),
                        array('phone','match','pattern'=>'/^([0-9\+])+$/u', 'message'=>'В номере телефона могут присутствовать только цифры и знак плюса'),
			array('email','email', 'message'=>'В Email допускаются латинские символы, цифры, точка и дефис', 'allowEmpty'=>true),
                        array('townId', 'match','not'=>true, 'except'=>array('preSave'), 'pattern'=>'/^0$/', 'message'=>'Поле Город не заполнено'),
                        array('description', 'safe'),
                        // The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, number, questionText, categoryId, categoryName', 'safe', 'on'=>'search'),
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
                    'town'          =>  array(self::BELONGS_TO, 'Town', 'townId'),
                    'answers'       =>  array(self::HAS_MANY, 'Answer', 'questionId'),
                    'answersCount'  =>  array(self::STAT, 'Answer', 'questionId'),
                    'bublishUser'   =>  array(self::BELONGS_TO, 'User', 'publishedBy'),
                    'author'   =>  array(self::BELONGS_TO, 'User', 'authorId'),
                    'categories'    =>  array(self::MANY_MANY, 'QuestionCategory', '{{question2category}}(qId, cId)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'            =>  'ID',
			'number'        =>  'Уникальный номер вопроса',
			'questionText'  =>  'Вопрос',
			'categoryId'    =>  'ID категории',
                        'category'      =>  'Категория',
			'categoryName'  =>  'Название категории',
                        'status'        =>  'Статус',
                        'authorName'    =>  'Ваше имя',
                        'town'          =>  'Город',
                        'townId'        =>  'Город',
                        'title'         =>  'Заголовок',
                        'phone'         =>  'Номер телефона',
                        'authorId'      =>  'ID автора',
                        'price'         =>  'Цена',
                        'payed'         =>  'Оплачен',
		);
	}
        
        // возвращает массив, ключами которого являются коды статусов, а значениями - названия статусов
        static public function getStatusesArray()
        {
            return array(
				self::STATUS_PRESAVE    =>  'Недозаполненные',
                self::STATUS_NEW        =>  'Email не указан / не подтвержден',
				self::STATUS_CHECK      =>  'Предварительно опубликован',
                self::STATUS_MODERATED  =>  'Ждет публикации',
                self::STATUS_PUBLISHED  =>  'Опубликован',
                self::STATUS_SPAM       =>  'Спам',
                
                
            );
        }
        
        // возвращает название статуса для объекта
        public function getQuestionStatusName()
        {
            $statusesArray = self::getStatusesArray();
            return $statusesArray[$this->status];
        }
        
        // статический метод, возвращает название статуса вопроса по коду
        static public function getStatusName($status)
        {
            $statusesArray = self::getStatusesArray();
            return $statusesArray[$status];
        }
        
        
        // возвращает количество вопросов с определенным статусом
        static public function getCountByStatus($status)
        {
            $connection  = Yii::app()->db;
            $sqlPublished = "SELECT COUNT(id) AS counter FROM {{question}} WHERE status=:status";
            $command = $connection->cache(600)->createCommand($sqlPublished);
            $command->bindParam(":status",  $status, PDO::PARAM_INT);
            $row = $command->queryRow();
            return $row['counter'];
        }
        
        // возвращает количество вопросов без ответов
        static public function getCountWithoutAnswers()
        {
            $connection  = Yii::app()->db;
            $sql = "SELECT COUNT(*) counter FROM {{question}} q LEFT OUTER JOIN {{answer}} a ON a.questionId = q.id WHERE a.id IS NULL AND q.status IN (:statusPub,:statusCheck)";
            $command = $connection->createCommand($sql);
            $command->bindValue(":statusCheck",  Question::STATUS_CHECK, PDO::PARAM_INT);
            $command->bindValue(":statusPub",  Question::STATUS_PUBLISHED, PDO::PARAM_INT);
            $row = $command->queryRow();
            return $row['counter'];
        }
        
        // возвращает количество вопросов 
        static public function getCount()
        {
            $connection  = Yii::app()->db;
            $sqlPublished = "SELECT COUNT(id) AS counter FROM {{question}}";
            $command = $connection->cache(600)->createCommand($sqlPublished);
            $command->bindParam(":status",  $status, PDO::PARAM_INT);
            $row = $command->queryRow();
            return $row['counter'];
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
		$criteria->compare('number',$this->number);
		$criteria->compare('questionText',$this->questionText,true);
		$criteria->compare('categoryId',$this->categoryId);
		$criteria->compare('categoryName',$this->categoryName,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        protected function beforeSave()
        {
            if(!parent::beforeSave()) {
                return false;
            }
            
            
            if($this->title == '') {
                $this->formTitle();
            }
            
            return true;
        }
        
        // присваивает полю title первые 10 слов из текста вопроса
        public function formTitle($wordsCount = 10)
        {
            $text = trim(preg_replace("/[^a-zA-Zа-яА-ЯёЁ0-9 ]/ui", ' ', $this->questionText));
            //echo $text . "\n";
            //echo $wordsCount . "\n";
            preg_match("/(\w+\s+){0,".$wordsCount."}/u", $text, $matches);
            $this->title = $matches[0];
            //print_r($matches); exit;
            $patterns = array();
            $patterns[0] = '/Здравствуйте/ui';
            $patterns[1] = '/Добрый день/ui';
            $patterns[2] = '/[!,\.\?:]/ui';
            $patterns[3] = '/quote/ui';
            $replacements = array();
            $replacements[3] = '';
            $replacements[2] = ' ';
            $replacements[1] = '';
            $replacements[0] = '';
            
            $this->title = preg_replace($patterns, $replacements, $this->title);
            $this->title = mb_strtoupper(mb_substr($this->title, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($this->title, 1, mb_strlen($this->title), 'UTF-8');
        }
        
        
        /*
         * возвращает цену вопроса по уровню
         */
        public static function getPriceByLevel($level=self::LEVEL_1)
        {
            switch($level) {
                case self::LEVEL_1:
                    return 125;
                    break;
                case self::LEVEL_2:
                    return 295;
                    break;
                case self::LEVEL_3:
                    return 455;
                    break;
            }
        }
        
        // сохраняет в базу вопрос, который не был полностью заполнен (имеет только имя и текст вопроса)
        public function preSave()
        {
            if($this->sessionId == '') {
                $this->sessionId = '' . time() . '_' . mt_rand(100,999);
                
                $this->setScenario('preSave');
                
                // особый статус "предварительно сохранен"
                $this->status = self::STATUS_PRESAVE;
                $this->townId = Yii::app()->user->getState('currentTownId');
                if(!$this->save()) {
                    //echo "Ошибки при предсохранении вопроса";
                    //CustomFuncs::printr($this->errors);
                }
            }
            
            
        }
        
        public function createAuthor()
        {
            
            $author = new User;
            $author->role = User::ROLE_CLIENT;
            $author->phone = $this->phone;
            $author->email = $this->email;
            $author->townId = $this->townId;
            $author->name = $this->authorName;
            $author->password = $author->password2 = $author->generatePassword();
            $author->confirm_code = md5($this->email.mt_rand(100000,999999));

            if($author->save()) {
                $this->authorId = $author->id;
                $author->sendConfirmation();
                return true;
            } else {
                return false;
            }
        }
        
        public static function getRandomId()
        {
            
            /*
             * возвращает id произвольного вопроса с определенным статусом и из категории, которая привязана 
             * к пользователю
             *  SELECT * FROM `crm_question` q 
                LEFT JOIN `crm_question2category` q2c ON q.id = q2c.qId
                WHERE q2c.cId IN(3,5) AND q.status=2
                ORDER BY RAND()
                LIMIT 1
             */
            
            $myCategories = Yii::app()->user->categories;
            
            $myCategoriesStr = '';
            $myCategoriesIds = array();
            foreach($myCategories as $cat) {
                $myCategoriesIds[] = $cat->id;
            }
            $myCategoriesStr = implode(',', $myCategoriesIds);
            
            $questionCommand = Yii::app()->db->createCommand()
                    ->select('q.id id')
                    ->from("{{question}} q")
                    ->leftJoin("{{question2category}} q2c", "q.id = q2c.qId")
                    ->where("q.status=:status", array(":status"=>self::STATUS_PUBLISHED))
                    ->order("RAND()")
                    ->limit(1);
            
            if($myCategoriesStr!='') {
                $questionCommand->andWhere("q2c.cId IN(" . $myCategoriesStr . ")");
            }       
            $questionRow = $questionCommand->queryRow();
            
            if($questionRow['id']) {
                return $questionRow['id'];
            } else {
                return 0;
            }
        }
        
        public static function normalizePhone($phone)
        {
            return preg_replace('/([^0-9])/i', '', $phone);
        }
}