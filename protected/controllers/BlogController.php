<?php

class BlogController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//frontend/question';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // разрешаем всем пользователям просматривать категории
                'actions' => array('index', 'view', 'rss'),
                'users' => array('*'),
            ),
            array('allow', // разрешаем зарегистрированным пользователям следить за категориями
                'actions' => array('follow'),
                'users' => array('@'),
            ),
            array('allow', // разрешаем модератору создавать, редактировать и удалять категории
                'actions' => array('create', 'update', 'admin', 'delete'),
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_ROOT . ')',
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Отображает категорию публикаций
     * @deprecated since version number
     */
    public function actionView($id) {
        //define(YII_DEBUG,true);

        $dependency = new CDbCacheDependency('SELECT MAX(datetime) FROM {{post}}');

        $model = Postcategory::model()->cache(1000, $dependency)->with('posts', 'posts.author', 'posts.categories', 'posts.commentsCount')->findByPk($id, array('order' => 'posts.datetime DESC'));
        $postsDataProvider = new CArrayDataProvider($model->posts, array(
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));

        // узнаем, подписан ли текущий пользователь на текущую категорию
        $catFollowing = $model->isUserFollowingCategory();

        $this->render('view', array(
            'model' => $model,
            'postsDataProvider' => $postsDataProvider,
            'catFollowing' => $catFollowing,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Postcategory;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Postcategory'])) {
            $model->attributes = $_POST['Postcategory'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Postcategory'])) {
            $model->attributes = $_POST['Postcategory'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        
//        $posts = Post::model()->findAll();
//        foreach($posts as $post) {
//            $post->alias = mb_substr(CustomFuncs::translit($post->title), 0, 200, 'utf-8');
//            $post->alias = preg_replace("/[^a-zA-Z0-9\-]/ui", '', $post->alias);
//            $post->save();
//        }
        
        
        $dataProvider = new CActiveDataProvider('Post', array(
            'criteria' => array(
                'with' => array('commentsCount', 'author', 'viewsCount'),
                'order' => 't.datePublication DESC',
                'condition' => 't.datePublication<NOW()'
            )
        ));


        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Postcategory('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Postcategory']))
            $model->attributes = $_GET['Postcategory'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Postcategory the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Postcategory::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Postcategory $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'postcategory-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    // подписка/отписка от категории
    public function actionFollow($id) {
        // проверка на существование категории
        $category = Postcategory::model()->findByPk($id);
        if (!$category) {
            throw new CHttpException(400, 'Категория не найдена');
        }
        // получаем объект связи категория-подписчик
        $cat2follower = Cat2follower::model()->findByAttributes(array('catId' => $category->id, 'userId' => Yii::app()->user->id));
        if ($cat2follower !== NULL) {
            // объект найден, удаляем его, чтобы отписать пользователя от категории
            Cat2follower::model()->deleteAllByAttributes(array('catId' => $category->id, 'userId' => Yii::app()->user->id));
            $this->redirect(array('blog/view', 'id' => $category->id));
        } else {
            // объект не найден, создаем и сохраняем его
            $cat2follower = new Cat2follower();
            $cat2follower->userId = Yii::app()->user->id;
            $cat2follower->catId = $category->id;
            if ($cat2follower->save()) {
                $this->redirect(array('blog/view', 'id' => $category->id));
            } else {
                throw new CHttpException(500, 'Не удалось сохранить отслеживание категории');
            }
        }
    }

    // generates RSS 2.0 feed with active posts
    public function actionRss() {
        $criteria = new CDbCriteria;
        $criteria->order = "t.datePublication DESC";
        $criteria->condition = "t.datePublication<NOW()";
        $posts = Post::model()->cache(600)->findAll($criteria);

        Yii::import('ext.feed.*');
        // RSS 2.0 is the default type
        $feed = new EFeed();

        $feed->title = Yii::app()->name;
        $feed->description = 'Юридические статьи';


        $feed->addChannelTag('language', 'ru-ru');
        $feed->addChannelTag('pubDate', date(DATE_RSS, time()));
        $feed->addChannelTag('link', 'https://100yuristov.com/blog/rss');

        // * self reference
        //$feed->addChannelTag('atom:link','http://www.100yuristov.com/question/rss');

        foreach ($posts as $post) {
            $item = $feed->createNewItem();


            $item->title = CHtml::encode($post->title);


            $item->link = Yii::app()->createUrl('post/view', array('id' => $post->id));
            //$item->date = time();
            $item->date = strtotime($post->datePublication);
            $item->description = Yii::app()->createUrl('post/view', array('id' => $post->id)) . " " . CHtml::encode($post->preview);

            $feed->addItem($item);
        }
        /*
          $questionsCriteria = new CDbCriteria();
          $questionsCriteria->condition = 'status=' . Question::STATUS_PUBLISHED;
          $questionsCriteria->order = 'id desc';
          $questionsCriteria->limit = 50;
          $questions = Question::model()->cache(600)->findAll($questionsCriteria);
          foreach($questions as $question)
          {
          $item = $feed->createNewItem();


          $item->title = CHtml::encode($question->title);


          $item->link = Yii::app()->createUrl('question/view',array('id'=>$question->id));
          //$item->date = time();
          $item->date = strtotime($question->publishDate);
          $item->description = mb_substr($question->questionText,0,300,'utf-8');

          $feed->addItem($item);
          }
         */
        $feed->generateFeed();
        Yii::app()->end();
    }

}
