<?php

/**
 * Модель для работы с категориями вопросов
 *
 * Поля в таблице '{{questionCategory}}':
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
 * @property integer $isDirection
 * @property integer $root
 * @property integer $lft
 * @property integer $rgt
 * @property integer $level
 * @property string $path
 */
class QuestionCategory extends CActiveRecord
{
        const NO_CATEGORY = 0; // 0 - нет категории	
        
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
         * Определение поведения для работы иерархии
         * @return type
         */
        public function behaviors()
        {
            return array(
                'nestedSetBehavior' =>  array(
                    'class'             =>  'ext.yiiext.behaviors.model.trees.NestedSetBehavior',
                    'leftAttribute'     =>  'lft',
                    'rightAttribute'    =>  'rgt',
                    'levelAttribute'    =>  'level',
                    'hasManyRoots'      =>  true, 
                ),
            );
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
        
        /**
         * возвращает массив, ключи которого - id категорий, значения - названия
         * дочерние категории имеют дефис перед названием
         * 
         * @return array массив категорий [id => name]
         */
        public static function getCategoriesIdsNames()
        {
            $allCategories = array(0=>'Без категории');
                
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
        
        /**
         * перед сохранением экземпляра класса проверим, есть ли алиас. Если нет, присвоим.
         * 
         * @return boolean 
         */
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
        
        /**
         * Проверяет, заполнено ли свойство объекта
         * 
         * @param string $propName Имя свойства
         * @return string Строка с галочкой, если поле заполнено, пустая - если не заполнено
         */
        public function checkIfPropertyFilled($propName)
        {
            if($this->$propName) {
                return "<span class='glyphicon glyphicon-ok'></span>";
            } else {
                return "";
            }
        }
        
        /**
         * проверяет, не 0 ли элемент массива с ключом $propName
         * 
         * @param array $categoryArray Массив с данными категории (fieldName => fieldValue)
         * @param string $propName ключ массива fieldName
         * @return string Строка с галочкой, если элемент заполнен, пустая - если не заполнен
         */
        public static function checkIfArrayPropertyFilled($categoryArray, $propName)
        {
            if($categoryArray[$propName]) {
                return "<span class='glyphicon glyphicon-ok'></span>";
            } else {
                return "";
            }
        }


        /**
         * возвращает массив, ключами которого являются id категорий-направлений, 
         * а значениями - их названия
         * 
         * @param boolean $withAlias включать ли alias в массив результатов
         * @param boolean $withHierarchy нужна ли иерархия в массиве результатов
         * 
         * @return array Массив категорий-направлений. Возможны 2 формата
         * 1. id => name
         * 2. id => array(
         *              name => name,
         *              alias => alias,
         *              parentId => parentId
         *          )
         */
        public static function getDirections($withAlias = false, $withHierarchy = false)
        {
            $categoriesRows = Yii::app()->db->createCommand()
                    ->select('id, name, alias, parentId')
                    ->from('{{questionCategory}}')
                    ->where('isDirection = 1')
                    ->order('name ASC')
                    ->queryAll();
            //CustomFuncs::printr($categoriesRows);
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
            
//            CustomFuncs::printr($categories);exit;
            
            if($withHierarchy === true && $withAlias === true) {
                // перебираем все категории-направления
                foreach($categories as $catId=>$cat) {
                    // если нет родителя, это категория верхнего уровня
                    if($cat['parentId'] == 0) {
                        $categoriesHierarchy[$catId] = $cat;
                    }
                    
                    /* если нет родителя, но родитель не найден в направлениях, записываем в верхний уровень
                        происходит, если категорию дочернего уровня пометили как направление
                    */
                    if($cat['parentId'] != 0 && !array_key_exists($cat['parentId'], $categories)) {
                        $categoriesHierarchy[$catId] = $cat;
                    }
                }
                
                foreach ($categories as $catId=>$cat) {
                     /*
                     * если дочерняя категория и в наборе есть родитель
                     */
                    if($cat['parentId'] != 0 && array_key_exists($cat['parentId'], $categories)) {
                        $categoriesHierarchy[$cat['parentId']]['children'][$catId] = $cat;
                    }
                }
                
//                CustomFuncs::printr($categoriesHierarchy);exit;
                
                return $categoriesHierarchy;
            }
            
            return $categories;
        }
        
        /**
         * возвращает одномерный массив направлений. 
         * направления-потомки имеют в названии дефис в начале
         * 
         * @param array $directionsHirerarchy Массив иерархии направлений
         * @return array массив направлений
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
        
        /**
         * Определяет, разрешать ли индексирование страницы текущей категории, исходя
         * из заполненности метаданных
         * 
         * @return boolean true - можно индексировать, false - нельзя
         */
        public function isIndexingAllowed()
        {
            // разрешим индексировать категории, у которых заполнено описание (верхнее ИЛИ нижнее)
            if($this->description1 || $this->description2) {
                return true;
            }
            
            return false;
        }
        
        /**
         * Функция получения элементов URL страницы категории
         * @param boolean $rewritePath Перезаписать свойство path
         * @return array
         * примеры:
         * /cat/ugolovnoe-pravo - ['name' => 'ugolovnoe-pravo'] 
         * /cat/ugolovnoe-pravo/krazha - ['name' => 'krazha', 'level2' => 'ugolovnoe-pravo']
         * @todo переписать запрос с ActiveRecord на DAO (во много раз сократит объем потребляемой памяти)
         */
        public function getUrl($rewritePath = false)
        {
            
            //$ancestors = $this->cache(3600)->ancestors()->findAll();
            $urlArray = array();
            
            // если в свойстве path хранится путь к странице категории, вытащим его оттуда, не делая лишнего запроса к БД
            if($this->path) {
                $ancestors = explode("/", $this->path);
            } else {
                $ancestors = Yii::app()->db->cache(0)->createCommand()
                    ->select('alias')
                    ->from('{{questionCategory}}')
                    ->where('lft<:lft AND rgt>:rgt AND root=:root', array(':lft' => $this->lft, ':rgt' => $this->rgt, ':root' => $this->root))
                    ->order('lft')
                    ->queryAll();
            }
            
            
            foreach($ancestors as $level=>$ancestor) {
                if($level == 0) {
                    $key = 'level2';
                }
                if($level == 1) {
                    $key = 'level3';
                }
                $urlArray[$key] = ($this->path)?$ancestor:$ancestor['alias'];
            }
            $urlArray['name'] = $this->alias;
            
            // если путь не сохранен (или задано переписать его), сохраним его в свойстве path на будущее
            if(!$this->path) {
                $this->path = implode('/', $urlArray);
                $this->saveNode();
            }
            // если нужно перезаписать path, просто сбрасываем его, чтобы обновить при следующем обращении
            if($rewritePath === true) {
                $this->path = '';
                $this->saveNode();
                $descendants = $this->descendants()->findAll();
                foreach ($descendants as $desc) {
                    $desc->path = '';
                    $desc->saveNode();
                }
            }
            
            return $urlArray;
        }
        
}