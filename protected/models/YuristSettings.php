<?php

/**
 * This is the model class for table "{{yuristSettings}}".
 *
 * The followings are the available columns in table '{{yuristSettings}}':
 * @property integer $yuristId
 * @property string $alias
 * @property integer $startYear
 * @property string $description
 * @property string $hello
 * @property integer $status
 * @property integer $isVerified
 * @property integer $vuz
 * @property integer $facultet
 * @property integer $education
 * @property integer $vuzTownId
 * @property integer $educationYear
 * @property integer $advOrganisation
 * @property integer $advNumber
 * @property integer $position
 * @property integer $site
 * @property integer $priceConsult
 * @property integer $priceDoc
 * @property string $phoneVisible
 * @property string $emailVisible
 * 
 */
class YuristSettings extends CActiveRecord
{
	
        const STATUS_NOTHING = 0; // нет статуса
        const STATUS_YURIST = 1; // юрист
        const STATUS_ADVOCAT = 2; // адвокат
        const STATUS_JUDGE = 3; // судья
        
        public $statusNew;
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
			array('yuristId, startYear, isVerified, status, vuzTownId, educationYear, priceConsult, priceDoc', 'numerical', 'integerOnly'=>true),
			array('alias', 'length', 'max'=>255),
                        array('alias','match','pattern'=>'/^([а-яa-zА-ЯA-Z0-9ёЁ\-. ])+$/u', 'message'=>'В псевдониме могут присутствовать буквы, цифры, точка, дефис и пробел'),
                        array('site','match','pattern'=>'/^(https?:\/\/)?([\dа-яёЁa-z\.-]+)\.([а-яёЁa-z\.]{2,6})([\/\w \.-]*)*\/?$/u', 'message'=>'В адресе сайта присутствуют недопустимые символы'),
                        array('description, hello, vuz, facultet, education, advOrganisation, advNumber, position', 'safe'),
			array('emailVisible','email', 'message'=>'В Email допускаются латинские символы, цифры, точка и дефис'),
			array('phoneVisible','match', 'pattern'=>'/^([0-9\- \(\)\+])+$/u', 'message'=>'В номере телефона разрешены цифры, скобки, пробелы и дефисы'),

                        // The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('yuristId, alias, startYear, description', 'safe', 'on'=>'search'),
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
                    'vuzTown'    =>  array(self::BELONGS_TO, 'Town', 'vuzTownId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'yuristId'      => 'Юрист',
			'alias'         => 'Псевдоним',
			'startYear'     => 'Год начала работы',
			'description'   => 'Описание',
			'hello'         => 'Приветствие',
			'status'        => 'Статус',
			'isVerified'    => 'Верифицирован',
                        'vuz'           => 'ВУЗ', 
                        'facultet'      =>  'факультет', 
                        'education'     =>  'образование', 
                        'advOrganisation'   =>  'членство в адвокатском объединении', 
                        'advNumber'     =>  'номер в реестре адвокатов', 
                        'position'      =>  'должность', 
                        'vuzTownId'     =>  'город ВУЗа', 
                        'educationYear' =>  'год окончания',
                        'site'          =>  'сайт',
                        'priceConsult'  =>  'стоимость консультации',
                        'priceDoc'      =>  'стоимость составления документа',
                        'phoneVisible'  =>  'Отображаемый телефон',
                        'emailVisible'  =>  'Отображаемый Email',
		);
	}
        
        
        // возвращает массив, ключами которого являются коды статусов, а значениями - названия
        static public function getStatusesArray()
        {
            return array(
                self::STATUS_NOTHING    =>  'без статуса',
                self::STATUS_YURIST     =>  'Юрист',
                self::STATUS_ADVOCAT    =>  'Адвокат',
                self::STATUS_JUDGE      =>  'Судья',
                                
            );
        }
        
        public function getStatusName()
        {
            $statusesArray = self::getStatusesArray();
            $statusName = $statusesArray[$this->status];
            return $statusName;
        }
        
        static public function getStatusNameByCode($code)
        {
            $statusesArray = self::getStatusesArray();
            $statusName = $statusesArray[$code];
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
