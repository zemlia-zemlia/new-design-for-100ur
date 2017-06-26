<?php

/**
 * Модель для работы с источниками лидов 100 юристов.
 *
 * The followings are the available columns in table '{{leadsource}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $officeId
 * @property integer $noLead
 * @property integer $active
 * @property string $appId
 * @property string $secretKey
 */
class Leadsource100 extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Leadsource the static model class
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
		return '{{leadsource100}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name, description', 'length', 'max'=>255),
                    	array('officeId, noLead, active', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, description', 'safe', 'on'=>'search'),
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
                    'office'    =>  array(self::BELONGS_TO, 'Office', 'officeId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'            =>  'ID',
			'name'          =>  'Название',
			'description'   =>  'Описание',
                        'officeId'      =>  'Офис',
                        'noLead'        =>  'Клиенты сразу приходят на консультацию',
                        'active'        =>  'Активность',
                        'appId'         =>  'ID источника для API',
                        'secretKey'     =>  'Секретный ключ для API',
		);
	}
        
        /**
         *  возвращает массив источников лидов, ключами которого являются ID, а значениями - названия
         * 
         * @param boolean $showInactive показывать неактивные источники
         * @return Array массив источников лидов (id => name)
         */
        
        static public function getSourcesArray($showInactive = true)
        {
            $attributes = array();

            if($showInactive == false) {
                $attributes['active'] = 1;
            } 

            //CustomFuncs::printr($attributes);
            $sources = self::model()->cache(3600)->findAllByAttributes($attributes);

            $sourcesArray = array(null=>'Нет');
            foreach($sources as $source) {
                $sourcesArray[$source->id] = $source->name;
            }
            return $sourcesArray;
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
                $criteria->compare('office',$this->office,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        /**
         * Генерирует appId
         * @return integer appId
         */
        public function generateAppId()
        {
            $this->appId = mt_rand(1000000, 9999999);
        }
        
        /**
         * Генерирует secretKey
         * @return string secretKey
         */
        public function generateSecretKey()
        {
            $this->secretKey = md5($this->id . mt_rand(1000000, 9999999) . time());
        }
}