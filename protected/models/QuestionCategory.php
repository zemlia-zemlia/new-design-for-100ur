<?php

/**
 * Модель для работы с категориями вопросов
 *
 * Поля в таблице '{{questionCategory}}':
 * @property integer $id
 * @property string $name
 * @property integer $parentId
 * @property string $alias
 * @property integer $isDirection
 * @property string $description1
 * @property string $description2
 * @property string $seoTitle
 * @property string $seoDescription
 * @property string $seoKeywords
 * @property string $seoH1
 * @property integer $root
 * @property integer $lft
 * @property integer $rgt
 * @property integer $level
 * @property string $path
 * @property string $image
 * @property string $publish_date
 */
class QuestionCategory extends CActiveRecord
{
    const NO_CATEGORY = 0; // 0 - нет категории

    const IMAGES_DIRECTORY = '/upload/categories/';

    const DEFAULT_IMAGE = '/pics/2017/head_default.jpg';

    public $imageFile; // для загрузки через форму

    public $attachments = []; // прикрепленные файлы

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return QuestionCategory the static model class
     */
    public static function model($className = __CLASS__)
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
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'questionCategory';
    }

    /**
     * Определение поведения для работы иерархии
     * @return type
     */
    public function behaviors()
    {
        return array(
            'nestedSetBehavior' => array(
                'class' => 'ext.yiiext.behaviors.model.trees.NestedSetBehavior',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'levelAttribute' => 'level',
                'hasManyRoots' => true,
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
            array('parentId, isDirection', 'numerical', 'integerOnly' => true),
            array('name, publish_date', 'length', 'max' => 255),
            array('alias', 'match', 'pattern' => '/^([a-z0-9\-])+$/'),
            array('description1, description2, seoTitle, seoDescription, seoKeywords, seoH1', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, parentId', 'safe', 'on' => 'search'),
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
            'questions' => [self::MANY_MANY, 'Question', '{{question2category}}(cId, qId)'],
            'parent' => [self::BELONGS_TO, 'QuestionCategory', 'parentId'],
            'children' => [self::HAS_MANY, 'QuestionCategory', 'parentId', 'order' => 'children.name ASC'],
            'files' => [self::HAS_MANY, 'File', 'objectId', 'condition' => 'files.objectType = ' . File::ITEM_TYPE_OBJECT_CATEGORY],
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
            'description1' => 'Описание 1',
            'description2' => 'Описание 2',
            'seoTitle' => 'SEO title',
            'seoDescription' => 'SEO description',
            'seoKeywords' => 'SEO keywords',
            'seoH1' => 'Заголовок H1',
            'isDirection' => 'Является направлением',
            'image' => 'Изображение',
            'imageFile' => 'Изображение категории',
            'publish_date' => 'Дата публикации',
            'attachments' => 'Прикрепленные файлы',
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
        $allCategories = array(0 => 'Без категории');

        $topCategories = QuestionCategory::model()->findAll(array(
                'order' => 't.name',
                'with' => 'children',
                'condition' => 't.parentId=0',
            )
        );

        foreach ($topCategories as $topCat) {
            $allCategories[$topCat->id] = $topCat->name;
            if (sizeof($topCat->children)) {
                foreach ($topCat->children as $childCat) {
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('parentId', $this->parentId);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * перед сохранением экземпляра класса проверим, есть ли алиас. Если нет, присвоим.
     *
     * @return boolean
     */
    protected function beforeSave()
    {
        if (parent::beforeSave()) {
            if ($this->alias == '') {
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
        if ($this->$propName) {
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
        if ($categoryArray[$propName]) {
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
            ->select('id, name, alias, parentDirectionId')
            ->from('{{questionCategory}}')
            ->where('isDirection = 1')
            ->order('parentDirectionId ASC, name ASC')
            ->queryAll();
        //CustomFuncs::printr($categoriesRows);
        $categories = array();
        $categoriesHierarchy = array();

        if (!$withAlias) {
            foreach ($categoriesRows as $row) {
                $categories[$row['id']] = $row['name'];
            }
        } else {
            foreach ($categoriesRows as $row) {
                $categories[$row['id']] = array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'alias' => $row['alias'],
                    'parentDirectionId' => $row['parentDirectionId'],
                );
            }
        }

//            CustomFuncs::printr($categories);exit;

        if ($withHierarchy === true && $withAlias === true) {
            // перебираем все категории-направления
            foreach ($categories as $catId => $cat) {
                // если нет родителя, это категория верхнего уровня
                if ($cat['parentDirectionId'] == 0) {
                    $categoriesHierarchy[$catId] = $cat;
                }

                /* если нет родителя, но родитель не найден в направлениях, записываем в верхний уровень
                    происходит, если категорию дочернего уровня пометили как направление
                */
                if ($cat['parentDirectionId'] != 0 && !array_key_exists($cat['parentDirectionId'], $categories)) {
                    $categoriesHierarchy[$catId] = $cat;
                }
            }

            foreach ($categories as $catId => $cat) {
                /*
                * если дочерняя категория и в наборе есть родитель
                */
                if ($cat['parentDirectionId'] != 0 && array_key_exists($cat['parentDirectionId'], $categories)) {
                    $categoriesHierarchy[$cat['parentDirectionId']]['children'][$catId] = $cat;
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

        foreach ($directionsHirerarchy as $key => $direction) {
            $directions[$key] = $direction['name'];

            if ($direction['children']) {
                foreach ($direction['children'] as $childId => $child) {
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
        if ($this->description1 || $this->description2) {
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
     */
    public function getUrl($rewritePath = false)
    {

        $urlArray = [];

        // если в свойстве path хранится путь к странице категории, вытащим его оттуда,
        // не делая лишнего запроса к БД
        if ($this->path) {
            $ancestors = explode("/", $this->path);
            $urlArray['root'] = $ancestors[0];
        } else {
            $ancestors = Yii::app()->db->cache(0)->createCommand()
                ->select('alias')
                ->from('{{questionCategory}}')
                ->where('lft<:lft AND rgt>:rgt AND root=:root', [
                    ':lft' => $this->lft,
                    ':rgt' => $this->rgt,
                    ':root' => $this->root,
                ])
                ->order('lft')
                ->queryAll();
        }

        if (isset($ancestors[0]['alias'])) {
            $urlArray['root'] = $ancestors[0]['alias'];
        }

        $urlArray['name'] = $this->alias;

        // если путь не сохранен (или задано переписать его), сохраним его в свойстве path на будущее (без name)
        if (!$this->path) {
            $urlArrayPath = $urlArray;
            array_pop($urlArrayPath);
            $this->path = implode('/', $urlArrayPath);
            $this->saveNode();
        }
        // если нужно перезаписать path, просто сбрасываем его, чтобы обновить при следующем обращении
        if ($rewritePath === true) {
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

    /**
     * Возвращает массив, ключами которого являются ключевые слова, а значениями - id соответствующих категорий
     * @return array Массив ключевых слов
     */
    public static function keys2categories()
    {
        return Yii::app()->params['categories'];
    }


    /**
     * Возвращает массив категорий по id родителя, для вывода в списке в админке
     * @param $parentId
     * @return array
     */
    public static function getCategoriesArrayByParent($parentId)
    {
        $categoriesArray = array();


        $categoriesRows = Yii::app()->db->createCommand()
            ->select("c.id c_id, "
                . "c.name c_name, "
                . "LENGTH(c.description1) c_description1,  "
                . "LENGTH(c.description2) c_description2, "
                . "LENGTH(c.seoTitle) c_seoTitle, "
                . "LENGTH(c.seoDescription) c_seoDescription, "
                . "LENGTH(c.seoKeywords) c_seoKeywords, "
                . "LENGTH(c.seoH1) c_seoH1, "
                . "c.isDirection c_isDirection, "
                . "c.level")
            ->from("{{questionCategory}} c")
            ->order("c.name, c.root, c.lft")
            ->where('c.parentId = :parentId', [':parentId' => $parentId])
            ->queryAll();

        foreach ($categoriesRows as $row) {
            $categoriesArray[$row['c_id']]['name'] = $row['c_name'];
            $categoriesArray[$row['c_id']]['description1'] = $row['c_description1'];
            $categoriesArray[$row['c_id']]['description2'] = $row['c_description2'];
            $categoriesArray[$row['c_id']]['seoTitle'] = $row['c_seoTitle'];
            $categoriesArray[$row['c_id']]['seoDescription'] = $row['c_seoDescription'];
            $categoriesArray[$row['c_id']]['seoKeywords'] = $row['c_seoKeywords'];
            $categoriesArray[$row['c_id']]['seoH1'] = $row['c_seoH1'];
            $categoriesArray[$row['c_id']]['isDirection'] = $row['c_isDirection'];
            $categoriesArray[$row['c_id']]['level'] = $row['level'];
        }

        return $categoriesArray;
    }

    /**
     * Возвращает путь на сервере к изображению категории
     * @return string
     */
    public function getImagePath()
    {
        if($this->image != '' && is_file(Yii::getPathOfAlias('webroot') . self::IMAGES_DIRECTORY . $this->image)) {
            return self::IMAGES_DIRECTORY . $this->image;
        } elseif ($this->image != '' && !is_file(Yii::getPathOfAlias('webroot') . self::IMAGES_DIRECTORY . $this->image)) {
            return self::DEFAULT_IMAGE;
        } else {
            return self::DEFAULT_IMAGE;
        }
    }


    /**
     * Возвращает массив категорий, отсортированный по убыванию даты публикации и id
     * @param int $limit Лимит выборки
     * @param boolean $hasPicture найти только категории с заглавной картинкой
     * @param int $rootId id раздела, в котором нужно выбрать категории
     * @return QuestionCategory[]
     */
    public static function getRecentCategories($limit = 3, $hasPicture = true, $rootId = null)
    {
        $criteria = new CDbCriteria();
        $criteria->order = 'publish_date DESC, id DESC';
        $criteria->limit = $limit;

        $criteria->addColumnCondition(['seoH1!' => '']);

        if ($hasPicture) {
            $criteria->addColumnCondition(['image!' => '']);
        }
        if ((int)$rootId > 0) {
            $criteria->addColumnCondition(['root' => $rootId]);
        }
        $categories = QuestionCategory::model()->findAll($criteria);
        return $categories;
    }

    /**
     * Возвращает массив метатегов для страницы категории
     */
    public function getAdditionalMetaTags()
    {
        $tags = [
            'og:title' => CHtml::encode($this->seoTitle),
            'og:type' => "article",
            'og:image' => Yii::app()->urlManager->baseUrl . $this->getImagePath(),
            'og:url' => Yii::app()->createUrl('/questionCategory/alias', $this->getUrl()),
            'og:description' => CHtml::encode($this->seoDescription),
        ];

        return $tags;
    }

}