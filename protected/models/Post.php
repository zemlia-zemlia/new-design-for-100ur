<?php

/**
 * Модель для работы с постами публикаций
 *
 * The followings are the available columns in table '{{post}}':
 * @property integer $id
 * @property integer $authorId
 * @property string $title
 * @property string $text
 * @property string $preview
 * @property string $datetime
 * @property integer $rating
 * @property string $datePublication
 * @property string $description
 */
class Post extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Post the static model class
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
		return '{{post}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('authorId, title, text, preview', 'required'),
                array('authorId, rating', 'numerical', 'integerOnly'=>true),
                array('title', 'length', 'max'=>256),
                array('description', 'safe'),
                // The following rule is used by search().
                // Please remove those attributes that should not be searched.
                array('id, authorId, title, text, datetime, rating, preview', 'safe', 'on'=>'search'),
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
                'comments'      =>  array(self::HAS_MANY, 'PostComment', 'postId'),
                'commentsCount' =>  array(self::STAT, 'PostComment', 'postId'),
                'author'        =>  array(self::BELONGS_TO, 'User', 'authorId'),
                'ratingHistory' =>  array(self::HAS_MANY, 'PostRatingHistory', 'postId'),
                'viewsCount'    =>  array(self::HAS_ONE, 'Postviews', 'postId'),
                'categories'    =>  array(self::MANY_MANY, 'Postcategory', '{{post2cat}}(postId, catId)'),
            );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
            return array(
                'id' => 'ID',
                'authorId' => 'ID автора',
                'title' => 'Заголовок',
                'text' => 'Текст',
                'preview' => 'Вступление',
                'datetime' => 'Время создания',
                'rating' => 'Рейтинг',
                'datePublication'   =>  'Дата публикации',
                'description'   =>  'SEO description',
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
            $criteria->compare('authorId',$this->authorId);
            $criteria->compare('title',$this->title,true);
            $criteria->compare('text',$this->text,true);
            $criteria->compare('datetime',$this->datetime,true);
            $criteria->compare('rating',$this->rating);

            return new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
            ));
	}
        
        protected function afterSave()
        {
            parent::afterSave();
            if($this->isNewRecord)
            {
                $connection=Yii::app()->db;
                $sqlInsert = "INSERT DELAYED INTO {{postviews}} (postId) VALUES('".$this->id."')";
                $commandInsert=$connection->createCommand($sqlInsert);
                if(!$commandInsert->execute())
                    throw new CHttpException(500,'Не удалось инициализировать счетчик просмотров.');
            }
            
        }
        
        // увеличивает число просмотров объявления на 1
        public function incrementCounter()
        {
            $connection = Yii::app()->db;

            $sqlUpdate = "UPDATE {{postviews}} SET views=views+1 where postId='".$this->id."'";
            $commandUpdate=$connection->createCommand($sqlUpdate);
            $commandUpdate->execute(); 
   
        }
        
        /* метод возвращает массив похожих постов
         * @todo необходимо продумать алгоритм и реализацию выборки
         */
        public function getRelatedPosts()
        {
            $relatedPostsCriteria = new CDbCriteria();
            $postCategories = $this->categories;
            
            // заполняем массив $categoriesIds категориями, к которым принадлежит пост
            $categoriesIds = array();
            foreach($postCategories as $category) {
                $categoriesIds[] = $category->id;
            }
                        
            //$relatedPostsSql = "SELECT * FROM {{post}} p LEFT JOIN {{post2cat}} p2c ON p.id = p2c.postId ORDER BY p.rating DESC LIMIT 5";
            $relatedPostsCommand = Yii::app()->db->createCommand()
                    ->select('id, title')
                    ->from('{{post}} p')
                    ->join('{{post2cat}} p2c', 'p.id = p2c.postId')
                    ->where(array('and', 'p.id!=' . $this->id, array('in', 'p2c.catId', $categoriesIds)))
                    ->group('p.id')
                    ->order('p.rating DESC')
                    ->limit(5);
            $relatedPostsRaw = $relatedPostsCommand->queryAll();
            // получили ассоциативный массив $relatedPostsRaw с информацией о постах
            
            // в массиве $relatedPosts будем хранить эту информацию в виде объектов класса Post
            $relatedPosts = array();
            foreach($relatedPostsRaw as $postRaw) {
                 $relatedPost = new Post;
                 $relatedPost->attributes = $postRaw;
                 $relatedPost->id = $postRaw['id']; // id не присваивается массово
                 $relatedPosts[] = $relatedPost;
            }
            return $relatedPosts;
            
        }
        
        /*
         * статический метод, возвращающий массив самых популярных постов (объекты класса Post)
         * если указана категория $categoryId, поиск ведется в ней
         */
        public static function getPopularPosts($categoryId = NULL)
        {
            //$popularPostsSql = "SELECT * FROM {{post}} p LEFT JOIN {{post2cat}} p2c ON p.id = p2c.postId ORDER BY p.rating DESC LIMIT 5";
            $popularPostsCommand = Yii::app()->db->createCommand()
                    ->select('id, title')
                    ->from('{{post}} p')
                    ->join('{{post2cat}} p2c', 'p.id = p2c.postId')
                    ->group('p.id')
                    ->order('p.rating DESC')
                    ->limit(10);
            if($categoryId !== NULL) {
                $popularPostsCommand->where('p2c.catId = :catId', array(':catId'=>(int)$categoryId));
            }
            $popularPostsRaw = $popularPostsCommand->queryAll();
            
            // в массиве $popularPosts будем хранить эту информацию в виде объектов класса Post
            $popularPosts = array();
            foreach($popularPostsRaw as $postRaw) {
                 $popularPost = new Post;
                 $popularPost->attributes = $postRaw;
                 $popularPost->id = $postRaw['id']; // id не присваивается массово
                 $popularPosts[] = $popularPost;
            }
            return $popularPosts;
        }
        
        
        /*
         * статический метод, возвращающий массив последних постов (объекты класса Post)
         * если указана категория $categoryId, поиск ведется в ней
         */
        public static function getRecentPosts($categoryId = NULL, $number = 4)
        {
            $recentPostsCommand = Yii::app()->db->createCommand()
                    ->select('id, title, preview')
                    ->from('{{post}} p')
                    ->group('p.id')
                    ->order('p.datePublication DESC')
                    ->where('p.datePublication<NOW()')
                    ->limit($number);
            if($categoryId !== NULL) {
                $recentPostsCommand->join('{{post2cat}} p2c', 'p.id = p2c.postId');
                $recentPostsCommand->where('p2c.catId = :catId', array(':catId'=>(int)$categoryId));
            }
            $recentPostsRaw = $recentPostsCommand->queryAll();
            
            // в массиве $popularPosts будем хранить эту информацию в виде объектов класса Post
            $recentPosts = array();
            foreach($recentPostsRaw as $postRaw) {
                 $recentPost = new Post;
                 $recentPost->attributes = $postRaw;
                 $recentPost->id = $postRaw['id']; // id не присваивается массово
                 $recentPosts[] = $recentPost;
            }
            return $recentPosts;
        }
        
        
        /* изменение рейтинга поста на величину $delta с записью в таблицу истории изменений рейтингов постов
         * в случае успеха возвращает новый рейтинг, в противном случае - NULL
         */
        public function changeRating($delta=0)
        {
            $this->rating+=$delta;
            
            $ratingLog = new PostRatingHistory();
            $ratingLog->postId = $this->id;
            $ratingLog->delta = (int)$delta;
            $ratingLog->userId = Yii::app()->user->id;
            
            if($ratingLog->save()) {
                return $this->rating;
            } else {
                return null;
            }
        }
        
        protected function beforeSave()
        {
            $this->update_timestamp = date('Y-m-d H:i:s');
            return parent::beforeSave();
        }
}
