<?php

class UserStatusRequestController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     *             using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//frontend/smart';

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
            ['allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['index', 'view', 'create', 'update'],
                'users' => ['@'],
            ],
            ['allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => ['admin', 'delete'],
                'users' => ['admin'],
            ],
            ['deny', // deny all users
                'users' => ['*'],
            ],
        ];
    }

    /**
     * Displays a particular model.
     *
     * @param int $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', [
            'model' => $this->loadModel($id),
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        if (Yii::app()->user->role != User::ROLE_JURIST) {
            throw new CHttpException(404, 'Авторизуйтесь под юристом.');
        }

        ini_set('upload_max_filesize', '10M');
        $model = new UserStatusRequest();

        // модель для работы со сканом
        $userFile = new UserFile();

        if (isset($_POST['UserStatusRequest'])) {
            $post = $_POST['UserStatusRequest'];
            $model->attributes = $_POST['UserStatusRequest'];

            $model->yuristId = Yii::app()->user->id;
            $model->inn = $post['inn'];
            $model->companyName = $post['companyName'];
            $model->address = $post['address'];

//            CVarDumper::dump($model->getAttributes(),5,true);

            switch ($model->status) {
                case YuristSettings::STATUS_YURIST:
                    $model->scenario = 'createYurist';
                    break;
                case YuristSettings::STATUS_ADVOCAT:
                    $model->scenario = 'createAdvocat';
                    break;
                case YuristSettings::STATUS_COMPANY:
                    $model->scenario = 'createCompany';
                    break;
            }


           if ($model->validate()) {
               // загрузка скана
               if (!empty($_FILES) && !$model->errors && 'createYurist' == $model->scenario) {
                   $scan = CUploadedFile::getInstance($userFile, 'userFile');
                   if ($scan && 0 == $scan->getError()) { // если файл нормально загрузился
                       $scanFileName = md5($scan->getName() . $scan->getSize() . mt_rand(10000, 100000)) . '.' . $scan->getExtensionName();

                       $scan->saveAs(Yii::getPathOfAlias('webroot') . UserFile::USER_FILES_FOLDER . '/' . $scanFileName);

                       $userFile->userId = Yii::app()->user->id;
                       $userFile->name = $scanFileName;
                       $userFile->type = $model->status;

                       if (!$userFile->save()) {
                           echo 'Не удалось сохранить скан';
                           StringHelper::printr($userFile->errors);
                           Yii::app()->end();
                       } else {
                           // после сохранения файла сохраним ссылку на него в объекте запроса
                           $model->fileId = $userFile->id;
                       }
                   } else {
                       $modelHasErrors = true;
                   }
               }


               // Если подтверждаем юриста, проверим, что он загрузил скан
               if ('createYurist' == $model->scenario && !$scan) {
                   $userFile->addError('userFile', 'Не загружен файл со сканом/фото диплома');
                   $modelHasErrors = true;
               }

               // Если подтверждаем фирму, меняем сеттингс
               if ('createCompany' == $model->scenario) {
                   $model->createCompany();
               }
           }


            if (!$model->errors && !$modelHasErrors && $model->save()) {
                $this->redirect(['/user']);
            }
        }

        $currentUser = User::model()->with('settings')->findByPk(Yii::app()->user->id);

        $this->render('create', [
            'model' => $model,
            'userFile' => $userFile,
            'currentUser' => $currentUser,
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

        if (isset($_POST['UserStatusRequest'])) {
            $model->attributes = $_POST['UserStatusRequest'];
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
        $dataProvider = new CActiveDataProvider('UserStatusRequest');
        $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new UserStatusRequest('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['UserStatusRequest'])) {
            $model->attributes = $_GET['UserStatusRequest'];
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
     * @return UserStatusRequest the loaded model
     *
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = UserStatusRequest::model()->findByPk($id);
        if (null === $model) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     *
     * @param UserStatusRequest $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && 'user-status-request-form' === $_POST['ajax']) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
