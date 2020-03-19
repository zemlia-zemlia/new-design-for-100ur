<?php

use App\models\Cat2follower;
use App\models\Post;
use App\models\Postcategory;
use App\models\User;

class BlogController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     *             using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//admin/main';

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
            [
                'allow',
                'actions' => ['index', 'view', 'follow', 'create', 'update'],
                'users' => ['@'],
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_EDITOR . ')',
            ],
            [
                'allow',
                'actions' => ['admin', 'delete'],
                'users' => ['@'],
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_ROOT . ')',
            ],
            [
                'deny',
                'users' => ['*'],
            ],
        ];
    }

    /**
     * Отображает категорию публикаций.
     */
    public function actionView($id)
    {
        $dependency = new CDbCacheDependency('SELECT MAX(datetime) FROM {{post}}');

        $model = Post::model()->cache(1000, $dependency)->findByPk($id);

        $this->render('view', [
            'model' => $model,
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
        $dataProvider = new CActiveDataProvider(Post::class, [
            'criteria' => [
                'with' => ['commentsCount', 'author', 'viewsCount'],
                'order' => 't.datePublication DESC',
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
}
