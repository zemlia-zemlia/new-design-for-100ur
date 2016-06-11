<?php

/**
 * This is the model class for table "{{answer}}".
 *
 * The followings are the available columns in table '{{answer}}':
 * @property integer $id
 * @property integer $questionId
 * @property string $answerText
 * @property integer $authorId
 * @property integer $status
 */
class Answer extends CActiveRecord
{
        const STATUS_NEW = 0;
        const STATUS_PUBLISHED  = 1;
        const STATUS_SPAM = 2;
        /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Answer the static model class
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
		return '{{answer}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('questionId, answerText', 'required', 'message'=>'Поле {attribute} должно быть заполнено'),
			array('questionId, authorId, status', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, questionId, answerText, authorId', 'safe', 'on'=>'search'),
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
                    'question'  =>  array(self::BELONGS_TO, 'Question', 'questionId'),
                    'author'    =>  array(self::BELONGS_TO, 'User', 'authorId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'questionId' => 'ID вопроса',
                        'question' => 'Вопрос',
			'answerText' => 'Ответ',
			'authorId' => 'ID автора',
                        'status'   =>   'Статус',
		);
	}

        // возвращает массив, ключами которого являются коды статусов, а значениями - названия статусов
        static public function getStatusesArray()
        {
            return array(
                self::STATUS_NEW        =>  'Предварительно опубликован',
                self::STATUS_PUBLISHED  =>  'Опубликован',
                self::STATUS_SPAM       =>  'Спам',
            );
        }
        
        // возвращает название статуса для объекта
        public function getAnswerStatusName()
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
		$criteria->compare('questionId',$this->questionId);
		$criteria->compare('answerText',$this->answerText,true);
		$criteria->compare('authorId',$this->authorId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}