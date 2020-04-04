<?php

/**
 * Модель для работы с комментариями к постам
 * @todo Класс не используется, удалить
 *
 * The followings are the available columns in table '{{postComment}}':
 *
 * @property int    $id
 * @property int    $postId
 * @property int    $authorId
 * @property string $text
 * @property string $datetime
 */
class PostComment extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     *
     * @param string $className active record class name
     *
     * @return PostComment the static model class
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
        return '{{postComment}}';
    }

    /**
     * @return string
     */
    public static function getFullTableName()
    {
        return Yii::app()->db->tablePrefix . 'postComment';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
                ['postId, authorId, text', 'required'],
                ['postId, authorId', 'numerical', 'integerOnly' => true],
                // The following rule is used by search().
                // Please remove those attributes that should not be searched.
                ['id, postId, authorId, text, datetime', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations():array
    {
        return [
            'post' => [self::BELONGS_TO, Post::class, 'postId'],
            'author' => [self::BELONGS_TO, User::class, 'authorId'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'postId' => 'ID поста',
            'authorId' => 'ID автора',
            'text' => 'Комментарий',
            'datetime' => 'Время создания',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('postId', $this->postId);
        $criteria->compare('authorId', $this->authorId);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('datetime', $this->datetime, true);

        return new CActiveDataProvider($this, [
                'criteria' => $criteria,
        ]);
    }
}
