<?php

class AnswerController extends Controller
{
    public $layout = '//admin/main';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + toSpam', // we only allow deletion via POST request
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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'getRandom'),
                'users' => array('@'),
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_JURIST . ') || Yii::app()->user->checkAccess(' . User::ROLE_OPERATOR . ') || Yii::app()->user->checkAccess(' . User::ROLE_SECRETARY . ')',
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('update', 'view', 'index', 'byPublisher', 'toSpam'),
                'users' => array('@'),
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_EDITOR . ')',
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'admin', 'delete', 'publish', 'setPubTime', 'payBonus'),
                'users' => array('@'),
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_ROOT . ')',
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $model = Answer::model()->findByPk($id);

        $this->render('view', array(
            'model' => $model,
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
        $oldStatus = $model->status;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Answer'])) {
            $model->attributes = $_POST['Answer'];

            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id, 'question_updated' => 'yes'));
            }
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
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $criteria = new CDbCriteria;
        $criteria->order = 't.id DESC';
        $criteria->with = 'author';
        $status = null;

        if (isset($_GET['status'])) {
            $status = (int)$_GET['status'];
            $criteria->addColumnCondition(array('t.status' => $status));
            $criteria->with = ['question', 'transaction'];
        }

        $dataProvider = new CActiveDataProvider('Answer', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'status' => $status,
        ));
    }


    public function actionPublish()
    {
        if (isset($_POST['id'])) {
            $id = (int)$_POST['id'];
        }

        $model = $this->loadModel($id);
        $model->status = Answer::STATUS_PUBLISHED;
        if ($model->save()) {
            echo CJSON::encode(array('id' => $id, 'status' => 1));
        } else {
            //print_r($model->errors);
            echo CJSON::encode(array('status' => 0));
        }
    }

    public function actionToSpam()
    {
        if (isset($_POST['id'])) {
            $id = (int)$_POST['id'];
        }
        $model = $this->loadModel($id);
        $model->status = Answer::STATUS_SPAM;
        if ($model->save()) {
            echo CJSON::encode(array('id' => $id, 'status' => 1));
        } else {
            //print_r($model->errors);
            echo CJSON::encode(array('status' => 0));
        }
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Answer the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Answer::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'Ответ не найден');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Question $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'question-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * @throws CHttpException
     */
    public function actionPayBonus()
    {
        if (!Yii::app()->request->isPostRequest) {
            throw new CHttpException(400, 'Разрешен только POST запрос');
        }

        $answerId = Yii::app()->request->getParam('id');

        $answer = $this->loadModel($answerId);

        if ($answer->payBonusForGoodAnswer()) {
            echo json_encode(['message' => 'ok', 'status' => 1, 'id' => $answerId]);
        }
    }
}
