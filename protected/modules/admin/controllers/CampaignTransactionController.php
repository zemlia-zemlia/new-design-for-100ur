<?php

use App\models\TransactionCampaign;
use App\models\UserStatusRequest;

class CampaignTransactionController extends Controller
{
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
        $criteria = new CDbCriteria();
        $criteria->order = 'id DESC';
        $criteria->addCondition('sum<0');
        $criteria->addCondition(['type =' . TransactionCampaign::TYPE_JURIST_MONEYOUT]);

        $dataProvider = new CActiveDataProvider('App\models\TransactionCampaign', [
            'criteria' => $criteria,
        ]);

        $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * изменение статуса заявки через AJAX.
     */
    public function actionChange()
    {
        $requestId = (isset($_POST['id'])) ? (int) $_POST['id'] : false;
        $requestVerified = (isset($_POST['status'])) ? (int) $_POST['status'] : false;
        $accountId = (int) Yii::app()->request->getParam('accountId');

        if (!$requestId || !$requestVerified) {
            echo json_encode(['code' => 400, 'message' => 'Wrong data']);
            Yii::app()->end();
        }

        $request = TransactionCampaign::model()->findByPk($requestId);

        if (!$request) {
            echo json_encode(['code' => 400, 'message' => 'Request not found']);
            Yii::app()->end();
        }

        if ($request->approveRequest($accountId)) {
            echo json_encode(['code' => 0, 'id' => $request->id, 'message' => 'OK']);
        } else {
            echo json_encode(['code' => 500, 'message' => 'Could not save request' . print_r($request->errors)]);
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param int $id the ID of the model to be loaded
     *
     * @return TransactionCampaign the loaded model
     *
     * @throws CHttpException
     */
    public function loadModel($id): TransactionCampaign
    {
        $model = TransactionCampaign::model()->findByPk($id);
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
