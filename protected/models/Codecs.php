<?php

/**
 * This is the model class for table "{{codecs}}".
 *
 * The followings are the available columns in table '{{codecs}}':
 * @property integer $id
 * @property string $title
 * @property integer $parent_id
 * @property string $content
 * @property string $sourceUrl
 * @property integer $isCat
 */
class Codecs extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Codecs the static model class
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
		return '{{codecs}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, parent_id, content, sourceUrl', 'required'),
			array('parent_id, isCat', 'numerical', 'integerOnly'=>true),
			array('title, sourceUrl', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, parent_id, content, sourceUrl, isCat', 'safe', 'on'=>'search'),
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
                    'parent'    =>  array(self::BELONGS_TO, 'Codecs', 'parent_id'),
                    'children'  =>  array(self::HAS_MANY, 'Codecs', 'parent_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Заголовок',
			'parent_id' => 'ID родительской категории',
			'content' => 'Контент',
			'sourceUrl' => 'Источник',
			'isCat' => 'Является категорией',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('sourceUrl',$this->sourceUrl,true);
		$criteria->compare('isCat',$this->isCat);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}