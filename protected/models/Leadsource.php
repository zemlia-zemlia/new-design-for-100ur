<?php

/**
 * Модель для работы с источниками лидов 100 юристов.
 *
 * The followings are the available columns in table '{{leadsource}}':
 *
 * @property int    $id
 * @property int    $type
 * @property string $name
 * @property string $description
 * @property int    $officeId
 * @property int    $noLead
 * @property int    $active
 * @property string $appId
 * @property string $secretKey
 * @property int    $userId
 * @property int    $moderation
 * @property int    $priceByPartner
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
            ['officeId, noLead, active, userId, type, moderation, priceByPartner', 'numerical', 'integerOnly' => true],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['id, name, description', 'safe', 'on' => 'search'],
            ['id', 'safe', 'on' => 'test'],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'user' => [self::BELONGS_TO, 'User', 'userId'],
            'leads' => [self::HAS_MANY, 'Lead', 'sourceId'],
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
            'officeId' => 'Офис',
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
     * @param int  $cacheTime    на сколько секунд кешировать
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
     * @return type
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

    public static function getSourcesArrayByUser($userId)
    {
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
     * @return mixed
     */
    public static function getByAppIdAsArray($appId, $cacheTime)
    {
        $source = Yii::app()->db->cache($cacheTime)->createCommand()
            ->select('*')
            ->from('{{leadsource}}')
            ->where('appId=:appId AND active=1', [':appId' => $appId])
            ->queryRow();

        return $source;
    }

    public static function getSourcesOrderByUser($active = false){
        if ($active) {
            $condition = 'l.id IS NOT NULL AND (l.question_date > NOW() - INTERVAL 5 DAY)';
        }
        else {
            $condition = 'l.id IS NOT NULL AND  (l.question_date < NOW() - INTERVAL 5 DAY)';
        }
        $criteria = new CDbCriteria();


        $criteria->join = 'LEFT JOIN  {{lead}} as l ON l.sourceId = t.id';
        $criteria->addCondition($condition);

        $criteria->order = 't.userId';




        $dataProvider = new CActiveDataProvider(
            'Leadsource',
            [
                'criteria' => $criteria,
                'pagination' => [
                    'pageSize' => 50,
                ], ]
        );

        return $dataProvider;

    }



}
