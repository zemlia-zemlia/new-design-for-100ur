<?php

use RandomStringHelper;
/**
 * This is the model class for table "{{docs}}".
 *
 * The followings are the available columns in table '{{docs}}':
 *
 * @property int    $id
 * @property string $name
 * @property string $filename
 * @property string $type
 * @property int    $downloads_count
 * @property int    $uploadTs
 * @property int    $size
 *
 * The followings are the available model relations:
 * @property File2category[] $file2categories
 * @property File2object[]   $file2objects
 */
class Docs extends CActiveRecord
{
    /** @var CUploadedFile $file */
    public $file;
<<<<<<< HEAD
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{docs}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
        if ($this->getIsNewRecord()) $allowEmpty = false;
        else $allowEmpty = true;
		return array(
			array('name', 'required', 'message' => 'Заполните имя'),
            array('file', 'required','on' => 'create' ),
			array('downloads_count', 'numerical', 'integerOnly'=>true),
			array('name, filename', 'length', 'max'=>255),
            array('file', 'file', 'types'=>'doc, docx, pdf, csv, xlsx, xls, rar, zip, 7z', 'maxSize' => 10485760, 'allowEmpty'=>$allowEmpty),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, downloads_count, description, uploadTs, size', 'safe'),

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
			'file2categories' => array(self::HAS_MANY, 'File2Category', 'file_id'),
			'file2objects' => array(self::HAS_MANY, 'File2Object', 'file_id'),
            'categories' => array(self::HAS_MANY, 'FileCategory', 'category_id', 'through' => 'file2categories'),
            'objects' => array(self::HAS_MANY, 'QuestionCategory', 'id', 'through' => 'file2objects'),
		);
	}
=======
>>>>>>> 667263e81cd3b63052977713d7a6346292116b19

    const RANDOM_NAME_LENGTH = 11; // длина рандомной части генерируемого имени файла

    /**
     * @return string the associated database table name
     */
    public function tableName():string
    {
        return '{{docs}}';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules():array
    {
        return [
            ['name', 'required', 'message' => 'Заполните имя'],
            ['file', 'required', 'on' => 'create'],
            ['downloads_count', 'numerical', 'integerOnly' => true],
            ['name, filename', 'length', 'max' => 255],
            ['file', 'file', 'types' => 'doc, docx, pdf, csv, xlsx, xls, rar, zip, 7z', 'allowEmpty' => $this->getIsNewRecord()],
            // The following rule is used by search().
            ['id, type, downloads_count, description, uploadTs, size', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations():array
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'file2categories' => [self::HAS_MANY, 'File2Category', 'file_id'],
            'file2objects' => [self::HAS_MANY, 'File2Object', 'file_id'],
            'categories' => [self::HAS_MANY, 'FileCategory', 'category_id', 'through' => 'file2categories'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels():array
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'filename' => 'Файл',
            'type' => 'Тип',
            'description' => 'Описание',
            'downloads_count' => 'Количество скачиваний',
        ];
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
     *                             based on the search/filter conditions
     */
    public function search():CActiveDataProvider
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria();
        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('filename', $this->filename, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('downloads_count', $this->downloads_count);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }


    /**
     * @return string
     */
    public function generateName():string
    {
        return RandomStringHelper::generateRandomString(self::RANDOM_NAME_LENGTH).'_'.time().'.'.$this->file->getExtensionName();
    }

    /**
     * @return string
     */
    public function getDownloadLink():string
    {
        ++$this->downloads_count;
        $this->save();

        return '/upload/files/'.$this->filename;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className active record class name
     *
     * @return Docs the static model class
     */
    public static function model($className = __CLASS__):Docs
    {
        return parent::model($className);
    }
}
