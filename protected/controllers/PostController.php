<?php

class PostController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//frontend/main';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
            return array(
                    'accessControl', // perform access control for CRUD operations
            );
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
            return array(
                array('allow',  // allow all users to perform 'index' and 'view' actions
                        'actions'=>array('index','view','postingDenied'),
                        'users'=>array('*'),
                ),
                array('allow', // allow authenticated user to perform 'create' and 'update' actions
                        'actions'=>array('create','update','vote'),
                        'users'=>array('@'),
                ),
                array('allow', // allow admin user to perform 'admin' and 'delete' actions
                        'actions'=>array('admin','delete'),
                        'expression'=>'Yii::app()->user->checkAccess(' . User::ROLE_ROOT . ')',
                ),
                array('deny',  // deny all users
                        'users'=>array('*'),
                ),
            );
	}

	/**
	 * вывод отдельного поста
	 */
	public function actionView($id)
	{
		            
                $dependency = new CDbCacheDependency('SELECT MAX(datetime) FROM {{postComment}} WHERE postId='.(int)$id);
                
                $model = Post::model()->cache(180, $dependency, 1)->with(array('categories',
                        'author',
                        'comments'  =>  array('order'=>'comments.datetime DESC'),
                        'commentsCount',
                        'comments.author'   =>  array('alias' => 'comment_author'), // избавляемся от конфликта имен при запросе
                    ))->findByPk($id);
                
                if(!$model) {
                    throw new CHttpException(404,'Публикация не найдена');
                }
                
                $commentsDataProvider = new CArrayDataProvider($model->comments);
                
                $relatedPosts = $model->getRelatedPosts();
                
                // узнаем, голосовал ли текущий пользователь за данный пост
                $postLiked = false;
                if(!Yii::app()->user->isGuest) {
                    $postRatingRecord = PostRatingHistory::model()->findByAttributes(array(
                        'postId'    =>  $id,
                        'userId'    =>  Yii::app()->user->id,
                    ));
                    if($postRatingRecord) $postLiked = true;
                }
                
                $postCommentModel = new PostComment; // модель комментария поста
                if(isset($_POST['PostComment']))
		{
                    $postCommentModel->attributes=$_POST['PostComment'];
                    $postCommentModel->authorId = Yii::app()->user->id;
                    $postCommentModel->postId = $model->id;
                    if($postCommentModel->save()) {
                        /* если комментарий добавлен, делаем редирект на страницу поста
                         * просто отрендерить представление нельзя, в этом случае новый комментарий не отобразится,
                         * так как комментарии были извлечены в коде выше
                         */
                        $this->redirect(array('view','id'=>$model->id));
                    }
                }
                
                // увеличиваем счетчик просмотров поста на 1
                $model->incrementCounter();
                
                $this->render('view',array(
			'model'                 =>  $model,
                        'commentsDataProvider'  =>  $commentsDataProvider,
                        'postCommentModel'      =>  $postCommentModel,
                        'relatedPosts'          =>  $relatedPosts,
                        'postLiked'             =>  $postLiked,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
            // проверка, подтвердил ли текущий пользователь свой email
            $currentUser = User::model()->findByPk(Yii::app()->user->id);
            if(!$currentUser || $currentUser->active == 0) {
                $this->redirect(array('postingDenied'));
            }
            
            $model = new Post;

            /*
             * получаем массив категорий публикаций. 
             * id => title
             * для использования в форме выбора категорий для нового поста
             */
            $categoriesArray = Array();
            $allCategoriesArray = Postcategory::model()->findAll(array('select'=>'id, title', 'order'=>'title'));
            foreach($allCategoriesArray as $cat)
            {
                $categoriesArray[$cat->id] = CHtml::encode($cat->title);
            }

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if(isset($_POST['Post']))
            {
                $model->attributes=$_POST['Post'];
                $model->authorId = Yii::app()->user->id;

                if($model->save()) {
                    if(isset($_POST['Post']['categories'])) {
                        foreach($_POST['Post']['categories'] as $categoryId)
                        {
                            $past2cat = new Post2cat();
                            $past2cat->postId = $model->id;
                            $past2cat->catId = $categoryId;
                            if(!$past2cat->save()) {
                            }
                        }
                    } else {
                        // если не указана категория поста
                        $past2cat = new Post2cat();
                        $past2cat->postId = $model->id;
                        $past2cat->catId = Postcategory::NO_CATEGORY;
                        if($past2cat->save());
                    }
                    $this->redirect(array('view','id'=>$model->id));
                }
            }

            $this->render('create',array(
                    'model'             =>  $model,
                    'categoriesArray'   =>  $categoriesArray,
            ));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

                if($model->authorId !== Yii::app()->user->id || !Yii::app()->user->checkAccess(User::ROLE_ROOT)) {
                    throw new CHttpException(403, 'Отказано в доступе: Вы не можете редактировать этот пост'); 
                }
                /*
                 * получаем массив категорий публикаций. 
                 * id => title
                 * для использования в форме выбора категорий для нового поста
                 */
                $categoriesArray = Array();
                $allCategoriesArray = Postcategory::model()->findAll(array('select'=>'id, title', 'order'=>'title'));
                foreach($allCategoriesArray as $cat)
                {
                    $categoriesArray[$cat->id] = CHtml::encode($cat->title);
                }
                
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Post']))
		{
                    $model->attributes=$_POST['Post'];

                    if($model->save()) {
                        if(isset($_POST['Post']['categories'])) {
                            // удалим старые привязки поста к категориям
                            Post2cat::model()->deleteAllByAttributes(array('postId'=>$model->id));
                            // привяжем пост к категориям
                            foreach($_POST['Post']['categories'] as $categoryId)
                            {
                                $past2cat = new Post2cat();
                                $past2cat->postId = $model->id;
                                $past2cat->catId = $categoryId;
                                if(!$past2cat->save()) {
                                }
                            }
                        } else {
                            $past2cat = new Post2cat();
                            $past2cat->postId = $model->id;
                            $past2cat->catId = Postcategory::NO_CATEGORY;
                            if($past2cat->save());
                        }

                        $this->redirect(array('view','id'=>$model->id));
                    }
		}

		$this->render('update',array(
			'model'             =>  $model,
                        'categoriesArray'   =>  $categoriesArray,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/blog'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
            $this->redirect(array('/category'), true, 301);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Post('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Post']))
			$model->attributes=$_GET['Post'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Post the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Post::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Post $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='post-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        
        
        /*
         * like/dislike поста
         * если текущий пользователь лайкнул этот пост, убираем лайк (рейтинг -1)
         * если не лайкнул, повышаем рейтинг (+1)
         */
        public function actionVote($id)
        {
            
            if(!Yii::app()->user->id) {
                throw new CHttpException(403, 'Голосовать за рейтинг могут только зарегистрированные пользователи');
            }
            
            $post = Post::model()->findByPk($id);
            if(!$post) {
                throw new CHttpException(400, 'Пост не найден, изменение рейтинга не удалось');
            }
            
            // возвращает запись из таблицы истории изменений рейтинга для поста с id=$id
            // которая сделана пользователем с id= id текущего пользователя
            $ratingsLogRecord = PostRatingHistory::model()->findByAttributes(array(
                    'userId'    =>  Yii::app()->user->id,
                    'postId'    =>  $post->id,
                ));
            
            if($ratingsLogRecord) {
                // запись о лайке существует, понижаем рейтинг поста и стираем запись
                $post->rating = $post->rating-1;
                
                $ratingsLogRecord->delete();
            } else {
                // пользователь еще не лайкал этот пост. повышаем рейтинг на 1 и пишем запись об этом в лог
                if(!$post->changeRating(1)) {
                    throw new CHttpException(400, 'Изменение рейтинга поста не удалось');
                }
            }
         
            
            if($post->save()) {
                echo $post->rating;
            } else {
                throw new CHttpException(500, 'Не удалось изменить рейтинг поста');       
                
            }
            
        }
        
        public function actionPostingDenied()
        {
            $this->render('postingDenied');
        }
        
        
}