<?php

/**
 * Модель для работы с комментариями
 *
 * Поля, доступные в таблице '{{comment}}':
 * @property string $id
 * @property integer $type
 * @property integer $authorId
 * @property integer $objectId
 * @property integer $rating
 * @property integer $status
 * @property string $text
 * @property string $dateTime
 * @property string $authorName
 */
class Comment extends CActiveRecord
{
	
        const TYPE_CONTACT = 1;
        const TYPE_AGREEMENT = 2;
        const TYPE_EVENT = 3;
        const TYPE_ANSWER = 4;
        const TYPE_COMPANY = 5;
        
        const STATUS_NEW = 0;
        const STATUS_CHECKED = 1;
        const STATUS_SPAM = 2;
        
        // используется в иерархии комментариев
        public $parentId;




        /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Comment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        /**
         * Определение поведения для работы иерархичных комментариев
         * @return type
         */
        public function behaviors()
        {
            return array(
                'nestedSetBehavior' =>  array(
                    'class'             =>  'ext.yiiext.behaviors.model.trees.NestedSetBehavior',
                    'leftAttribute'     =>  'lft',
                    'rightAttribute'    =>  'rgt',
                    'levelAttribute'    =>  'level',
                    'hasManyRoots'      =>  true, 
                ),
            );
        }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{comment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('text', 'required'),
			array('type, authorId, objectId, rating, status, parentId', 'numerical', 'integerOnly'=>true),
                        array('authorName','length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, authorId, objectId, text, dateTime', 'safe', 'on'=>'search'),
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
                    'author'    =>  array(self::BELONGS_TO, 'User', 'authorId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'        => 'ID',
			'type'      => 'Тип',
			'authorId'  => 'ID автора',
			'objectId'  => 'ID связанного объекта',
			'text'      => 'Комментарий',
			'dateTime'  => 'Дата и время',
                        'author'    =>  'Автор',
                        'rating'    =>  'Оценка',
                        'authorName'=>  'Имя автора',
		);
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('authorId',$this->authorId);
		$criteria->compare('objectId',$this->objectId);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('dateTime',$this->dateTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        
        /**
         * возвращает массив, ключами которого являются коды статусов, а значениями - названия статусов
         * 
         * @return array (код статуса => название)
         */
        static public function getStatusesArray()
        {
            return array(
                self::STATUS_NEW        =>  'Новый, не проверен',
                self::STATUS_CHECKED    =>  'Опубликован',
                self::STATUS_SPAM       =>  'Спам',
            );
        }
        
        /**
         * возвращает название статуса для объекта
         * 
         * @return string название статуса
         */
        public function getCommentStatusName()
        {
            $statusesArray = self::getStatusesArray();
            return $statusesArray[$this->status];
        }
        
        /**
         * Статический метод, возвращает название статуса по коду
         * 
         * @param int $status код статуса
         * @return string Название статуса 
         */
        static public function getStatusName($status)
        {
            $statusesArray = self::getStatusesArray();
            return $statusesArray[$status];
        }
        
        /**
         * Возвращает количество новых комментариев заданного типа
         * 
         * @param int $type Тип комментария
         * @param int $cacheTime Время кеширования (сек.)
         * @return int количество новых комментариев
         */
        public static function newCommentsCount($type, $cacheTime = 0) 
        {
            $counterRow = Yii::app()->db->cache($cacheTime)->createCommand()
                    ->select("COUNT(*) counter")
                    ->from("{{comment}}")
                    ->where("type=:type AND status=:status", array(':type'=>(int)$type, ':status'=>self::STATUS_NEW))
                    ->queryRow();
            
            return ($counterRow!== false)?$counterRow['counter']:0;
        }
        
        
        /**
         * Метод, вызываемый после сохранения комментария
         */
        
        protected function afterSave()
        {
            /**
             * после сохранения коментария, если это был комментарий к ответу юриста, 
             * отправим юристу уведомление
             * а если это комментарий на комментарий, уведомим автора родительского комментария
             */
            if($this->type == static::TYPE_ANSWER && $this->objectId && $this->isNewRecord === true) {
                $answer = Answer::model()->with('question')->findByPk($this->objectId);
                
                if($this->level>1) {
                    // это комментарий на комментарий
                    $parentComment = $this->parent()->find();
                    if($parentComment && $parentComment->author) {
                        $parentComment->author->sendCommentNotification($answer->question, $this, true);
                    }
                } else {
                    // это комментарий на ответ
                    if($answer && $answer->question) {
                        $answerAuthor = $answer->author;
                        if($answerAuthor && $answerAuthor->active100 == 1) {
                            $answerAuthor->sendCommentNotification($answer->question, $this, false);
                        }
                    }
                }
                  
            }
            
            parent::afterSave();
        }
}