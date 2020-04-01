<?php

namespace App\models;

use CActiveDataProvider;
use CActiveRecord;
use CDbCriteria;
use Yii;

/**
 * This is the model class for table "{{expence}}".
 * Хранит данные о расходах по датам: контекстная реклама, звонки и т.д.
 *
 * The followings are the available columns in table '{{expence}}':
 *
 * @property int $id
 * @property string $date
 * @property int $expences
 * @property string $comment
 * @property int $type
 */
class Expence extends CActiveRecord
{
    const TYPE_DIRECT = 0; // Расходы на директ
    const TYPE_CALLS = 1; // Расходы на входящие звонки

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{expence}}';
    }

    /**
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'expence';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['date, expences', 'required'],
            ['type', 'numerical', 'integerOnly' => true],
            ['expences', 'length', 'max' => 6],
            ['comment', 'length', 'max' => 255],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, date, expences', 'safe', 'on' => 'search'],
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
            'date' => 'Дата',
            'expences' => 'Расход',
            'comment' => 'Комментарий',
            'type' => 'Статья',
        ];
    }

    /**
     * Возвращает массив типов расходов.
     *
     * @return array Типы расходов [код => Наименование]
     */
    public static function getTypes()
    {
        return [
            self::TYPE_DIRECT => 'Директ',
            self::TYPE_CALLS => 'Звонки',
        ];
    }

    /**
     * Возвращает название типа расхода для текущего объекта.
     *
     * @return string
     */
    public function getTypeName()
    {
        $allTypes = self::getTypes();

        return $allTypes[$this->type];
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
        $criteria->compare('date', $this->date, true);
        $criteria->compare('expences', $this->expences * 100, true);

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
     * @return Expence the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
