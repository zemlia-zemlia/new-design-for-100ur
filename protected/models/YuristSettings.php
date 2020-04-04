<?php

namespace App\models;

use CActiveDataProvider;
use CActiveRecord;
use CDbCriteria;
use Yii;

/**
 * Модель для работы с дополнительными настройками юриста.
 *
 * Поля в таблице '{{yuristSettings}}':
 *
 * @property int $yuristId
 * @property string $alias
 * @property int $startYear
 * @property string $description
 * @property string $hello
 * @property int $status
 * @property int $isVerified
 * @property int $vuz
 * @property int $facultet
 * @property int $education
 * @property int $vuzTownId
 * @property int $educationYear
 * @property int $advOrganisation
 * @property int $advNumber
 * @property int $position
 * @property int $site
 * @property int $priceConsult
 * @property int $priceDoc
 * @property string $phoneVisible
 * @property string $emailVisible
 * @property string $inn
 * @property string $companyName
 * @property string $address
 * @property int $subscribeQuestions
 * @property int $rang
 */
class YuristSettings extends CActiveRecord
{
    // статусы пользователя
    const STATUS_NOTHING = 0; // нет статуса
    const STATUS_YURIST = 1; // юрист
    const STATUS_ADVOCAT = 2; // адвокат
    const STATUS_JUDGE = 3; // судья
    const STATUS_COMPANY = 4; // Юр. Фирма

    const SUBSCRIPTION_NOTHING = 0; // нет подписок
    const SUBSCRIPTION_TOWN = 1; // подписка на вопросы своего города
    const SUBSCRIPTION_REGION = 2; // подписка на вопросы региона

    public $statusNew;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{yuristSettings}}';
    }

    /**
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'yuristSettings';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['yuristId', 'required'],
            ['inn, companyName, address', 'safe'],
            ['yuristId, startYear, isVerified, status, vuzTownId, educationYear, priceConsult, priceDoc, subscribeQuestions, rang', 'numerical', 'integerOnly' => true],
            ['alias', 'length', 'max' => 255],
            ['alias', 'match', 'pattern' => '/^([а-яa-zА-ЯA-Z0-9ёЁ\-. ])+$/u', 'message' => 'В псевдониме могут присутствовать буквы, цифры, точка, дефис и пробел'],
            ['site', 'match', 'pattern' => '/^(https?:\/\/)?([\dа-яёЁa-z\.-]+)\.([а-яёЁa-z\.]{2,6})([\/\w \.-]*)*\/?$/u', 'message' => 'В адресе сайта присутствуют недопустимые символы'],
            ['description, hello, vuz, facultet, education, advOrganisation, advNumber, position', 'safe'],
            ['emailVisible', 'email', 'message' => 'В Email допускаются латинские символы, цифры, точка и дефис'],
            ['phoneVisible', 'match', 'pattern' => '/^([0-9\- \(\)\+])+$/u', 'message' => 'В номере телефона разрешены цифры, скобки, пробелы и дефисы'],

            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['yuristId, alias, startYear, description', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations(): array
    {
        return [
            'user' => [self::BELONGS_TO, User::class, 'yuristId'],
            'vuzTown' => [self::BELONGS_TO, Town::class, 'vuzTownId'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'yuristId' => 'Юрист',
            'alias' => 'Псевдоним',
            'startYear' => 'Год начала работы',
            'description' => 'Описание',
            'hello' => 'Приветствие',
            'status' => 'Статус',
            'isVerified' => 'Верифицирован',
            'vuz' => 'ВУЗ',
            'facultet' => 'факультет',
            'education' => 'образование',
            'advOrganisation' => 'членство в адвокатском объединении',
            'advNumber' => 'номер в реестре адвокатов',
            'position' => 'должность',
            'vuzTownId' => 'город ВУЗа',
            'educationYear' => 'год окончания',
            'site' => 'сайт',
            'priceConsult' => 'консультация от',
            'priceDoc' => 'составление документа от',
            'phoneVisible' => 'Общедоступный телефон',
            'emailVisible' => 'Общедоступный Email',
            'subscribeQuestions' => 'Получать уведомления о вопросах',
            'rang' => 'Звание',
            'inn' => 'ИНН',
            'companyName' => 'Название компании',
            'address' => 'Адрес',
        ];
    }

    /**
     * возвращает массив, ключами которого являются коды статусов, а значениями - названия.
     *
     * @return array Массив статусов профессиональных пользователей (код => название)
     */
    public static function getStatusesArray()
    {
        return [
            self::STATUS_NOTHING => '',
            self::STATUS_YURIST => 'Юрист',
            self::STATUS_ADVOCAT => 'Адвокат',
            self::STATUS_COMPANY => 'Юридическая фирма',
        ];
    }

    /**
     * Возвращает название статуса пользователя.
     *
     * @return string Название статуса
     */
    public function getStatusName()
    {
        $statusesArray = self::getStatusesArray();
        $statusName = $statusesArray[$this->status];

        return $statusName;
    }

    /**
     * Статический метод, возвращающий название статуса по коду.
     *
     * @param int $code Код статуса
     *
     * @return string Название статуса
     */
    public static function getStatusNameByCode($code)
    {
        $statusesArray = self::getStatusesArray();
        $statusName = $statusesArray[$code];

        return $statusName;
    }

    /**
     * возвращает массив, ключами которого являются коды типов подписки на вопросы, а значениями - названия.
     *
     * @return array Массив типов подписки (код => название)
     */
    public static function getSubscriptionsArray()
    {
        return [
            self::SUBSCRIPTION_NOTHING => 'Не получать уведомления',
            self::SUBSCRIPTION_TOWN => 'Из моего города',
            self::SUBSCRIPTION_REGION => 'Из моего региона',
        ];
    }

    /**
     * Возвращает название типа подписки на вопросы.
     *
     * @return string Название статуса
     */
    public function getSubscriptionName()
    {
        $subscriptionsArray = self::getSubscriptionsArray();
        $subscriptionName = $subscriptionsArray[$this->subscribeQuestions];

        return $subscriptionName;
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('yuristId', $this->yuristId);
        $criteria->compare('alias', $this->alias, true);
        $criteria->compare('startYear', $this->startYear);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('town', $this->town);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className active record class name
     *
     * @return YuristSettings the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
