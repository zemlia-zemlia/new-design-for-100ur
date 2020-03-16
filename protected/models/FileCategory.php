<?php

namespace App\models;

use CActiveDataProvider;
use CActiveRecord;
use CDbCriteria;

/**
 * This is the model class for table "{{file_category}}".
 *
 * The followings are the available columns in table '{{file_category}}':
 *
 * @property int $id
 * @property string $name
 * @property int $lft
 * @property int $rgt
 * @property int $root
 * @property int $level
 * @property string $description
 *
 * The followings are the available model relations:
 * @property File2category[] $file2categories
 */
class FileCategory extends CActiveRecord
{
    public function actionAttachFileToObject()
    {
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{docs_category}}';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['name', 'length', 'max' => 255],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, name, lft, active, rgt, root, level, description', 'safe'],
        ];
    }

    public function behaviors()
    {
        return [
            'nestedSetBehavior' => [
                'class' => 'ext.yiiext.behaviors.model.trees.NestedSetBehavior',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'levelAttribute' => 'level',
                'hasManyRoots' => true,
            ],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations()
    {
        return [
            'file2categories' => [self::HAS_MANY, File2Category::class, 'category_id'],
            'files' => [self::HAS_MANY, Docs::class, 'file_id', 'through' => 'file2categories'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'root' => 'Root',
            'level' => 'Level',
            'description' => 'Описание',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('lft', $this->lft);
        $criteria->compare('rgt', $this->rgt);
        $criteria->compare('root', $this->root);
        $criteria->compare('level', $this->level);

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
     * @return FileCategory the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getParentObj()
    {
        return $this->ancestors()->findAll()[0];
    }
}
