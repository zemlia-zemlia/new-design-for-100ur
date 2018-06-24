<?php

class UserStatusRequestController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//admin/main';

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
            array('allow',
                'actions' => array('index', 'view', 'create', 'update', 'change'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new UserStatusRequest;
        // модель для работы со сканом
        $userFile = new UserFile;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['UserStatusRequest'])) {
            $model->attributes = $_POST['UserStatusRequest'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
            'userFile' => $userFile,
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

        if (isset($_POST['UserStatusRequest'])) {
            $model->attributes = $_POST['UserStatusRequest'];
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
        $dataProvider = new CActiveDataProvider('UserStatusRequest', array(
            'criteria' => array(
                'order' => 'id DESC',
            ),
        ));


        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new UserStatusRequest('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['UserStatusRequest']))
            $model->attributes = $_GET['UserStatusRequest'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    // изменение статуса заявки и юриста через AJAX
    public function actionChange() {
        $requestId = (isset($_POST['id'])) ? (int) $_POST['id'] : false;
        $requestComment = (isset($_POST['requestComment'])) ? $_POST['requestComment'] : false;
        $requestVerified = (isset($_POST['status'])) ? (int) $_POST['status'] : false;

        if (!$requestId || !$requestVerified) {
            echo json_encode(array('code' => 400, 'message' => 'Wrong data'));
            Yii::app()->end();
        }

        if ($requestVerified == UserStatusRequest::STATUS_DECLINED && !$requestComment) {
            echo json_encode(array('code' => 400, 'message' => 'Comment not provided'));
            Yii::app()->end();
        }

        $request = UserStatusRequest::model()->with('user')->findByPk($requestId);

        if (!$request || !$request->user) {
            echo json_encode(array('code' => 400, 'message' => 'Request or user not found'));
            Yii::app()->end();
        }

        // обновляем запрос
        $request->isVerified = $requestVerified;
        $request->comment = $requestComment;

        if ($request->save()) {
            // если запрос сохранился, обновляем данные юриста, если запрос был одобрен
            if ($requestVerified == UserStatusRequest::STATUS_ACCEPTED) {
                $yuristSettings = $request->user->settings;
                if (!$yuristSettings) {
                    echo json_encode(array('code' => 400, 'id' => $request->id, 'message' => 'user settings not found'));
                    Yii::app()->end();
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
                    echo json_encode(array('code' => 0, 'id' => $request->id, 'message' => 'OK'));
                    Yii::app()->end();
                } else {
                    echo json_encode(array('code' => 500, 'id' => $request->id, 'message' => 'Could not save yurist settings'));
                    Yii::app()->end();
                }
            } else {
                $request->sendNotification();
                echo json_encode(array('code' => 0, 'id' => $request->id, 'message' => 'OK'));
                Yii::app()->end();
            }
        } else {
            echo json_encode(array('code' => 500, 'message' => 'Could not save request'));
            Yii::app()->end();
        }


//            print_r($request->attributes);
//            print_r($request->user->attributes);
//            print_r($request->user->settings->attributes);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return UserStatusRequest the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = UserStatusRequest::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param UserStatusRequest $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-status-request-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
