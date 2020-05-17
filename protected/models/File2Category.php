<?php

namespace App\models;

use CActiveDataProvider;
use CActiveRecord;
use CDbCriteria;

/**
 * This is the model class for table "{{file2category}}".
 *
 * The followings are the available columns in table '{{file2category}}':
 *
 * @property int $id
 * @property int $file_id
 * @property int $category_id
 *
 * The followings are the available model relations:
 * @property Docs         $file
 * @property FileCategory $category
 */
class File2Category extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{docs2category}}';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['file_id, category_id', 'required'],
            ['file_id, category_id', 'numerical', 'integerOnly' => true],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, file_id, category_id', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations()
    {
        return [
            'file' => [self::BELONGS_TO, Docs::class, 'file_id'],
            'category' => [self::BELONGS_TO, FileCategory::class, 'category_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_id' => 'File',
            'category_id' => 'Category',
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('file_id', $this->file_id);
        $criteria->compare('category_id', $this->category_id);

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
     * @return File2Category the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
