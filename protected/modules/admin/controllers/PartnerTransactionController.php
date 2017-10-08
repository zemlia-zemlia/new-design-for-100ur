<?php

class PartnerTransactionController extends Controller {

    
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
        $criteria = new CDbCriteria();
        $criteria->order = 'id DESC';
        $criteria->addCondition('status=' . PartnerTransaction::STATUS_PENDING . ' AND sum<0');
        
        $dataProvider = new CActiveDataProvider('PartnerTransaction', array(
            'criteria' => $criteria,
        ));


        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    // изменение статуса заявки через AJAX
    public function actionChange() {
        $requestId = (isset($_POST['id'])) ? (int) $_POST['id'] : false;
        $requestVerified = (isset($_POST['status'])) ? (int) $_POST['status'] : false;

        if (!$requestId || !$requestVerified) {
            echo json_encode(array('code' => 400, 'message' => 'Wrong data'));
            exit;
        }

        $request = PartnerTransaction::model()->findByPk($requestId);

        if (!$request) {
            echo json_encode(array('code' => 400, 'message' => 'Request not found'));
            exit;
        }

        // обновляем запрос на вывод средств
        $request->status = $requestVerified;

        if ($request->save()) {
            if ($requestVerified == PartnerTransaction::STATUS_COMPLETE) {
                
                // если одобрили вывод средств, создаем транзакцию в кассе
                $moneyTransaction = new Money();
                $moneyTransaction->type = Money::TYPE_EXPENCE;
                $moneyTransaction->direction = 8; // выплаты вебмастерам
                $moneyTransaction->accountId = 0; // яндекс
                $moneyTransaction->value = abs($request->sum);
                $moneyTransaction->datetime = date('Y-m-d H:i:s');
                $moneyTransaction->comment = "Выплата вебмастеру id " . $request->partnerId;
                $moneyTransaction->save();
                
                
            } 
            
            echo json_encode(array('code' => 0, 'id' => $request->id, 'message' => 'OK'));
            exit;
            
        } else {
            echo json_encode(array('code' => 500, 'message' => 'Could not save request'));
            exit;
        }

    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return UserStatusRequest the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = PartnerTransaction::model()->findByPk($id);
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
