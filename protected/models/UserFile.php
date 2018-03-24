<?php

/**
 * Модель для работы с файлами пользователей
 * 
 * Используется, например, для хранения сканов документов юристов, 
 * претендующих на верификацию своего профиля
 *
 * Поля в таблице '{{userFile}}':
 * @property integer $id
 * @property integer $userId
 * @property string $datetime
 * @property string $name
 * @property integer $isVerified
 * @property string $comment
 * @property integer $type
 * @property string $reason
 */
class UserFile extends CActiveRecord
{
	// типы файлов
        const TYPE_NOTHING = 0; // нет статуса
        const TYPE_YURIST = 1; // подтверждение статуса юрист
        const TYPE_ADVOCAT = 2; // подтверждение статуса адвокат
        const TYPE_JUDGE = 3; // подтверждение статуса судья
        
        // статусы файлов
        const STATUS_REVIEW = 0; // на рассмотрении
        const STATUS_CONFIRMED = 1; // одобрен
        const STATUS_DECLINED = 2; // не одобрен
        
        // папка для хранения пользовательских файлов
        const USER_FILES_FOLDER = '/upload/userfiles';
        
        public $userFile; // свойство для хранения данных о загружаемом файле
        
        /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
            return '{{userFile}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('userId, name, type', 'required'),
                array('userId, isVerified, type', 'numerical', 'integerOnly'=>true),
                array('name, reason', 'length', 'max'=>255),
                array('userFile', 'file', 'allowEmpty'=>true, 'types'=>'jpg,pdf,tiff,png', 'maxSize'=>10000000, 'message'=>'Файл должен быть в допустимом формате'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, userId, datetime, name, isVerified, comment, type, reason', 'safe', 'on'=>'search'),
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
                'user'   =>  array(self::BELONGS_TO, 'User', 'userId'),
            );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
            return array(
                'id'            => 'ID',
                'userId'        => 'ID пользователя',
                'datetime'      => 'Дата и время загрузки',
                'name'          => 'Имя файла',
                'isVerified'    => 'Проверен',
                'comment'       => 'Комментарий автора',
                'type'          => 'Тип',
                'reason'        => 'Причина отказа',
                'userFile'      => 'Скан или фото разворота диплома (формат jpg, pdf, tiff, png, до 10 МБ)',
            );
	}
        
        /**
         * возвращает массив, ключами которого являются коды типов, а значениями - названия
         * 
         * @return array массив типов (код => название) 
         */
        static public function getTypesArray()
        {
            return array(
                self::TYPE_NOTHING    =>  'без статуса',
                self::TYPE_YURIST     =>  'юрист',
                self::TYPE_ADVOCAT    =>  'адвокат',
                self::TYPE_JUDGE      =>  'судья',
            );
        }
        
        /**
         * Возвращает название типа текущего файла
         * 
         * @return string Название типа
         */
        public function getTypeName()
        {
            $typesArray = self::getTypesArray();
            $typeName = $typesArray[$this->type];
            return $typeName;
        }

        /** 
         * возвращает массив, ключами которого являются коды статусов, а значениями - названия
         * 
         * @return array Массив статусов (код => название)
         */
        static public function getStatusesArray()
        {
            return array(
                self::STATUS_REVIEW      =>  'на проверке',
                self::STATUS_CONFIRMED   =>  'одобрен',
                self::STATUS_DECLINED    =>  'не одобрен',                               
            );
        }
        
        /**
         * Возвращает статус текущего файла
         * 
         * @return string Статус файла
         */
        public function getStatusName()
        {
            $statusesArray = self::getStatusesArray();
            $statusName = $statusesArray[$this->isVerified];
            return $statusName;
        }

        
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('userId',$this->userId);
		$criteria->compare('datetime',$this->datetime,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('isVerified',$this->isVerified);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('reason',$this->reason,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserFile the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
