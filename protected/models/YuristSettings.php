<?php

/**
 * This is the model class for table "{{yuristSettings}}".
 *
 * The followings are the available columns in table '{{yuristSettings}}':
 * @property integer $yuristId
 * @property string $alias
 * @property integer $startYear
 * @property string $description
 * @property integer $townId
 */
class YuristSettings extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{yuristSettings}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('yuristId', 'required'),
			array('yuristId, startYear, townId', 'numerical', 'integerOnly'=>true),
			array('alias', 'length', 'max'=>255),
                        array('alias','match','pattern'=>'/^([а-яa-zА-ЯA-Z0-9ёЁ\-. ])+$/u', 'message'=>'В псевдониме могут присутствовать буквы, цифры, точка, дефис и пробел'),
                        array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('yuristId, alias, startYear, description, townId', 'safe', 'on'=>'search'),
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
                    'user'       =>  array(self::BELONGS_TO, 'User', 'yuristId'),
                    'town'       =>  array(self::BELONGS_TO, 'Town', 'townId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'yuristId' => 'Yurist',
			'alias' => 'Псевдоним',
			'startYear' => 'Год начала работы',
			'description' => 'Описание',
			'townId' => 'ID Города',
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

		$criteria->compare('yuristId',$this->yuristId);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('startYear',$this->startYear);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('town',$this->town);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return YuristSettings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
