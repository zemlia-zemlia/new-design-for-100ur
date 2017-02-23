<?php

/**
 * This is the model class for table "{{yurCompany}}".
 *
 * The followings are the available columns in table '{{yurCompany}}':
 * @property integer $id
 * @property string $name
 * @property string $logo
 * @property integer $townId
 * @property string $metro
 * @property string $yurName
 * @property string $phone1
 * @property string $phone2
 * @property string $phone3
 * @property string $address
 * @property string $yurAddress
 * @property string $description
 * @property integer $yearFound
 * @property string $website
 * @property integer $authorId
 */
class YurCompany extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
    
        public $photoFile;
        const COMPANY_PHOTO_PATH = "/upload/company";
        const COMPANY_PHOTO_THUMB_FOLDER = "/thumbs";
    
	public function tableName()
	{
		return '{{yurCompany}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, townId, phone1, address, description', 'required', 'except'=>'parsing'),
                        array('phone1, phone2, phone3','match','pattern'=>'/^([0-9\+])+$/u', 'message'=>'В номере телефона могут присутствовать только цифры и знак плюса'),
			array('townId, yearFound, authorId', 'numerical', 'integerOnly'=>true),
			array('name, logo, metro, yurName, phone1, phone2, phone3, address, yurAddress, website', 'length', 'max'=>255),
			array('photoFile', 'file', 'types'=>'jpg, gif, png', 'allowEmpty'=>true),
                        array('logo, metro, yurName, address, yurAddress, description, website', 'safe'),
                        // The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, logo, townId, metro, yurName, phone1, phone2, phone3, address, yurAddress, description, yearFound, website, authorId', 'safe', 'on'=>'search'),
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
                    'author'   =>  array(self::BELONGS_TO, 'User', 'authorId'),
                    'town'     =>  array(self::BELONGS_TO, 'Town', 'townId'),
                    'commentsChecked' =>  array(self::HAS_MANY, 'Comment', 'objectId', 'condition'=>'commentsChecked.type='.Comment::TYPE_COMPANY . ' AND commentsChecked.status='.Comment::STATUS_CHECKED, 'order'=>'commentsChecked.dateTime DESC'),
                    'comments' =>  array(self::HAS_MANY, 'Comment', 'objectId', 'condition'=>'comments.type='.Comment::TYPE_COMPANY, 'order'=>'comments.dateTime DESC'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
            return array(
                'id' => 'ID',
                'name' => 'Название ',
                'logo' => 'Логотип',
                'townId' => 'Город',
                'metro' => 'Метро',
                'yurName' => 'Юридическое название',
                'phone1' => 'Телефон 1',
                'phone2' => 'Телефон 2',
                'phone3' => 'Телефон 3',
                'address' => 'Адрес',
                'yurAddress' => 'Юридический адрес',
                'description' => 'Описание',
                'yearFound' => 'Год основания',
                'website' => 'Сайт',
                'authorId' => 'Автор',
                'photoFile' =>  'Файл с логотипом',
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
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('townId',$this->townId);
		$criteria->compare('metro',$this->metro,true);
		$criteria->compare('yurName',$this->yurName,true);
		$criteria->compare('phone1',$this->phone1,true);
		$criteria->compare('phone2',$this->phone2,true);
		$criteria->compare('phone3',$this->phone3,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('yurAddress',$this->yurAddress,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('yearFound',$this->yearFound);
		$criteria->compare('website',$this->website,true);
		$criteria->compare('authorId',$this->authorId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        // возвращает URL логотипа относительно корня сайта
        public function getPhotoUrl($size='full')
        {
            $photoUrl = '';
                        
            if($size == 'full') {
                $photoUrl = self::COMPANY_PHOTO_PATH . '/' . CHtml::encode($this->logo);
            } elseif($size == 'thumb') {
                $photoUrl = self::COMPANY_PHOTO_PATH . self::COMPANY_PHOTO_THUMB_FOLDER . '/' . CHtml::encode($this->logo);
            }
            return $photoUrl;
        }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return YurCompany the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
