<?php

/**
 * This is the model class for table "{{questionCategory}}".
 *
 * The followings are the available columns in table '{{questionCategory}}':
 * @property integer $id
 * @property string $name
 * @property integer $parentId
 * @property integer $isDirection
 * @property string $description1
 * @property string $description2
 * @property string $seoTitle
 * @property string $seoDescription
 * @property string $seoKeywords
 * @property string $seoH1
 */
class QuestionCategory extends CActiveRecord
{
        const NO_CATEGORY = 0;	
        
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
			array('parentId, isDirection', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
                        array('alias','match','pattern'=>'/^([a-z0-9\-])+$/'),
                        array('description1, description2, seoTitle, seoDescription, seoKeywords, seoH1', 'safe'),
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
                    'questions'         =>  array(self::MANY_MANY, 'Question', '{{question2category}}(cId, qId)'),
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
			'id'                => 'ID',
			'name'              => 'Название',
			'parentId'          => 'ID родительской категории',
                        'parent'            => 'Родительская категория',
                        'description1'      =>  'Описание 1',
                        'description2'      =>  'Описание 2',
                        'seoTitle'          =>  'SEO title',
                        'seoDescription'    =>  'SEO description',
                        'seoKeywords'       =>  'SEO keywords',
                        'seoH1'             =>  'Заголовок H1',
                        'isDirection'       =>  'Является направлением',
		);
	}
        
        // возвращает массив, ключи которого - id категорий, значения - названия
        public static function getCategoriesIdsNames()
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
        
        // перед сохранением экземпляра класса проверим, есть ли алиас. Если нет, присвоим.
        protected function beforeSave()
        {
            if(parent::beforeSave()) {
                if($this->alias == '') {
                    $this->alias = CustomFuncs::translit($this->name);
                }
                return true;
            } else {
                return false;
            }
        }
        
        public function checkIfPropertyFilled($propName)
        {
            if($this->$propName) {
                return "<span class='glyphicon glyphicon-ok'></span>";
            } else {
                return "";
            }
        }
        
        // проверяет, не 0 ли элемент массива с ключом $propName
        public static function checkIfArrayPropertyFilled($categoryArray, $propName)
        {
            if($categoryArray[$propName]) {
                return "<span class='glyphicon glyphicon-ok'></span>";
            } else {
                return "";
            }
        }


        /*
         * возвращает массив, ключами которого являются id категорий-направлений, а значениями - их названия
         */
        public static function getDirections($withAlias = false, $withHierarchy = false)
        {
            $categoriesRows = Yii::app()->db->createCommand()
                    ->select('id, name, alias, parentId')
                    ->from('{{questionCategory}}')
                    ->where('isDirection = 1')
                    ->order('name ASC')
                    ->queryAll();
            
            $categories = array();
            $categoriesHierarchy = array();
            
            if(!$withAlias) {
                foreach ($categoriesRows as $row) {
                    $categories[$row['id']] = $row['name'];
                }
            } else {
                foreach ($categoriesRows as $row) {
                    $categories[$row['id']] = array(
                        'name'  =>  $row['name'],
                        'alias' =>  $row['alias'],
                        'parentId'  =>  $row['parentId'],
                        );
                }
            }
            
            
            if($withHierarchy === true && $withAlias === true) {
                foreach($categories as $catId=>$cat) {
                    if($cat['parentId'] == 0) {
                        $categoriesHierarchy[$catId] = $cat;
                    }
                    if($cat['parentId'] != 0 && !array_key_exists($cat['parentId'], $categories)) {
                        $categoriesHierarchy[$catId] = $cat;
                    }
                    if($cat['parentId'] != 0 && array_key_exists($cat['parentId'], $categories)) {
                        $categoriesHierarchy[$cat['parentId']]['children'][$catId] = $cat;
                    }
                }
                
                return $categoriesHierarchy;
            }
            
            return $categories;
        }
        
        /*
         * возвращает одномерный массив направлений. направления-потомки имеют в названии дефис в начале
         * @param $directionsHirerarchy - массив иерархии направлений
         */
        public static function getDirectionsFlatList($directionsHirerarchy)
        {
            $directions = array();
            
            foreach($directionsHirerarchy as $key=>$direction) {
                $directions[$key] = $direction['name'];
                
                if($direction['children']) {
                    foreach ($direction['children'] as $childId=>$child) {
                        $directions[$childId] = '-- ' . $child['name'];
                    }
                }
            }
            
            
            return $directions;
        }
        
}