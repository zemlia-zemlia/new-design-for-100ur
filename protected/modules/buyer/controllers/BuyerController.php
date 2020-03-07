<?php

use buyer\services\StatisticsService;

class BuyerController extends Controller
{
    public $layout = '//lk/main';

    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
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
            ['allow', // разрешаем доступ только авторизованным пользователям
                'actions' => ['index', 'leads', 'faq', 'viewLead', 'campaign', 'brakLead', 'transactions', 'topup', 'api', 'help', 'campaigns', 'myleads'],
                'users' => ['@'],
                'expression' => 'Yii::app()->user->role == User::ROLE_BUYER',
            ],
            ['deny', // запрещаем все, что не разрешено
                'users' => ['*'],
            ],
        ];
    }

    // главная страница кабинета
    public function actionIndex()
    {
        // выберем кампании текущего пользователя
        $dataProvider = (new BuyerRepository())->getBuyersCampaignsDataProvider(Yii::app()->user->id);
        $showInactive = true;
        $currentUser = Yii::app()->user->getModel();

        $buyerStatisticService = new StatisticsService(Yii::app()->user->id);

        $statPeriodDays = 30;
        $statsFromDate = (new DateTime())->modify('-' . ($statPeriodDays - 1) . ' day')->modify('midnight');
        $soldLeadsCount = $buyerStatisticService->getSoldLeadsCount($statsFromDate);
        $soldLeadsTotalExpences = $buyerStatisticService->getTotalExpences($statsFromDate);
        $averageExpencesPerDay = round($soldLeadsTotalExpences / $statPeriodDays);

        $this->render('index', [
            'dataProvider' => $dataProvider,
            'showInactive' => $showInactive,
            'currentUser' => $currentUser,
            'soldLeadsCount' => $soldLeadsCount,
            'averageExpencesPerDay' => $averageExpencesPerDay,
        ]);
    }

    public function actionLeads()
    {
        $campaignId = (isset($_GET['campaign'])) ? $_GET['campaign'] : 0;
        $status = (isset($_GET['status'])) ? $_GET['status'] : false;

        $campaign = Campaign::model()->findByPk($campaignId);
        if ($campaign && $campaign->buyerId != Yii::app()->user->id) {
            throw new CHttpException(403, 'Вы не можете просматривать лиды данной кампании');
        }

        $criteria = new CDbCriteria();

        // найдем лидов, проданных текущему пользователю
        $criteria->order = 'id DESC';

        $criteria->addColumnCondition(['campaignId' => $campaignId]);

        if (false !== $status) {
            $criteria->addColumnCondition(['leadStatus' => (int) $status]);
        }
        if (0 == $campaignId) {
            $criteria->addColumnCondition(['buyerId' => Yii::app()->user->id]);
        }

        $dataProvider = new CActiveDataProvider('Lead', [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
        $this->render('leads', [
            'dataProvider' => $dataProvider,
            'campaign' => $campaign,
            'status' => $status,
        ]);
    }

    public function actionViewLead($id)
    {
        $model = Lead::model()->with('campaign')->findByPk($id);

        if ($model->campaign->buyerId != Yii::app()->user->id) {
            throw new CHttpException(403, 'Вы не можете просматривать этого лида');
        }

        $this->render('viewLead', [
            'model' => $model,
        ]);
    }

    public function actionCampaigns()
    {
        $campaigns = Campaign::getCampaignsForBuyer(Yii::app()->user->id);
        $campaignsNoActive = Campaign::getCampaignsForBuyerNoActive(Yii::app()->user->id);

        $this->render('campaigns', [
            'campaigns' => $campaigns,
            'campaignsNoActive' => $campaignsNoActive,
        ]);
    }

    public function actionCampaign($id)
    {
        $campaign = Campaign::model()->with('transactions')->findByPk($id);

        if ($campaign && $campaign->buyerId != Yii::app()->user->id) {
            throw new CHttpException(403, 'Вы не можете просматривать эту кампанию');
        }

        $transactionsDataProvider = new CArrayDataProvider($campaign->transactions);

        $this->render('viewCampaign', [
            'model' => $campaign,
            'transactionsDataProvider' => $transactionsDataProvider,
        ]);
    }

    /**
     * Страница пополнения баланса покупателя.
     */
    public function actionTopup()
    {
        $this->render('topup', []);
    }

    public function actionTransactions()
    {
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(['buyerId' => Yii::app()->user->id]);
        $criteria->order = 'id DESC';

        $currentUser = User::model()->findByPk(Yii::app()->user->id);

        $transactionsDataProvider = new CActiveDataProvider('TransactionCampaign', [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $this->render('transactions', [
            'transactionsDataProvider' => $transactionsDataProvider,
            'currentUser' => $currentUser,
        ]);
    }

    // отбраковка лида покупателем
    public function actionBrakLead()
    {
        $reason = isset($_POST['reason']) ? (int) $_POST['reason'] : 0;
        $reasonComment = isset($_POST['reasonComment']) ? CHtml::encode($_POST['reasonComment']) : '';
        $leadId = isset($_POST['leadId']) ? (int) $_POST['leadId'] : 0;

        if (!$leadId || !$reason || !$reasonComment) {
            echo json_encode(['code' => 400, 'message' => 'Ошибка, не заполнены все поля формы']);
            Yii::app()->end();
        }

        $lead = Lead::model()->findByPk($leadId);

        if (!$lead) {
            echo json_encode(['code' => 404, 'message' => 'Лид не найден']);
            Yii::app()->end();
        }

        if (!$lead->campaign || $lead->campaign->buyerId != Yii::app()->user->id) {
            echo json_encode(['code' => 403, 'message' => 'Вы не можете редактировать этого лида']);
            Yii::app()->end();
        }

        if (!(!is_null($lead->deliveryTime) && (time() - strtotime($lead->deliveryTime) < 86400 * Yii::app()->params['leadHoldPeriodDays']))) {
            echo json_encode(['code' => 403, 'message' => 'Нельзя отправить на отбраковку лид, отправленный покупателю более 3 суток назад']);
            Yii::app()->end();
        }

        $lead->leadStatus = Lead::LEAD_STATUS_NABRAK;
        $lead->brakReason = (int) $reason;
        $lead->brakComment = $reasonComment;

        if ($lead->save()) {
            echo json_encode(['code' => 0, 'id' => $leadId, 'message' => 'Лид отправлен на отбраковку']);
            Yii::app()->end();
        } else {
            echo json_encode(['code' => 400, 'id' => $leadId, 'message' => 'Ошибка: не удалось отправить лид на отбраковку']);
            Yii::app()->end();
        }
    }

    public function actionApi()
    {
        $this->render('api');
    }

    public function actionFaq()
    {
        $this->render('faq');
    }

    public function actionHelp()
    {
        $this->render('help');
    }

    public function actionMyleads()
    {
        $dataProvider = (new BuyerRepository())->getBuyersCampaignsDataProvider(Yii::app()->user->id);

        $this->render('myleads', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
