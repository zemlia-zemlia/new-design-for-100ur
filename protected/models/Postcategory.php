<?php

/**
 * Модель для работы с категориями постов
 *
 * The followings are the available columns in table '{{postcategory}}':
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $alias
 * @property string $avatar
 */
class Postcategory extends CActiveRecord
{
	const NO_CATEGORY = 0; // пост без категории имеет категорию 0
    
        /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Postcategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
            return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
            return '{{postcategory}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                    array('title, description, alias', 'required'),
                    array('title, alias, avatar', 'length', 'max'=>256),
                    // The following rule is used by search().
                    // Please remove those attributes that should not be searched.
                    array('id, title, description, alias, avatar', 'safe', 'on'=>'search'),
            );
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
            // NOTE: you may need to adjust the relation name and the related
            // class name for the relations automatically generated below.
            return array(
                'posts' => array(self::MANY_MANY, 'Post', '{{post2cat}}(catId, postId)'),
            );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
            return array(
                'id'            => 'ID',
                'title'         => 'Название',
                'description'   => 'Описание',
                'alias'         => 'Символьный код',
                'avatar'        => 'Аватар',
            );
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
            // Warning: Please modify the following code to remove attributes that
            // should not be searched.

            $criteria=new CDbCriteria;

            $criteria->compare('id',$this->id);
            $criteria->compare('title',$this->title,true);
            $criteria->compare('description',$this->description,true);
            $criteria->compare('alias',$this->alias,true);
            $criteria->compare('avatar',$this->avatar,true);

            return new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
            ));
	}
        
        /**
         * определяет, подписан ли пользователь с id $userId на категорию
         * если пользователь не указан, берется id текущего авторизованного пользователя
         * 
         * @param int $userId id пользователя
         * @return boolean true, если подписан, false - если не подписан
         */
        public function isUserFollowingCategory($userId = NULL)
        {
            $userId = ($userId==NULL) ? Yii::app()->user->id : $userId;
            $followCategoryRow = Yii::app()->db->createCommand()
                    ->from('{{cat2follower}}')
                    ->where(array('and','userId=:userId','catId=:catId'),array(':userId'=>$userId,':catId'=>$this->id))
                    ->queryRow();
            
            $catFollowing = ($followCategoryRow) ? true : false;
            return $catFollowing;
        }
}
