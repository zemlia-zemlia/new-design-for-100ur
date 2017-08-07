<?php

class CabinetController extends Controller {

    public $layout = '//frontend/cabinet';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // разрешаем доступ только авторизованным пользователям
                'actions' => array('index', 'leads', 'viewLead', 'campaign', 'brakLead', 'transactions', 'topup'),
                'users' => array('@'),
                'expression' => 'Yii::app()->user->role == User::ROLE_BUYER',
            ),
            array('deny', // запрещаем все, что не разрешено
                'users' => array('*'),
            ),
        );
    }

    // главная страница кабинета
    public function actionIndex() {
        // выберем кампании текущего пользователя

        $myCampaigns = Campaign::getCampaignsForBuyer(Yii::app()->user->id);
        $myCampaignIds = array();

        foreach ($myCampaigns as $campaign) {
            $myCampaignIds[] = $campaign->id;
        }

        $criteria = new CDbCriteria;

        $criteria->addInCondition('campaignId', $myCampaignIds);
        $criteria->order = 'id DESC';

        $showInactive = true;

        $currentUser = User::model()->findByPk(Yii::app()->user->id);

        /* if(!isset($_GET['show_inactive'])) {
          $criteria->addColumnCondition(array('active'=>1));
          $showInactive = false;
          } else {
          $showInactive = true;
          } */

        $dataProvider = new CActiveDataProvider('Lead100', array(
            'criteria' => $criteria,
        ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'showInactive' => $showInactive,
            'currentUser' => $currentUser,
        ));
    }

    public function actionLeads() {

        $campaignId = (isset($_GET['campaign'])) ? $_GET['campaign'] : 0;
        $status = (isset($_GET['status'])) ? $_GET['status'] : false;

        if (!$campaignId) {
            throw new CHttpException(400, 'Не указана кампания');
        }

        $campaign = Campaign::model()->findByPk($campaignId);
        if (!$campaign || $campaign->buyerId != Yii::app()->user->id) {
            throw new CHttpException(403, 'Вы не можете просматривать лиды данной кампании');
        }

        $criteria = new CDbCriteria;

        // найдем лидов, проданных текущему пользователю
        $criteria->order = 'id DESC';
        $criteria->addColumnCondition(array('campaignId' => $campaignId));

        if ($status !== false) {
            $criteria->addColumnCondition(array('leadStatus' => (int) $status));
        }

        $dataProvider = new CActiveDataProvider('Lead100', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));
        $this->render('leads', array(
            'dataProvider' => $dataProvider,
            'campaign' => $campaign,
            'status' => $status,
        ));
    }

    public function actionViewLead($id) {
        $model = Lead100::model()->with('campaign')->findByPk($id);

        if ($model->campaign->buyerId != Yii::app()->user->id) {
            throw new CHttpException(403, 'Вы не можете просматривать этого лида');
        }

        $this->render('viewLead', array(
            'model' => $model,
        ));
    }

    public function actionCampaign($id) {
        $campaign = Campaign::model()->with('transactions')->findByPk($id);

        if ($campaign && $campaign->buyerId != Yii::app()->user->id) {
            throw new CHttpException(403, 'Вы не можете просматривать эту кампанию');
        }

        $transactionsDataProvider = new CArrayDataProvider($campaign->transactions);

        $this->render('viewCampaign', array(
            'model' => $campaign,
            'transactionsDataProvider' => $transactionsDataProvider,
        ));
    }

    /**
     * Страница пополнения баланса покупателя
     */
    public function actionTopup() {
        $this->render('topup', array());
    }

    public function actionTransactions() {

        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('buyerId' => Yii::app()->user->id));
        $criteria->order = 'id DESC';

        $currentUser = User::model()->findByPk(Yii::app()->user->id);

        $transactionsDataProvider = new CActiveDataProvider('TransactionCampaign', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));

        $this->render('transactions', array(
            'transactionsDataProvider' => $transactionsDataProvider,
            'currentUser' => $currentUser,
        ));
    }

    // отбраковка лида покупателем
    public function actionBrakLead() {
        $reason = isset($_POST['reason']) ? (int) $_POST['reason'] : 0;
        $reasonComment = isset($_POST['reasonComment']) ? CHtml::encode($_POST['reasonComment']) : '';
        $leadId = isset($_POST['leadId']) ? (int) $_POST['leadId'] : 0;

        if (!$leadId || !$reason || !$reasonComment) {
            echo json_encode(array('code' => 400, 'message' => 'Ошибка, не заполнены все поля формы'));
            exit;
        }

        $lead = Lead100::model()->findByPk($leadId);

        if (!$lead) {
            echo json_encode(array('code' => 404, 'message' => 'Лид не найден'));
            exit;
        }

        if (!$lead->campaign || $lead->campaign->buyerId != Yii::app()->user->id) {
            echo json_encode(array('code' => 403, 'message' => 'Вы не можете редактировать этого лида'));
            exit;
        }
        
        if (!(!is_null($lead->deliveryTime) && (time() - strtotime($lead->deliveryTime) < 86400 * Yii::app()->params['leadHoldPeriodDays']))) {
            echo json_encode(array('code' => 403, 'message' => 'Нельзя отправить на отбраковку лид, отправленный покупателю более 3 суток назад'));
            exit;
        }

        $lead->leadStatus = Lead100::LEAD_STATUS_NABRAK;
        $lead->brakReason = (int) $reason;
        $lead->brakComment = $reasonComment;

        if ($lead->save()) {
            echo json_encode(array('code' => 0, 'id' => $leadId, 'message' => 'Лид отправлен на отбраковку'));
            exit;
        } else {
            echo json_encode(array('code' => 400, 'id' => $leadId, 'message' => 'Ошибка: не удалось отправить лид на отбраковку'));
            exit;
        }
    }

}
