<?php

class BlogController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     *             using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//frontend/blog';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        ];
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     *
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            ['allow', // разрешаем всем пользователям просматривать категории
                'actions' => ['index', 'view', 'rss'],
                'users' => ['*'],
            ],
            ['allow', // разрешаем зарегистрированным пользователям следить за категориями
                'actions' => ['follow'],
                'users' => ['@'],
            ],
            ['allow', // разрешаем модератору создавать, редактировать и удалять категории
                'actions' => ['create', 'update', 'admin', 'delete'],
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_ROOT . ')',
            ],
            ['deny', // deny all users
                'users' => ['*'],
            ],
        ];
    }

    /**
     * Отображает категорию публикаций.
     *
     * @deprecated since version number
     */
    public function actionView($id)
    {
        //define(YII_DEBUG,true);

        $dependency = new CDbCacheDependency('SELECT MAX(datetime) FROM {{post}}');

        $model = Postcategory::model()->cache(1000, $dependency)->with('posts', 'posts.author', 'posts.categories', 'posts.commentsCount')->findByPk($id, ['order' => 'posts.datetime DESC']);
        $postsDataProvider = new CArrayDataProvider($model->posts, [
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        // узнаем, подписан ли текущий пользователь на текущую категорию
        $catFollowing = $model->isUserFollowingCategory();

        $this->render('view', [
            'model' => $model,
            'postsDataProvider' => $postsDataProvider,
            'catFollowing' => $catFollowing,
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Postcategory();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Postcategory'])) {
            $model->attributes = $_POST['Postcategory'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Postcategory'])) {
            $model->attributes = $_POST['Postcategory'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     *
     * @param int $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['admin']);
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
//        $posts = Post::model()->findAll();
//        foreach($posts as $post) {
//            $post->alias = mb_substr(CustomFuncs::translit($post->title), 0, 200, 'utf-8');
//            $post->alias = preg_replace("/[^a-zA-Z0-9\-]/ui", '', $post->alias);
//            $post->save();
//        }

        $dataProvider = new CActiveDataProvider('Post', [
            'criteria' => [
                'with' => ['commentsCount', 'author', 'viewsCount'],
                'order' => 't.datePublication DESC',
                'condition' => 't.datePublication<NOW()',
            ],
        ]);

        $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Postcategory('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Postcategory'])) {
            $model->attributes = $_GET['Postcategory'];
        }

        $this->render('admin', [
            'model' => $model,
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param int $id the ID of the model to be loaded
     *
     * @return Postcategory the loaded model
     *
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Postcategory::model()->findByPk($id);
        if (null === $model) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     *
     * @param Postcategory $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && 'postcategory-form' === $_POST['ajax']) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    // подписка/отписка от категории
    public function actionFollow($id)
    {
        // проверка на существование категории
        $category = Postcategory::model()->findByPk($id);
        if (!$category) {
            throw new CHttpException(400, 'Категория не найдена');
        }
        // получаем объект связи категория-подписчик
        $cat2follower = Cat2follower::model()->findByAttributes(['catId' => $category->id, 'userId' => Yii::app()->user->id]);
        if (null !== $cat2follower) {
            // объект найден, удаляем его, чтобы отписать пользователя от категории
            Cat2follower::model()->deleteAllByAttributes(['catId' => $category->id, 'userId' => Yii::app()->user->id]);
            $this->redirect(['blog/view', 'id' => $category->id]);
        } else {
            // объект не найден, создаем и сохраняем его
            $cat2follower = new Cat2follower();
            $cat2follower->userId = Yii::app()->user->id;
            $cat2follower->catId = $category->id;
            if ($cat2follower->save()) {
                $this->redirect(['blog/view', 'id' => $category->id]);
            } else {
                throw new CHttpException(500, 'Не удалось сохранить отслеживание категории');
            }
        }
    }

    // generates RSS 2.0 feed with active posts
    public function actionRss()
    {
        $posts = Yii::app()->db->cache(600)->createCommand()
                ->select('id, datePublication, title, preview')
                ->from('{{post}}')
                ->where('datePublication<NOW()')
                ->order('datePublication DESC')
                ->queryAll();

        Yii::import('ext.feed.*');
        // RSS 2.0 is the default type
        $feed = new EFeed();

        $feed->title = Yii::app()->name;
        $feed->description = 'Юридические статьи';

        $feed->addChannelTag('language', 'ru-ru');
        $feed->addChannelTag('pubDate', date(DATE_RSS, time()));
        $feed->addChannelTag('link', Yii::app()->urlManager->baseUrl . '/blog/rss');

        foreach ($posts as $post) {
            $item = $feed->createNewItem();
            $item->title = CHtml::encode($post['title']);
            $item->link = Yii::app()->createUrl('post/view', ['id' => $post['id']]);
            $item->date = strtotime($post['datePublication']);
            $item->description = Yii::app()->createUrl('post/view', ['id' => $post['id']]) . ' ' . CHtml::encode($post['preview']);

            $feed->addItem($item);
        }
        $feed->generateFeed();
        Yii::app()->end();
    }
}
