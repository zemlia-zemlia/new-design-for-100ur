<?php

class CodecsController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     *             using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//frontend/main';

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
            ['allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => ['index', 'view', 'path'],
                'users' => ['*'],
            ],
            ['allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['create', 'update'],
                'users' => ['@'],
            ],
            ['allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => ['admin', 'delete'],
                'users' => ['admin'],
            ],
            ['deny',  // deny all users
                'users' => ['*'],
            ],
        ];
    }

    /**
     * Displays a particular model.
     *
     * @param int $id the ID of the model to be displayed
     */
    public function actionView()
    {
        $realPath = $_SERVER['REQUEST_URI'];
        $path = str_replace('/', '|', trim($realPath, '/'));

        $model = Codecs::model()->findByAttributes(['path' => $path]);

        if (!$model) {
            throw new CHttpException(404, 'Страница не найдена');
        }

        if ($model->isfolder) {
            $this->render('viewCat', [
                        'model' => $model,
                ]);
        } else {
            $this->render('view', [
                    'model' => $model,
                ]);
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Codecs();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Codecs'])) {
            $model->attributes = $_POST['Codecs'];
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

        if (isset($_POST['Codecs'])) {
            $model->attributes = $_POST['Codecs'];
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
        $codecsArray = Codecs::model()->findAllByAttributes(['isfolder' => 1, 'parent' => 0]);
        $this->render('index', [
                    'codecsArray' => $codecsArray,
            ]);
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Codecs('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Codecs'])) {
            $model->attributes = $_GET['Codecs'];
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
     * @return Codecs the loaded model
     *
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Codecs::model()->findByPk($id);
        if (null === $model) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     *
     * @param Codecs $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && 'codecs-form' === $_POST['ajax']) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionPath()
    {
        ini_set('memory_limit', '1024M');
        $parents = Codecs::model()->findAllByAttributes(['parent' => 0]);

        foreach ($parents as $parent) {
            $parent->getPath('codecs');
        }
    }
}
