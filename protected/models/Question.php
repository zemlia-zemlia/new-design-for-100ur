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
 */
class Question extends CActiveRecord
{
    
        const STATUS_NEW = 0;
        const STATUS_MODERATED = 1;
        const STATUS_PUBLISHED = 2;
        const STATUS_SPAM = 3;
        
        const LEAD_STATUS_SENT_CRM = 1;
        const LEAD_STATUS_SENT_LEADIA = 2;
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
			array('questionText, townId, authorName', 'required', 'message'=>'Поле {attribute} должно быть заполнено'),
			array('phone', 'required', 'on'=>'create', 'message'=>'Поле {attribute} должно быть заполнено'),
                        array('number, categoryId, status, publishedBy', 'numerical', 'integerOnly'=>true),
			array('categoryName', 'length', 'max'=>255),
                        array('authorName, title','match','pattern'=>'/^([а-яa-zА-ЯA-Z0-9ёЁ\-., ])+$/u', 'message'=>'В {attribute} могут присутствовать буквы, цифры, точка, дефис и пробел'),
                        array('phone','match','pattern'=>'/^([0-9\+])+$/u', 'message'=>'В номере телефона могут присутствовать только цифры и знак плюса'),
			array('email','email', 'message'=>'В Email допускаются латинские символы, цифры, точка и дефис'),
                        array('townId', 'match','not'=>true, 'pattern'=>'/^0$/', 'message'=>'Поле Город не заполнено'),
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
		);
	}
        
        // возвращает массив, ключами которого являются коды статусов, а значениями - названия статусов
        static public function getStatusesArray()
        {
            return array(
                self::STATUS_NEW        =>  'Новый, не модерирован',
                self::STATUS_MODERATED  =>  'Одобрен, неопубликован',
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
            preg_match("/(?:\w+(?:\W+|$)){0,$wordsCount}/u", $this->questionText, $matches);
            $this->title = $matches[0];
            $patterns = array();
            $patterns[0] = '/Здравствуйте/ui';
            $patterns[1] = '/Добрый день/ui';
            $patterns[2] = '/[!,\.\?:]/ui';
            $replacements = array();
            $replacements[2] = '';
            $replacements[1] = '';
            $replacements[0] = '';
            
            $this->title = preg_replace($patterns, $replacements, $this->title);
            $this->title = trim($this->title);
            $this->title = ucfirst($this->title);
        }
}