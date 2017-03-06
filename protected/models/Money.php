<?php

/**
 * This is the model class for table "{{money}}".
 *
 * The followings are the available columns in table '{{money}}':
 * @property integer $id
 * @property integer $accountId
 * @property string $datetime
 * @property integer $type
 * @property string $value
 * @property string $comment
 * @property integer $direction
 */
class Money extends CActiveRecord
{
	
        const TYPE_INCOME = 0; // доход
        const TYPE_EXPENCE = 1; // расход

        public $date1;
        public $date2; // диапазон дат для поиска
        
        
        /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{money}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('accountId, datetime, type, value, comment, direction', 'required'),
			array('accountId, type, direction', 'numerical', 'integerOnly'=>true),
			array('value', 'length', 'max'=>10),
			array('comment', 'length', 'max'=>255),
			array('date1, date2','match','pattern'=>'/^([0-9\-\.])$/u', 'message'=>'В датах могут присутствовать только цифры, дефисы и точки'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, accountId, datetime, type, value, comment, direction', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'accountId'     => 'Счет',
			'datetime'      => 'Дата',
			'type'          => 'Доход/расход',
			'value'         => 'Сумма',
			'comment'       => 'Комментарий',
			'direction'     => 'Статья',
			'date1'     	=> 'от',
			'date2'     	=> 'до',
		);
	}
        
        /**
         * возвращает массив, ключами которого являются коды счетов, а значениями - названия
         * @return array 
         */
        static public function getAccountsArray()
        {
            return array(
                0   =>  "Яндекс",
                1   =>  "Карта В",
                2   =>  "Карта М",
                3   =>  "Наличные", 
                4   =>  "Р/сч", 
            );
        }
        
        /**
         * Возвращает название счета по его коду
         * @return string
         */
        public function getAccount()
        {
            $accounts = self::getAccountsArray();
            return ($accounts[$this->accountId])?$accounts[$this->accountId]:false;
        }
        
        
        /**
         * возвращает массив, ключами которого являются коды статей расходов/доходов, а значениями - названия
         * @return array 
         * 1-100 - операционные расходы
         * 101-200 - инвестиции
         * 501-600 - доходы
         */
        static public function getDirectionsArray()
        {
            return array(
                1   =>  "Аренда",
                2   =>  "Хозрасходы",
                3   =>  "Контекстная реклама",
                4   =>  "Фонд оплаты труда", 
                5   =>  "Телефония",
                6   =>  "Покупка заявок",
                7   =>  "Реклама",
                101 =>  "SEO",
                500  =>  "Другое",
                501 => "Пополнение кампаний",
                502 => "Взносы учредителей",
                503 => "Размещение рекламы",
                504 => "VIP вопросы",
                
            );
        }
        
        /**
         * Возвращает название статьи для объекта Money по коду
         * @return string
         */
        public function getDirection()
        {
            $accounts = self::getDirectionsArray();
            return ($accounts[$this->direction])?$accounts[$this->direction]:false;
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
		$criteria->compare('accountId',$this->accountId);
		$criteria->compare('datetime',$this->datetime,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('direction',$this->direction);

		if($this->date1){
			$criteria->addCondition('datetime>="' . CustomFuncs::invertDate($this->date1) . '"');
		}
		if($this->date2){
			$criteria->addCondition('datetime<="' . CustomFuncs::invertDate($this->date2) . '"');
		}
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	/**
	*	Возвращает массив записей, соответствующих условию
	*
	*/
	public function getReportSet()
	{
		

		$command = Yii::app()->db->createCommand()
		->select('*')
		->from('{{money}}');

		if($this->date1) {
			$command->andWhere('`datetime`>=:date1', array(':date1'=>CustomFuncs::invertDate($this->date1)));
		}

		if($this->date2) {
			$command->andWhere('`datetime`<=:date2', array(':date2'=>CustomFuncs::invertDate($this->date2)));
		}

		//echo $command->text;
		return $command->queryAll();

	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Money the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
