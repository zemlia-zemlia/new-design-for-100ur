<?php

namespace App\models;

use CActiveDataProvider;
use CActiveRecord;
use CDbCriteria;
use Yii;

/**
 * Модель для работы с просмотрами постов.
 *
 * Счетчики просмотров хранятся в отдельной таблице БД
 *
 * The followings are the available columns in table '{{postviews}}':
 *
 * @property string $postId
 * @property string $views
 */
class Postviews extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     *
     * @param string $className active record class name
     *
     * @return Postviews the static model class
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
        return '{{postviews}}';
    }

    /**
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'postviews';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['postId', 'required'],
            ['postId, views', 'length', 'max' => 11],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['postId, views', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'postId' => 'Post',
            'views' => 'Views',
        ];
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

        $criteria->compare('postId', $this->postId, true);
        $criteria->compare('views', $this->views, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }
}
