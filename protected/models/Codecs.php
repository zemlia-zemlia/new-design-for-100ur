<?php

namespace App\models;

use CActiveDataProvider;
use CActiveRecord;
use CDbCriteria;

/**
 * Модель для работы с кодексами.
 *
 * The followings are the available columns in table '{{codecs}}':
 *
 * @property int    $id
 * @property string $pagetitle
 * @property string $longtitle
 * @property string $description
 * @property string $alias
 * @property int    $parent
 * @property int    $isfolder
 * @property string $introtext
 * @property string $content
 * @property string $menutitle
 * @property string $path
 */
class Codecs extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{codecs}}';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['parent, isfolder', 'numerical', 'integerOnly' => true],
            ['pagetitle, longtitle, description, alias, menutitle', 'length', 'max' => 255],
            ['introtext, content, path', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, pagetitle, longtitle, description, alias, parent, isfolder, introtext, content, menutitle', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'children' => [self::HAS_MANY, 'App\models\Codecs', 'parent', 'order' => 'children.id'],
            'parentElement' => [self::BELONGS_TO, 'App\models\Codecs', 'parent'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pagetitle' => 'Pagetitle',
            'longtitle' => 'Longtitle',
            'description' => 'Description',
            'alias' => 'Alias',
            'parent' => 'Parent',
            'isfolder' => 'Isfolder',
            'introtext' => 'Introtext',
            'content' => 'Content',
            'menutitle' => 'Menutitle',
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
        $criteria->compare('pagetitle', $this->pagetitle, true);
        $criteria->compare('longtitle', $this->longtitle, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('alias', $this->alias, true);
        $criteria->compare('parent', $this->parent);
        $criteria->compare('isfolder', $this->isfolder);
        $criteria->compare('introtext', $this->introtext, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('menutitle', $this->menutitle, true);

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
     * @return Codecs the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * рекурсивная функция, находящая путь до элемента кодексов и записывающая его в этот элемент
     *
     * @param string $prefix
     *
     * @return string путь до элемента
     */
    public function getPath($prefix = '')
    {
        $this->path = $prefix . '|' . $this->alias;
        echo $this->path . '<br />';
        $this->save();

        $children = $this->children;

        foreach ($children as $child) {
            $child->getPath($this->path);
        }
    }

    /**
     * Функция получения массива родителей элемента.
     *
     * @return array массив родителей
     */
    public function getParents()
    {
        $path = $this->path;
        $pathArray = explode('|', $path);
        $parents = [];

        while ($parentAlias = array_pop($pathArray)) {
            if ('codecs' == $parentAlias) {
                break;
            }
            $parentPath = implode('|', $pathArray); // путь из массива, оставшегося после удаления последнего элемента
            //echo $parentPath . ':' . '<br />';

            $parent = self::model()->findByAttributes(['path' => $parentPath]);

            if ($parent) {
                $parents += [str_replace('|', '/', $parentPath) => $parent->pagetitle];
            }
        }

        return array_reverse($parents);
    }
}
