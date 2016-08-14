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
 * @property string $photo
 * @property integer $regionId
 * @property integer $countryId
 */
class Town extends CActiveRecord
{
	public $photoFile;
        const TOWN_PHOTO_PATH = "/upload/townphoto";
        const TOWN_PHOTO_THUMB_FOLDER = "/thumbs";
        
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
                        array('alias','unique','message'=>'Такой город уже есть в базе'),
			array('size, regionId, countryId', 'numerical', 'integerOnly'=>true),
                        array('name, ocrug,country, alias', 'length', 'max'=>64),
                        array('name,ocrug,country','match','pattern'=>'/^([а-яa-zА-ЯA-Z0-9ёЁ\-. \(\)])+$/u', 'message'=>'В {attribute} могут присутствовать буквы, цифры, скобки, точка, дефис и пробел'),
			array('alias','match','pattern'=>'/^([a-z0-9\-])+$/'),
                        array('description, description1, description2, seoKeywords, seoTitle, seoDescription','safe'),
                        array('photoFile', 'file', 'types'=>'jpg, gif, png', 'allowEmpty'=>true),
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
                    'questions'           =>  array(self::HAS_MANY, 'Question', 'townId'),
                    'questionsCount'      =>  array(self::STAT, 'Question', 'townId'),
                    'companies'           =>  array(self::HAS_MANY, 'YurCompany', 'townId'),
                    'region'              =>  array(self::BELONGS_TO, 'Region', 'regionId'),
                    'country'             =>  array(self::BELONGS_TO, 'Country', 'countryId'),
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
			'region' => 'Регион',
                        'country' => 'Страна',
                        'description' =>'Описание',
                        'alias' =>'Псевдоним на транслите',
                        'description1'  =>  'Описание 1',
                        'description2'  =>  'Описание 2',
                        'seoTitle'  =>  'SEO Title',
                        'seoDescription'  =>  'SEO Description',
                        'seoKeywords'  =>  'SEO Keywords',
                        'photo'         =>  'Фотография',
                        'photoFile'     =>  'Файл с фотографией (минимум 1000х300 пикселей)',
                        'regionId'      =>  'ID региона',
                        'countryId'     =>  'ID страны',
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

            $townsArray = array();
            
            foreach($towns as $town) {
                $townsArray[$town->id] = $town->name . " (" . $town->ocrug . ")";
            }
            
            $townsArray = array(0=>'Не указан') + $townsArray;
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
        
        // возвращает URL фотографии города относительно корня сайта
        public function getPhotoUrl($size='full')
        {
            $photoUrl = '';
                        
            if($size == 'full') {
                $photoUrl = self::TOWN_PHOTO_PATH . '/' . CHtml::encode($this->photo);
            } elseif($size == 'thumb') {
                $photoUrl = self::TOWN_PHOTO_PATH . self::TOWN_PHOTO_THUMB_FOLDER . '/' . CHtml::encode($this->photo);
            }
            return $photoUrl;
        }
        
        // ищем соседние города
        // возвращает массив объектов Town
        public function getCloseTowns()
        {
            $region = $this->region;
            $towns = $region->towns;
            return $towns;
        }
        
}