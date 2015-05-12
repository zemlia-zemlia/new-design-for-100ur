<?php

/**
 * This is the model class for table "{{town}}".
 *
 * The followings are the available columns in table '{{town}}':
 * @property integer $id
 * @property string $name
 * @property string $ocrug
 * @property string $description1
 * @property string $description2
 * @property string $seoTitle
 * @property string $seoDescription
 * @property string $seoKeywords
 */
class Town extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Town the static model class
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
		return '{{town}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, ocrug,country', 'required'),
                        array('name','unique','message'=>'Такой город уже есть в базе'),
			array('name, ocrug,country, alias', 'length', 'max'=>64),
                        array('name,ocrug,country','match','pattern'=>'/^([а-яa-zА-ЯA-Z0-9ёЁ\-. \(\)])+$/u', 'message'=>'В {attribute} могут присутствовать буквы, цифры, скобки, точка, дефис и пробел'),
			array('alias','match','pattern'=>'/^([a-z\-])+$/'),
                        array('description, description1, description2, seoKeywords, seoTitle, seoDescription','safe'),
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
			'ocrug' => 'Регион',
                        'country' => 'Страна',
                        'description' =>'Описание',
                        'alias' =>'Псевдоним на транслите',
                        'description1'  =>  'Описание 1',
                        'description2'  =>  'Описание 2',
                        'seoTitle'  =>  'SEO Title',
                        'seoDescription'  =>  'SEO Description',
                        'seoKeywords'  =>  'SEO Keywords',
		);
	}

        static public function getName($id) 
        {
            $model = self::model()->findByPk((int)$id);
            return $model->name . " (" . $model->ocrug . ")";
        }
        
        
        // возвращает массив. ключи - id городов, значения - названия
        static public function getTownsIdsNames()
        {
            $townsArray = array();
            $towns = self::model()->cache(3600)->findAll(array(
                'order' =>  'size DESC',
            ));

            $townsArray = array(0=>'Не определен');
            foreach($towns as $town) {
                $townsArray[$town->id] = $town->name . " (" . $town->ocrug . ")";
            }
            return $townsArray;
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
		$criteria->compare('ocrug',$this->ocrug,true);
                $criteria->compare('country',$this->country,true);
                $criteria->compare('alias',$this->alias,true);
                // для поиска городов с описанием и без:
                if($this->description === 0) $criteria->addCondition("description=''");
                    else if($this->description === 1) $criteria->addCondition("description!=''");

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'pagination'=>array(
                            'pageSize'=>30,
                        ),
		));
	}
        
        
        public function createPageTitle()
        {
            if(!empty($this->seoTitle)) {
               $pageTitle =  $this->seoTitle;
            } else {
                $pageTitle = "Консультация юриста в городе " . CHtml::encode($this->name) . ". ".CHtml::encode($this->ocrug) . ". ". Yii::app()->name;
            }
            return $pageTitle;
        }
        
        public function createPageDescription()
        {
            if(!empty($this->seoDescription)) {
               $pageDescription =  $this->seoDescription;
            } else { 
                $pageDescription = "Консультация юриста по всем отраслям права в городе " . CHtml::encode($this->name) . ", только профессиональные юристы и адвокаты.";
            }
            return $pageDescription;
        }
        
        public function createPageKeywords()
        {
            if(!empty($this->seoKeywords)) {
               $pageKeywords =  $this->seoKeywords;
            } else {
                $pageKeywords = 'Консультация юриста, консультация адваоката, '.CHtml::encode($this->name);
            }
            return $pageKeywords;
        }
        
}