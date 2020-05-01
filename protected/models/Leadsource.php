<?php

namespace App\models;

use CActiveDataProvider;
use CActiveRecord;
use CDbCriteria;
use Yii;

/**
 * Модель для работы с источниками лидов 100 юристов.
 *
 * The followings are the available columns in table '{{leadsource}}':
 *
 * @property int $id
 * @property int $type
 * @property string $name
 * @property string $description
 * @property int $noLead
 * @property int $active
 * @property string $appId
 * @property string $secretKey
 * @property int $userId
 * @property int $moderation
 * @property int $priceByPartner
 */
class Leadsource extends CActiveRecord
{
    const TYPE_LEAD = 1; // источник для привлечения лидов
    const TYPE_QUESTION = 2; // источник для привлечения вопросов

    /**
     * Returns the static model of the specified AR class.
     *
     * @param string $className active record class name
     *
     * @return Leadsource the static model class
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
        return '{{leadsource}}';
    }

    /**
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'leadsource';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['name', 'required', 'message' => 'Поле {attribute} не заполнено'],
            ['name, description', 'length', 'max' => 255],
            ['noLead, active, userId, type, moderation, priceByPartner', 'numerical', 'integerOnly' => true],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['id, name, description', 'safe', 'on' => 'search'],
            ['id, appId, secretKey', 'safe', 'on' => 'test'],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations()
    {
        return [
            'user' => [self::BELONGS_TO, User::class, 'userId'],
            'leads' => [self::HAS_MANY, Lead::class, 'sourceId'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Тип',
            'name' => 'Название',
            'description' => 'Описание',
            'noLead' => 'Клиенты сразу приходят на консультацию',
            'active' => 'Активность',
            'appId' => 'ID источника для API',
            'secretKey' => 'Секретный ключ для API',
            'userId' => 'ID пользователя',
            'moderation' => 'Требуется премодерация лидов',
            'priceByPartner' => 'Вебмастер назначает цену',
        ];
    }

    /**
     *  возвращает массив источников лидов, ключами которого являются ID, а значениями - названия.
     *
     * @param bool $showInactive показывать неактивные источники
     * @param int $cacheTime на сколько секунд кешировать
     *
     * @return array массив источников лидов (id => name)
     */
    public static function getSourcesArray($showInactive = true, $cacheTime = 60)
    {
        $attributes = [];

        if (false == $showInactive) {
            $attributes['active'] = 1;
        }

        $sources = self::model()->cache($cacheTime)->findAllByAttributes($attributes);

        $sourcesArray = [0 => 'Нет'];
        foreach ($sources as $source) {
            $sourcesArray[$source->id] = $source->name;
        }

        return $sourcesArray;
    }

    /**
     * Возвращает массив типов источников (code => name).
     */
    public static function getTypes()
    {
        return [
            self::TYPE_LEAD => 'лиды',
            self::TYPE_QUESTION => 'вопросы',
        ];
    }

    /**
     * Возвращает название типа источника.
     *
     * @return string
     */
    public function getTypeName()
    {
        $types = self::getTypes();

        return $types[$this->type];
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
        $criteria->compare('office', $this->office, true);
        $criteria->compare('description', $this->description, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Генерирует appId.
     */
    public function generateAppId()
    {
        $this->appId = mt_rand(1000000, 9999999);
    }

    /**
     * Генерирует secretKey.
     *
     * @return string secretKey
     */
    public function generateSecretKey()
    {
        $this->secretKey = md5($this->id . mt_rand(1000000, 9999999) . time());
    }

    /**
     * Возвращает массив источников, привязанных к пользователю.
     *
     * @param int $userId ID пользователя
     *
     * @return array Массив источников
     */
    public static function getSourcesByUser($userId)
    {
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(['userId' => $userId]);

        $sources = self::model()->findAll($criteria);

        return $sources;
    }

    /**
     * @param int $userId
     * @return array
     */
    public static function getSourcesArrayByUser($userId): array
    {
        $sourcesArray = [];

        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(['userId' => $userId, 'type' => 1]);

        $sources = self::model()->findAll($criteria);
        foreach ($sources as $source) {
            $sourcesArray[$source->id] = $source->name;
        }

        return $sourcesArray;
    }

    /**
     * @param int $appId
     * @param int $cacheTime
     *
     * @return array|null
     * @throws \CException
     */
    public static function getByAppIdAsArray($appId, $cacheTime):?array
    {
        $source = Yii::app()->db->cache($cacheTime)->createCommand()
            ->select('*')
            ->from('{{leadsource}}')
            ->where('appId=:appId AND active=1', [':appId' => $appId])
            ->queryRow();

        return $source;
    }

    /**
     * @param int $days
     * @return array
     * @throws CException
     */
    public static function getActiveSourcesWithUserAsArray($days = 5): array
    {
        return self::getSourcesWithUserAsArray(true, $days);
    }

    /**
     * @param int $days
     * @return array
     * @throws CException
     */
    public static function getInactiveSourcesWithUserAsArray($days = 5): array
    {
        return self::getSourcesWithUserAsArray(false, $days);
    }

    /**
     * @param bool $active Вернуть только активные источники, в которых есть лиды за последние $days дней
     * @param int $days
     * @return array
     * @throws CException
     */
    private static function getSourcesWithUserAsArray($active = true, $days = 5): array
    {
        /*
        Запрос для получения источников, времени последнего лида и пользователя
        SELECT s.id, s.name, MAX(l.question_date) last_lead_time, u.id, u.name
        FROM 100_leadsource s
        LEFT JOIN 100_lead l ON l.sourceId=s.id
        LEFT JOIN 100_user u ON s.userId=u.id
        GROUP BY s.id
        HAVING last_lead_time > NOW()-INTERVAL 5 DAY
        order by s.id DESC
        */

        $queryBuilder = Yii::app()->db->createCommand()
            ->select("s.id, s.name, MAX(l.question_date) last_lead_time, u.id user_id, u.name user_name")
            ->from("{{leadsource}} s")
            ->leftJoin("{{lead}} l", "l.sourceId=s.id")
            ->leftJoin("{{user}} u", "s.userId=u.id")
            ->group("s.id")
            ->order('s.id DESC');

        $havingCondition = ($active == true) ?
            "last_lead_time >= NOW()-INTERVAL :days DAY" :
            "last_lead_time < NOW()-INTERVAL :days DAY";


        $queryBuilder->having($havingCondition, [
            ':days' => (int)$days,
        ]);

        return $queryBuilder->queryAll();
    }

}
