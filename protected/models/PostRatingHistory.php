<?php

namespace App\models;

use CActiveDataProvider;
use CActiveRecord;
use CDbCriteria;
use Yii;

/**
 * Модель для хранения истории рейтингов постов.
 *
 * The followings are the available columns in table '{{postRatingHistory}}':
 *
 * @property int    $postId
 * @property int    $userId
 * @property int    $delta
 * @property string $datetime
 */
class PostRatingHistory extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     *
     * @param string $className active record class name
     *
     * @return PostRatingHistory the static model class
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
        return '{{postRatingHistory}}';
    }

    /**
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'postRatingHistory';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['postId, userId', 'required'],
            ['postId, userId, delta', 'numerical', 'integerOnly' => true],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['postId, userId, delta, datetime', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations(): array
    {
        return [
            'post' => [self::BELONGS_TO, Post::class, 'postId'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'postId' => 'ID поста',
            'userId' => 'ID пользователя',
            'delta' => 'Изменение рейтинга',
            'datetime' => 'Дата и время',
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

        $criteria->compare('postId', $this->postId);
        $criteria->compare('userId', $this->userId);
        $criteria->compare('delta', $this->delta);
        $criteria->compare('datetime', $this->datetime, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }
}
