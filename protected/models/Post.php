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
 * @property string $photo
 * @property string $description
 */
class Post extends CActiveRecord
{
	public $photoFile;
        const PHOTO_PATH = "/upload/blogphoto";
        const PHOTO_THUMB_FOLDER = "/thumbs";
        
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
                array('id, authorId, rating', 'numerical', 'integerOnly'=>true),
                array('title, photo', 'length', 'max'=>256),
                array('description, datePublication', 'safe'),
                array('photoFile', 'file', 'types'=>'jpg, gif, png', 'allowEmpty'=>true),
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
                'photo'         =>  'Фотография',
                'photoFile'     =>  'Файл с фотографией (минимум 1000х700 пикселей)',
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
        
        /**
         * Метод, вызываемый после сохранения записи
         * @throws CHttpException
         */
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
        
        /**
         *  увеличивает число просмотров поста на 1
         */
        public function incrementCounter()
        {
            $connection = Yii::app()->db;

            $sqlUpdate = "UPDATE {{postviews}} SET views=views+1 where postId='".$this->id."'";
            $commandUpdate=$connection->createCommand($sqlUpdate);
            $commandUpdate->execute(); 
   
        }
        
        /**
         *  метод возвращает массив похожих постов
         * @todo необходимо продумать алгоритм и реализацию выборки
         * @return array массив похожих постов
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
        
        /**
         * статический метод, возвращающий массив самых популярных постов (объекты класса Post)
         * если указана категория $categoryId, поиск ведется в ней
         * 
         * @param int $categoryId id категории
         * @return array массив самых популярных постов
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
        
        
        /**
         * статический метод, возвращающий массив последних постов (объекты класса Post)
         * если указана категория $categoryId, поиск ведется в ней
         * 
         * @param int $categoryId id категории
         * @param int $number лимит выборки
         * @return array массив последних постов
         */
        public static function getRecentPosts($categoryId = NULL, $number = 4)
        {
            $recentPostsCommand = Yii::app()->db->createCommand()
                    ->select('id, title, preview, datePublication, photo')
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
                 $recentPost = new Post();
                 $recentPost->id = $postRaw['id']; // id не присваивается массово
                 $recentPost->attributes = $postRaw;
                 $recentPosts[] = $recentPost;
            }
            return $recentPosts;
        }
        
        
        /**
         *  изменение рейтинга поста на величину $delta с записью в таблицу истории изменений рейтингов постов
         * в случае успеха возвращает новый рейтинг, в противном случае - NULL
         * 
         * @param int $delta На какую величину изменить рейтинг
         * @return int|NULL новый рейтинг
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
        
        /**
         * Метод, вызываемый перед сохранением поста
         * 
         * @return boolean
         */
        protected function beforeSave()
        {
            $this->update_timestamp = date('Y-m-d H:i:s');
            return parent::beforeSave();
        }
        
        /**
         * возвращает URL фотографии поста относительно корня сайта
         * 
         * @param string $size Размер картинки full - большая, thumb - превью
         * @return string URL фотографии
         */
        public function getPhotoUrl($size='full')
        {
            $photoUrl = '';
                        
            if($size == 'full') {
                $photoUrl = self::PHOTO_PATH . '/' . CHtml::encode($this->photo);
            } elseif($size == 'thumb') {
                $photoUrl = self::PHOTO_PATH . self::PHOTO_THUMB_FOLDER . '/' . CHtml::encode($this->photo);
            }
            return $photoUrl;
        }
}
