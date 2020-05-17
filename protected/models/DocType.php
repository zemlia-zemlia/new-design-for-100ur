<?php

namespace App\models;

use CActiveDataProvider;
use CActiveRecord;
use CDbCriteria;
use Yii;

/**
 * This is the model class for table "{{docType}}".
 *
 * The followings are the available columns in table '{{docType}}':
 *
 * @property int    $id
 * @property int    $class
 * @property string $name
 * @property int    $minPrice
 */
class DocType extends CActiveRecord
{
    const CLASS_BUSINESS = 1; // Регистрация бизнеса
    const CLASS_DEAL = 2; // Договоры и соглашения
    const CLASS_COURT = 3; // Документы в суд
    const CLASS_COMPLAIN = 4; // Претензии потребителей
    const CLASS_AUTHORITY = 5; // Жалоба на чиновника
    const CLASS_OTHER = 6; // Другое

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{doctype}}';
    }

    /**
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'doctype';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['name, class', 'required', 'message' => 'Поле {attribute} должно быть заполнено'],
            ['minPrice, class', 'numerical', 'integerOnly' => true],
            ['name', 'length', 'max' => 255],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, name, minPrice', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations()
    {
        return [];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class' => 'Раздел',
            'name' => 'Наименование',
            'minPrice' => 'Минимальная цена',
        ];
    }

    /**
     * возвращает массив, ключами которого являются коды классов, а значениями - названия.
     *
     * @return array массив классов
     */
    public static function getClassesArray()
    {
        return [
            self::CLASS_BUSINESS => [
                'name' => 'Регистрация бизнеса',
                'description' => 'Комплекты документов для регистрации ООО, ИП, ТСЖ и др.',
            ],
            self::CLASS_DEAL => [
                'name' => 'Договоры и соглашения',
                'description' => 'Договоры аренды, подряда, купли-продажи, займа, комиссии и др',
            ],
            self::CLASS_COURT => [
                'name' => 'Документы в суд',
                'description' => 'Исковое заявление, отзыв на исковое заявление, ходатайство, жалоба на решение суда и др.',
            ],
            self::CLASS_COMPLAIN => [
                'name' => 'Претензии потребителей',
                'description' => 'Претензии на возврат денег за товар. Претензии в страховую, в банк, к ЖКХ и др.',
            ],
            self::CLASS_AUTHORITY => [
                'name' => 'Жалоба на чиновника',
                'description' => 'Жалоба на действия должностного лица, судебного пристава, сотрудника ГИБДД и др.',
            ],
            self::CLASS_OTHER => [
                'name' => 'Другое',
                'description' => 'Любой другой документ. Вы можете описать его самостоятельно.',
            ],
        ];
    }

    /**
     * возвращает название класса для объекта.
     *
     * @return string название статуса
     */
    public function getClassName()
    {
        $classesArray = self::getClassesArray();

        return $classesArray[$this->class]['name'];
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

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('minPrice', $this->minPrice);

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
     * @return DocType the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
