<?php

class CampaignController extends Controller {

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
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'topup', 'setLimit'),
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_ROOT . ')',
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
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
        $model = Campaign::model()->with('transactions')->findByPk($id);
        $transactionsDataProvider = new CArrayDataProvider($model->transactions);

        $leadsStats = NULL;

        $leadSearchModel = new Lead100;
        $leadSearchModel->scenario = 'search';


        $leadSearchModel->attributes = $_GET['Lead100'];

        // по умолчанию собираем статистику по проданным лидам за последние 30 дней
        $dateTo = ($leadSearchModel->date2 != '') ? CustomFuncs::invertDate($leadSearchModel->date2) : date("Y-m-d");
        $dateFrom = ($leadSearchModel->date1 != '') ? CustomFuncs::invertDate($leadSearchModel->date1) : date("Y-m-d", time() - 86400 * 30);
        $leadsStats = Lead100::getStatsByPeriod($dateFrom, $dateTo, null, $model->id);


        $this->render('view', array(
            'model' => $model,
            'transactionsDataProvider' => $transactionsDataProvider,
            'leadsStats' => $leadsStats,
            'searchModel' => $leadSearchModel,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Campaign;

        $buyerId = (int) $_GET['buyerId'];

        if (!User::model()->findByPk($buyerId)) {
            throw new CHttpException("Пользователь не найден", 404);
        }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Campaign'])) {
            $model->attributes = $_POST['Campaign'];
            $model->buyerId = $buyerId;
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $buyersArray = User::getAllBuyersIdsNames();
        $regions = array('0' => 'Не выбран') + Region::getAllRegions();

        $this->render('create', array(
            'model' => $model,
            'buyersArray' => $buyersArray,
            'regions' => $regions,
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

        if (isset($_POST['Campaign'])) {
            $oldActivity = $model->active; // запомним статус активности кампании

            $model->attributes = $_POST['Campaign'];
            $buyer = $model->buyer; // покупатель

            if ($_POST['town'] == '') {
                $model->townId = 0;
            }
            
            $model->days = implode(',', $model->workDays);
//            CustomFuncs::printr($model->workDays);
//            CustomFuncs::printr($model->days);
//            exit;           
            
            if ($model->save()) {

                // если статус активности сменился с Модерация на другой, отправим уведомление
                if ($model->active != $oldActivity && $oldActivity == Campaign::ACTIVE_MODERATION) {
                    $buyer->sendBuyerNotification(User::BUYER_EVENT_CONFIRM, $model);
                }
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $buyersArray = User::getAllBuyersIdsNames();
        $regions = array('0' => 'Не выбран') + Region::getAllRegions();
        
        $model->workDays = explode(',', $model->days);

        $this->render('update', array(
            'model' => $model,
            'buyersArray' => $buyersArray,
            'regions' => $regions,
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
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {

        /*
         * Базовый запрос
         * Ищем кампании с их пользователями, балансами.
         * Есть 4 типа кампаний:
         * 1) Активные (active=1, есть транзакции за последние 10 дней)
         * 2) Условно активные (active=1, транзакций нет больше 10 дней)
         * 3) Неактивные (active=0)
         * 4) На модерации (active=2)
         */
//            SELECT c.id, c.townId, t.name townName, c.regionId, r.name regionName, COUNT(l.id), u.name, u.balance, u.lastTransactionTime 
//            FROM `100_campaign` c
//            LEFT JOIN `100_user` u ON u.id = c.buyerId
//            LEFT JOIN `100_lead100` l ON l.campaignId = c.id AND l.leadStatus=6
//            LEFT JOIN `100_town` t ON t.id = c.townId
//            LEFT JOIN `100_region` r ON r.id = c.regionId
//            WHERE c.active=1 AND u.lastTransactionTime<NOW()-INTERVAL 10 DAY OR u.lastTransactionTime IS NULL
//            GROUP BY c.id
//            ORDER BY u.name

        $campaignsCommand = Yii::app()->db->createCommand()
                ->select("c.id, c.townId, c.days, t.name townName, c.regionId, r.name regionName, c.leadsDayLimit, c.realLimit, c.brakPercent, c.timeFrom, c.timeTo, c.price, COUNT(l.id) leadsSent, u.id userId, u.name, u.balance, u.lastTransactionTime")
                ->from("{{campaign}} c")
                ->leftJoin("{{user}} u", "u.id = c.buyerId")
                ->leftJoin("{{town}} t", "t.id = c.townId")
                ->leftJoin("{{region}} r", "r.id = c.regionId")
                ->leftJoin("{{lead100}} l", "l.campaignId = c.id AND l.leadStatus!=" . Lead100::LEAD_STATUS_BRAK)
                ->group("c.id")
                ->order("u.id");

        // условия выборки в зависимости от выбранного типа кампании
        switch ($_GET['type']) {
            case 'active': default:
                $active = Campaign::ACTIVE_YES;
                $campaignsCommand->andWhere("c.active=:active AND u.lastTransactionTime>NOW()-INTERVAL 10 DAY", array(':active' => $active));
                $type = 'active';
                break;
            case 'passive':
                $active = Campaign::ACTIVE_YES;
                $campaignsCommand->andWhere("c.active=:active AND u.lastTransactionTime<NOW()-INTERVAL 10 DAY", array(':active' => $active));
                $type = 'passive';
                break;
            case 'inactive':
                $active = Campaign::ACTIVE_NO;
                $campaignsCommand->andWhere("c.active=:active", array(':active' => $active));
                $type = 'inactive';
                break;
            case 'moderation':
                $active = Campaign::ACTIVE_MODERATION;
                $campaignsCommand->andWhere("c.active=:active", array(':active' => $active));
                $type = 'moderation';
                break;
        }

        $campaignsRows = $campaignsCommand->queryAll();

        /*
         * Выберем одним запросом количество лидов, отправленных сегодня в кампании
         * с группировкой по кампаниям
         */
//            SELECT c.id, COUNT(l.id)
//            FROM `100_campaign` c
//            LEFT JOIN `100_lead100` l ON l.campaignId = c.id AND l.leadStatus=6
//            WHERE c.active=1 AND DATE(l.question_date) = DATE(NOW())
//            GROUP BY c.id

        $todayLeadsRows = Yii::app()->db->createCommand()
                ->select("c.id, COUNT(l.id) counter")
                ->from("{{campaign}} c")
                ->leftJoin("{{lead100}} l", "l.campaignId = c.id AND l.leadStatus!=" . Lead100::LEAD_STATUS_BRAK)
                ->where("c.active=:active AND DATE(l.deliveryTime) = DATE(NOW())", array(':active' => $active))
                ->group("c.id")
                ->queryAll();

        /*
         * Массив со счетчиками лидов, отправленных сегодня в кампании (campaignId => count)
         */
        $todayLeadsArray = array();

        foreach ($todayLeadsRows as $row) {
            $todayLeadsArray[$row['id']] = $row['counter'];
        }

        $campaignsArray = array();

        foreach ($campaignsRows as $row) {
            $campaignsArray[$row['userId']]['name'] = $row['name'];
            $campaignsArray[$row['userId']]['id'] = $row['userId'];
            $campaignsArray[$row['userId']]['balance'] = $row['balance'];
            $campaignsArray[$row['userId']]['campaigns'][$row['id']]['id'] = $row['id'];
            $campaignsArray[$row['userId']]['campaigns'][$row['id']]['days'] = $row['days'];
            $campaignsArray[$row['userId']]['campaigns'][$row['id']]['townId'] = $row['townId'];
            $campaignsArray[$row['userId']]['campaigns'][$row['id']]['regionId'] = $row['regionId'];
            $campaignsArray[$row['userId']]['campaigns'][$row['id']]['regionName'] = $row['regionName'];
            $campaignsArray[$row['userId']]['campaigns'][$row['id']]['townName'] = $row['townName'];
            $campaignsArray[$row['userId']]['campaigns'][$row['id']]['timeFrom'] = $row['timeFrom'];
            $campaignsArray[$row['userId']]['campaigns'][$row['id']]['timeTo'] = $row['timeTo'];
            $campaignsArray[$row['userId']]['campaigns'][$row['id']]['price'] = $row['price'];
            $campaignsArray[$row['userId']]['campaigns'][$row['id']]['leadsDayLimit'] = $row['leadsDayLimit'];
            $campaignsArray[$row['userId']]['campaigns'][$row['id']]['realLimit'] = $row['realLimit'];
            $campaignsArray[$row['userId']]['campaigns'][$row['id']]['brakPercent'] = $row['brakPercent'];
            $campaignsArray[$row['userId']]['campaigns'][$row['id']]['leadsSent'] = $row['leadsSent'];
            $campaignsArray[$row['userId']]['campaigns'][$row['id']]['todayLeads'] = (int) $todayLeadsArray[$row['id']];
        }

        //CustomFuncs::printr($campaignsRows);
        //CustomFuncs::printr($campaignsArray);
        //exit;

        $this->render('index', array(
            'campaignsArray' => $campaignsArray,
            'type' => $type,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Campaign('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Campaign']))
            $model->attributes = $_GET['Campaign'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Campaign the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Campaign::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Campaign $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'campaign-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionTopup() {
        $buyerId = isset($_POST['buyerId']) ? (int) $_POST['buyerId'] : 0;
        $sum = isset($_POST['sum']) ? (int) $_POST['sum'] : 0;
        $account = isset($_POST['account']) ? (int) $_POST['account'] : 1;

        if ($sum <= 0 || !$buyerId) {
            echo json_encode(array('code' => 400, 'message' => 'Error, not enough data'));
            exit;
        }

        $buyer = User::model()->findByPk($buyerId);
        $buyer->setScenario('balance');

        if (!$buyer) {
            echo json_encode(array('code' => 400, 'message' => 'Error, buyer not found'));
            exit;
        }

        $transaction = new TransactionCampaign;
        $transaction->sum = $sum;
        $transaction->buyerId = $buyerId;
        $transaction->description = "Пополнение баланса пользователя " . $buyerId;
        
        // создаем запись кассы
        $moneyTransaction = new Money();
        $moneyTransaction->accountId = $account;
        $moneyTransaction->value = $sum;
        $moneyTransaction->type = Money::TYPE_INCOME;
        $moneyTransaction->direction = 501;
        $moneyTransaction->datetime = date('Y-m-d');
        $moneyTransaction->comment = 'Пополнение баланса пользователя ' . $buyerId;
        

        $buyer->balance += $sum;

        if ($transaction->save()) {
            $buyer->lastTransactionTime = date("Y-m-d H:i:s");
            $moneyTransaction->save();
            if ($buyer->save()) {
                // если баланс пополнен, отправляем уведомление покупателю
                $buyer->sendBuyerNotification(User::BUYER_EVENT_TOPUP);
                echo json_encode(array('code' => 0, 'id' => $buyerId, 'balance' => $buyer->balance));
            } else {
                CustomFuncs::printr($buyer->errors);
            }
        } else {
            CustomFuncs::printr($transaction->errors);
        }
    }

    public function actionSetLimit()
    {
        $campaignId = (int)$_POST['id'];
        $limit = (int)$_POST['limit'];
        
        $campaign = Campaign::model()->findByPk($campaignId);
        
        if(!$campaign) {
            throw new CHttpException(400, 'Кампания не найдена');
        }
        
        $campaign->realLimit = $limit;
        
        if($campaign->save()) {
            die('1');
        } else {
            die('0');
        }
    }
}
