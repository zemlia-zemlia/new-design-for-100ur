<?php

/**
 * This is the model class for table "{{questionCategory}}".
 *
 * The followings are the available columns in table '{{questionCategory}}':
 * @property integer $id
 * @property string $name
 * @property integer $parentId
 * @property string $description1
 * @property string $description2
 */
class QuestionCategory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return QuestionCategory the static model class
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
		return '{{questionCategory}}';
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
			array('parentId', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
                        array('alias','match','pattern'=>'/^([a-z0-9\-])+$/'),
                        array('description1, description2', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, parentId', 'safe', 'on'=>'search'),
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
                    'questions'     =>  array(self::HAS_MANY, 'Question', 'categoryId'),
                    'parent'        =>  array(self::BELONGS_TO, 'QuestionCategory', 'parentId'),
                    'children'      =>  array(self::HAS_MANY, 'QuestionCategory', 'parentId'),
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
			'parentId' => 'ID родительской категории',
                        'parent' => 'Родительская категория',
                        'description1'  =>  'Описание 1',
                        'description2'  =>  'Описание 2',
		);
	}
        
        // возвращает массив, ключи которого - id категорий, значения - названия
        public function getCategoriesIdsNames()
        {
            $allCategories = array(0=>'Без категории');
                
            /*$categories = self::model()->findAll(array(
                'order' =>  'name ASC',
            ));
            foreach($categories as $cat){
                $allCategories[$cat->id] = $cat->name;
            }*/
            $topCategories = QuestionCategory::model()->findAll(array(
                'order'     =>  't.name',
                'with'      =>  'children',
                'condition' =>  't.parentId=0',
                )
            );
            
            foreach($topCategories as $topCat) {
                $allCategories[$topCat->id] = $topCat->name;
                if(sizeof($topCat->children)) {
                    foreach($topCat->children as $childCat) {
                        $allCategories[$childCat->id] = '- ' . $childCat->name;
                    }
                }
            }
            
            return $allCategories;
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
		$criteria->compare('parentId',$this->parentId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}