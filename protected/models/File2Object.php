<?php

/**
 * This is the model class for table "{{file2object}}".
 *
 * The followings are the available columns in table '{{file2object}}':
 *
 * @property int $id
 * @property int $file_id
 * @property int $object_id
 * @property int $object_type
 *
 * The followings are the available model relations:
 * @property Docs $file
 */
class File2Object extends CActiveRecord
{



    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{docs2object}}';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['file_id, object_id, object_type', 'required'],
            ['file_id, object_id, object_type', 'numerical', 'integerOnly' => true],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, file_id, object_id, object_type', 'safe', 'on' => 'search'],
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
            'object_id' => 'Object',
            'object_type' => 'Object Type',
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
        $criteria->compare('object_id', $this->object_id);
        $criteria->compare('object_type', $this->object_type);

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
     * @return File2Object the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
