<?php

/**
 * This is the model class for table "{{region}}".
 *
 * The followings are the available columns in table '{{region}}':
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property integer $countryId
 */
class Region extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{region}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, alias, countryId', 'required'),
			array('countryId', 'numerical', 'integerOnly'=>true),
			array('name, alias', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, alias, countryId', 'safe', 'on'=>'search'),
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
                    'towns'     =>  array(self::HAS_MANY, 'Town', 'regionId'),
                    'country'   =>  array(self::BELONGS_TO, 'Country', 'countryId'),
                    'capital'   =>  array(self::HAS_ONE, 'Town', 'regionId', 'condition'=>'isCapital=1'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
			'alias' => 'Псевдоним',
			'countryId' => 'ID страны',
		);
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('countryId',$this->countryId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Region the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        
        // возвращает массив, ключами которого являются id регионов, а значениями - их имена
        public static function getAllRegions($countryId = 2)
        {
            $allRegions = array();
            $criteria = new CDbCriteria;
            $criteria->addColumnCondition(array('countryId'      =>  (int)$countryId));
            $criteria->order = 'name asc';
            
            $regions = self::model()->findAll($criteria);
            
            foreach($regions as $region) {
                $allRegions[$region->id] = $region->name;
            }
            return $allRegions;
        }
        
        /**
         * Возвращает расстояние в километрах от центра столицы региона до точки с заданными координатами
         * @param float $lat Широта точки
         * @param float $lng Долгота точки
         * @return int Расстояние в километрах или -1 в случае, если не удалось найти (например, неизвестна столица региона)
         */
        public function getRangeFromCenter($lat, $lng)
        {
            $capital = $this->capital;
            
            if(!$capital) {
                return -1;
            }
            
            $capitalLat = $capital->lat;
            $capitalLng = $capital->lng;
            
            $distance = (int)SQRT(POW(110.6* ($lat - $capitalLat), 2) + POW(110.6 * ($capitalLng - $lng) * COS($lat / 57.3), 2));

            if($distance>0) {
                return $distance;
            } else {
                return -1;
            }
        }
}