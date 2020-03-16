<?php

namespace App\models;

use CActiveDataProvider;
use CActiveRecord;
use CDbCriteria;
use CHtml;
use Yii;

/**
 * Модель для работы с городами.
 *
 * Поля в таблице '{{town}}':
 *
 * @property int $id
 * @property string $name
 * @property string $description1
 * @property string $description2
 * @property string $seoTitle
 * @property string $seoDescription
 * @property string $seoKeywords
 * @property string $photo
 * @property int $regionId
 * @property int $countryId
 * @property int $isCapital
 * @property int $buyPrice
 * @property int $sellPrice
 */
class Town extends CActiveRecord
{
    // используется в форме редактирования для закачки фотографии города
    public $photoFile;

    const TOWN_PHOTO_PATH = '/upload/townphoto';
    const TOWN_PHOTO_THUMB_FOLDER = '/thumbs';

    /**
     * Returns the static model of the specified AR class.
     *
     * @return Town the static model class
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
        return '{{town}}';
    }

    /**
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'town';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['name', 'required'],
            ['alias', 'unique', 'message' => 'Такой город уже есть в базе'],
            ['size, regionId, countryId, isCapital, buyPrice, sellPrice', 'numerical', 'integerOnly' => true],
            ['name, alias', 'length', 'max' => 64],
            ['name', 'match', 'pattern' => '/^([а-яa-zА-ЯA-Z0-9ёЁ\-. \(\)])+$/u', 'message' => 'В {attribute} могут присутствовать буквы, цифры, скобки, точка, дефис и пробел'],
            ['alias', 'match', 'pattern' => '/^([a-z0-9\-])+$/'],
            ['description, description1, description2, seoKeywords, seoTitle, seoDescription', 'safe'],
            ['photoFile', 'file', 'types' => 'jpg, gif, png', 'allowEmpty' => true],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['id, name, description', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations(): array
    {
        return [
            'questions' => [self::HAS_MANY, Question::class, 'townId'],
            'questionsCount' => [self::STAT, Question::class, 'townId'],
            'yurists' => [self::HAS_MANY, User::class, 'townId', 'condition' => 'role=' . User::ROLE_JURIST],
            'region' => [self::BELONGS_TO, Region::class, 'regionId'],
            'country' => [self::BELONGS_TO, Country::class, 'countryId'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'region' => 'Регион',
            'country' => 'Страна',
            'description' => 'Описание',
            'alias' => 'Псевдоним на транслите',
            'description1' => 'Описание 1',
            'description2' => 'Описание 2',
            'seoTitle' => 'SEO Title',
            'seoDescription' => 'SEO Description',
            'seoKeywords' => 'SEO Keywords',
            'photo' => 'Фотография',
            'photoFile' => 'Файл с фотографией (минимум 1000х300 пикселей)',
            'regionId' => 'ID региона',
            'countryId' => 'ID страны',
            'isCapital' => 'Столица региона',
            'buyPrice' => 'Базовая цена покупки лида',
            'sellPrice' => 'Базовая цена продажи лида',
        ];
    }

    /**
     * Возвращает название города по его id в формате "Город (регион)".
     *
     * @param int $id id города
     *
     * @return string Название города + регион
     */
    public static function getName($id)
    {
        $model = self::model()->with('region')->findByPk((int)$id);

        return $model->name . ' (' . $model->region->name . ')';
    }

    /**
     *   возвращает массив городов, отсортированный по уменьшению населения.
     *
     * @return array массив. ключи - id городов, значения - названия + названия регионов
     */
    public static function getTownsIdsNames()
    {
        $townsArray = [];

        $towns = Yii::app()->db->cache(300)->createCommand()
            ->select('t.id, t.name, r.name regionName')
            ->from('{{town}} t')
            ->leftJoin('{{region}} r', 'r.id = t.regionId')
            ->order('t.size DESC')
            ->queryAll();

        $townsArray = [];

        foreach ($towns as $town) {
            $townsArray[$town['id']] = $town['name'] . ' (' . $town['regionName'] . ')';
        }

        $townsArray = [0 => 'Не указан'] + $townsArray;

        return $townsArray;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('country', $this->country, true);
        $criteria->compare('alias', $this->alias, true);
        // для поиска городов с описанием и без:
        if (0 === $this->description) {
            $criteria->addCondition("description=''");
        } elseif (1 === $this->description) {
            $criteria->addCondition("description!=''");
        }

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
    }

    /**
     * Возвращает SEO title для страницы города.
     *
     * @return string SEO title
     */
    public function createPageTitle()
    {
        if (!empty($this->seoTitle)) {
            $pageTitle = $this->seoTitle;
        } else {
            $pageTitle = 'Консультация юриста в городе ' . CHtml::encode($this->name) . '. ' . CHtml::encode($this->region->name) . '. ';
        }

        return $pageTitle;
    }

    /**
     * Возвращает SEO Description для страницы города.
     *
     * @return string SEO Description
     */
    public function createPageDescription()
    {
        if (!empty($this->seoDescription)) {
            $pageDescription = $this->seoDescription;
        } else {
            $pageDescription = 'Консультация юриста по всем отраслям права в городе ' . CHtml::encode($this->name) . ', ' . CHtml::encode($this->region->name) . ', только профессиональные юристы и адвокаты.';
        }

        return $pageDescription;
    }

    /**
     * Возвращает SEO Keywords для страницы города.
     *
     * @return string SEO Keywords
     */
    public function createPageKeywords()
    {
        if (!empty($this->seoKeywords)) {
            $pageKeywords = $this->seoKeywords;
        } else {
            $pageKeywords = 'Консультация юриста, консультация адваоката, ' . CHtml::encode($this->name);
        }

        return $pageKeywords;
    }

    /**
     * возвращает URL фотографии города относительно корня сайта.
     *
     * @param string $size размер фотографии (full - большая, thumb - маленькая)
     *
     * @return string
     */
    public function getPhotoUrl($size = 'full')
    {
        $photoUrl = '';

        if ('full' == $size) {
            $photoUrl = self::TOWN_PHOTO_PATH . '/' . CHtml::encode($this->photo);
        } elseif ('thumb' == $size) {
            $photoUrl = self::TOWN_PHOTO_PATH . self::TOWN_PHOTO_THUMB_FOLDER . '/' . CHtml::encode($this->photo);
        }

        return $photoUrl;
    }

    /**
     * Возвращает массив соседних городов, расположенных
     * в радиусе $radius км. от данного города
     * за расстояние считается дистанция между центрами.
     *
     * @param float $radius Радиус поиска (км)
     *
     * @return array массив объектов App\models\Town
     */
    public function getCloseTowns($radius = 100, $limit = 10)
    {
        if (!$this->lat || !$this->lng) {
            return [];
        }

        $criteria = new CDbCriteria();
        $criteria->select = '*, SQRT(
            POW(110.6* (lat - ' . $this->lat . '), 2) +
            POW(110.6 * (' . $this->lng . ' - lng) * COS(lat / 57.3), 2)) AS distance';
        $criteria->having = 'distance < ' . $radius;
        $criteria->order = 'isCapital DESC, name ASC';
        $criteria->limit = $limit;
        $criteria->condition = 'id!=' . $this->id;

        $towns = Town::model()->findAll($criteria);

        return $towns;
    }
}
