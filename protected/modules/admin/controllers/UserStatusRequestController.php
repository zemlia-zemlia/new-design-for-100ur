<?php

use App\models\UserFile;
use App\models\UserStatusRequest;

class UserStatusRequestController extends Controller
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
            ['allow',
                'actions' => ['index', 'view', 'create', 'update', 'change'],
                'users' => ['@'],
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
        $model = new UserStatusRequest();
        // модель для работы со сканом
        $userFile = new UserFile();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['App\models\UserStatusRequest'])) {
            $model->attributes = $_POST['App\models\UserStatusRequest'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $this->render('create', [
            'model' => $model,
            'userFile' => $userFile,
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

        if (isset($_POST['App\models\UserStatusRequest'])) {
            $model->attributes = $_POST['App\models\UserStatusRequest'];
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
        $dataProvider = new CActiveDataProvider('App\models\UserStatusRequest', [
            'criteria' => [
                'order' => 'id DESC',
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
        $model = new UserStatusRequest('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['App\models\UserStatusRequest'])) {
            $model->attributes = $_GET['App\models\UserStatusRequest'];
        }

        $this->render('admin', [
            'model' => $model,
        ]);
    }

    // изменение статуса заявки и юриста через AJAX
    public function actionChange()
    {
        $requestId = (isset($_POST['id'])) ? (int) $_POST['id'] : false;
        $requestComment = (isset($_POST['requestComment'])) ? $_POST['requestComment'] : false;
        $requestVerified = (isset($_POST['status'])) ? (int) $_POST['status'] : false;

        if (!$requestId || !$requestVerified) {
            echo json_encode(['code' => 400, 'message' => 'Wrong data']);
            Yii::app()->end();
        }

        if (UserStatusRequest::STATUS_DECLINED == $requestVerified && !$requestComment) {
            echo json_encode(['code' => 400, 'message' => 'Comment not provided']);
            Yii::app()->end();
        }

        $request = UserStatusRequest::model()->with('user')->findByPk($requestId);

        if (!$request || !$request->user) {
            echo json_encode(['code' => 400, 'message' => 'Request or user not found']);
            Yii::app()->end();
        }

        // обновляем запрос
        $request->isVerified = $requestVerified;
        $request->comment = $requestComment;

        if ($request->save()) {
            // если запрос сохранился, обновляем данные юриста, если запрос был одобрен
            if (UserStatusRequest::STATUS_ACCEPTED == $requestVerified) {
                $yuristSettings = $request->user->settings;
                if (!$yuristSettings) {
                    echo json_encode(['code' => 400, 'id' => $request->id, 'message' => 'user settings not found']);
                    Yii::app()->end();
                }

               // Если подтверждаем фирму, меняем сеттингс
               if ('createCompany' == $request->status) {
                   $request->createCompany();
               }

                // присваиваем пользователю новый статус, помечаем его как верифицированный
                $yuristSettings->status = $request->status;
                $yuristSettings->isVerified = 1;
                $yuristSettings->vuz = $request->vuz;
                $yuristSettings->facultet = $request->facultet;
                $yuristSettings->education = $request->education;
                $yuristSettings->vuzTownId = $request->vuzTownId;
                $yuristSettings->educationYear = $request->educationYear;

                if ($yuristSettings->save()) {
                    $request->sendNotification();
                    echo json_encode(['code' => 0, 'id' => $request->id, 'message' => 'OK']);
                    Yii::app()->end();
                } else {
                    echo json_encode(['code' => 500, 'id' => $request->id, 'message' => 'Could not save yurist settings']);
                    Yii::app()->end();
                }
            } else {
                $request->sendNotification();
                echo json_encode(['code' => 0, 'id' => $request->id, 'message' => 'OK']);
                Yii::app()->end();
            }
        } else {
            echo json_encode(['code' => 500, 'message' => 'Could not save request']);
            Yii::app()->end();
        }

//            print_r($request->attributes);
//            print_r($request->user->attributes);
//            print_r($request->user->settings->attributes);
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
