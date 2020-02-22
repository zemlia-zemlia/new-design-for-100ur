<?php

/**
 * в этой модели хранится информация обо всех изменениях кармы пользователей
 * This is the model class for table "{{karmaChange}}".
 *
 * The followings are the available columns in table '{{karmaChange}}':
 *
 * @property int $id
 * @property int $userId
 * @property int $authorId
 * @property string $datetime
 */
class KarmaChange extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName(): string
    {
        return '{{karmaChange}}';
    }

    /**
     * @return string
     */
    public static function getFullTableName(): string
    {
        return Yii::app()->db->tablePrefix . 'karmaChange';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules(): array
    {
        return [
            ['userId, authorId, datetime', 'required'],
            ['userId, authorId', 'numerical', 'integerOnly' => true],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, userId, authorId, datetime', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations(): array
    {
        return [
            'user' => [self::BELONGS_TO, 'User', 'userId'],
            'author' => [self::BELONGS_TO, 'User', 'authorId'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'userId' => 'User',
            'authorId' => 'Author',
            'datetime' => 'Datetime',
        ];
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
    public function search(): CActiveDataProvider
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('userId', $this->userId);
        $criteria->compare('authorId', $this->authorId);
        $criteria->compare('datetime', $this->datetime, true);

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
     * @return KarmaChange the static model class
     */
    public static function model($className = __CLASS__): KarmaChange
    {
        return parent::model($className);
    }
}
