<?php

use App\helpers\DateHelper;
use App\helpers\StringHelper;
use App\models\Campaign;
use App\models\Lead;
use App\models\Money;
use App\models\Region;
use App\models\TransactionCampaign;
use App\models\User;
use App\modules\admin\controllers\AbstractAdminController;

class CampaignController extends AbstractAdminController
{
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
            ['allow', // allow all users to perform 'index' and 'view' actions
                'actions' => ['index', 'view', 'regions'],
                'users' => ['*'],
            ],
            ['allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['create', 'update', 'topup', 'setLimit'],
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_SECRETARY . ')',
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
        $model = Campaign::model()->with('transactions')->findByPk($id);
        $transactionsDataProvider = new CArrayDataProvider($model->transactions);

        $leadsStats = null;

        $leadSearchModel = new Lead();
        $leadSearchModel->scenario = 'search';

        $leadSearchModel->attributes = $_GET['App\models\Lead'];

        // по умолчанию собираем статистику по проданным лидам за последние 30 дней
        $dateTo = ('' != $leadSearchModel->date2) ? DateHelper::invertDate($leadSearchModel->date2) : date('Y-m-d');
        $dateFrom = ('' != $leadSearchModel->date1) ? DateHelper::invertDate($leadSearchModel->date1) : date('Y-m-d', time() - 86400 * 30);
        $leadsStats = Lead::getStatsByPeriod($dateFrom, $dateTo, null, $model->id);

        // найдем лиды, отправленные в данную кампанию
        $leadsCriteria = new CDbCriteria();
        $leadsCriteria->addColumnCondition(['campaignId' => $model->id]);
        $leadsCriteria->order = 'id DESC';
        $leadsDataProvider = new CActiveDataProvider(Lead::class, [
            'criteria' => $leadsCriteria,
        ]);

        $this->render('view', [
            'model' => $model,
            'transactionsDataProvider' => $transactionsDataProvider,
            'leadsStats' => $leadsStats,
            'searchModel' => $leadSearchModel,
            'leadsDataProvider' => $leadsDataProvider,
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Campaign();

        $buyerId = (int) $_GET['buyerId'];

        if (!User::model()->findByPk($buyerId)) {
            throw new CHttpException('Пользователь не найден', 404);
        }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['App_models_Campaign'])) {
            $model->attributes = $_POST['App_models_Campaign'];
            $model->buyerId = $buyerId;
            $model->price *= 100;

            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $buyersArray = User::getAllBuyersIdsNames();
        $regions = ['0' => 'Не выбран'] + Region::getAllRegions();

        $this->render('create', [
            'model' => $model,
            'buyersArray' => $buyersArray,
            'regions' => $regions,
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

        if (isset($_POST['App_models_Campaign'])) {
            $oldActivity = $model->active; // запомним статус активности кампании

            $model->attributes = $_POST['App_models_Campaign'];
            $model->price *= 100;

            $buyer = $model->buyer; // покупатель

            if ('' == $_POST['town']) {
                $model->townId = 0;
            }

            $model->days = implode(',', $model->workDays);

            if ($model->save()) {
                // если статус активности сменился с Модерация на другой, отправим уведомление
                if ($model->active != $oldActivity && Campaign::ACTIVE_MODERATION == $oldActivity) {
                    $buyer->sendBuyerNotification(User::BUYER_EVENT_CONFIRM, $model);
                }
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $buyersArray = User::getAllBuyersIdsNames();
        $regions = ['0' => 'Не выбран'] + Region::getAllRegions();

        $model->workDays = explode(',', $model->days);

        $this->render('update', [
            'model' => $model,
            'buyersArray' => $buyersArray,
            'regions' => $regions,
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
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['index']);
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        /*
         * Базовый запрос
         * Ищем кампании с их пользователями, балансами.
         * Есть 5 типов кампаний:
         * 1) Активные (active=1, есть транзакции за последние 10 дней)
         * 2) Условно активные (active=1, транзакций нет больше 10 дней)
         * 3) Неактивные (active=0)
         * 4) На модерации (active=2)
         * 5) Одобренные (active=1, lastLeadTime = NULL)
         */
        /*           SELECT c.id, c.townId, t.name townName, c.regionId, r.name regionName, COUNT(l.id), u.name, u.balance, u.lastTransactionTime
                    FROM `100_campaign` c
                    LEFT JOIN `100_user` u ON u.id = c.buyerId
                    LEFT JOIN `100_lead100` l ON l.campaignId = c.id AND l.leadStatus=6
                    LEFT JOIN `100_town` t ON t.id = c.townId
                    LEFT JOIN `100_region` r ON r.id = c.regionId
                    WHERE c.active=1 AND u.lastTransactionTime<NOW()-INTERVAL 10 DAY OR u.lastTransactionTime IS NULL
                    GROUP BY c.id
                    ORDER BY u.name
        */

        $campaignsCommand = Yii::app()->db->createCommand()
            ->select('c.id, c.townId, c.days, t.name townName, c.regionId, r.name regionName, c.leadsDayLimit, c.realLimit, c.brakPercent, c.timeFrom, c.timeTo, c.price, COUNT(l.id) leadsSent, u.id userId, u.name, u.balance, u.lastTransactionTime, u.yurcrmToken')
            ->from('{{campaign}} c')
            ->leftJoin('{{user}} u', 'u.id = c.buyerId')
            ->leftJoin('{{town}} t', 't.id = c.townId')
            ->leftJoin('{{region}} r', 'r.id = c.regionId')
            ->leftJoin('{{lead}} l', 'l.campaignId = c.id AND l.leadStatus!=' . Lead::LEAD_STATUS_BRAK)
            ->group('c.id')
            ->order('u.id, townName, regionName');

        // условия выборки в зависимости от выбранного типа кампании
        switch ($_GET['active']) {
            case 'active':
            default:
                $active = Campaign::ACTIVE_YES;
                $campaignsCommand->andWhere('c.active=:active AND u.lastTransactionTime>NOW()-INTERVAL 10 DAY', [':active' => $active]);
                $type = 'active';
                if (Campaign::TYPE_PARTNERS == Yii::app()->request->getParam('type')) {
                    $campaignsCommand->andWhere('c.type=:type', [':type' => Campaign::TYPE_PARTNERS]);
                    $type = 'activePP';
                } else {
                    $campaignsCommand->andWhere('c.type=:type', [':type' => Campaign::TYPE_BUYERS]);
                }
                break;
            case 'passive':
                $active = Campaign::ACTIVE_YES;
                $campaignsCommand->andWhere('c.active=:active AND u.lastTransactionTime<NOW()-INTERVAL 10 DAY', [':active' => $active]);
                $type = 'passive';
                break;
            case 'inactive':
                $active = Campaign::ACTIVE_NO;
                $campaignsCommand->andWhere('c.active=:active', [':active' => $active]);
                $type = 'inactive';
                break;
            case 'moderation':
                $active = Campaign::ACTIVE_MODERATION;
                $campaignsCommand->andWhere('c.active=:active', [':active' => $active]);
                $type = 'moderation';
                break;
            case 'accepted':
                $active = Campaign::ACTIVE_YES;
                $campaignsCommand->andWhere('c.active=:active AND lastLeadTime IS NULL', [':active' => $active]);
                $type = 'accepted';
                break;
            case 'archive':
                $active = Campaign::ACTIVE_ARCHIVE;
                $campaignsCommand->andWhere('c.active=:active', [':active' => $active]);
                $type = 'archive';
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
            ->select('c.id, COUNT(l.id) counter')
            ->from('{{campaign}} c')
            ->leftJoin('{{lead}} l', 'l.campaignId = c.id AND l.leadStatus!=' . Lead::LEAD_STATUS_BRAK)
            ->where('c.active=:active AND DATE(l.deliveryTime) = DATE(NOW())', [':active' => $active])
            ->group('c.id')
            ->queryAll();

        /*
         * Массив со счетчиками лидов, отправленных сегодня в кампании (campaignId => count)
         */
        $todayLeadsArray = [];

        foreach ($todayLeadsRows as $row) {
            $todayLeadsArray[$row['id']] = $row['counter'];
        }

        $campaignsArray = [];

        foreach ($campaignsRows as $row) {
            $campaignsArray[$row['userId']]['name'] = $row['name'];
            $campaignsArray[$row['userId']]['yurcrmToken'] = $row['yurcrmToken'];
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

            $campaignsArray[$row['userId']]['campaigns'][$row['id']]['object'] = Campaign::model()->findByPk($row['id']);
        }

        /* теперь нужно вытащить данные по маржинальности кампаний за последние 5 дней.
         * найдем лиды, отправленные в найденные кампании за последние 5 дней, вытащим суммы их цен покупки и продажи
         * цена покупки - для всех лидов, цена продажи - для лидов в статусе Отправлен
         *
         *  SELECT campaignId, leadStatus, SUM(buyPrice), SUM(price)  FROM 100_lead100
         *  WHERE deliveryTime > NOW()-INTERVAL 5 DAY
         *  GROUP BY campaignId, leadStatus
         */
        $leadsByStatusArray = [];
        $leadsByStatusRows = Yii::app()->db->cache(600)->createCommand()
            ->select('campaignId, leadStatus, SUM(buyPrice) sumBuy, SUM(price) sumSell')
            ->from('{{lead}}')
            ->where('deliveryTime > NOW()-INTERVAL 5 DAY')
            ->group('campaignId, leadStatus')
            ->queryAll();

        foreach ($leadsByStatusRows as $row) {
            $leadsByStatusArray[$row['campaignId']]['expences'] += $row['sumBuy']; // суммируем цены покупки для всех статусов в расход по кампании
            if (Lead::LEAD_STATUS_SENT == $row['leadStatus'] || Lead::LEAD_STATUS_RETURN == $row['leadStatus']) {
                $leadsByStatusArray[$row['campaignId']]['revenue'] += $row['sumSell']; // суммируем цены продажи для проданных лидов в доход по кампании
            }
        }

        $this->render('index', [
            'campaignsArray' => $campaignsArray,
            'type' => $type,
            'leadsByStatusArray' => $leadsByStatusArray,
        ]);
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Campaign('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['App\models\Campaign'])) {
            $model->attributes = $_GET['App\models\Campaign'];
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
     * @return Campaign the loaded model
     *
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Campaign::model()->findByPk($id);
        if (null === $model) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     *
     * @param Campaign $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && 'campaign-form' === $_POST['ajax']) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionTopup()
    {
        $buyerId = isset($_POST['buyerId']) ? (int) $_POST['buyerId'] : 0;
        $sum = isset($_POST['sum']) ? ((int) $_POST['sum']) * 100 : 0;
        $account = isset($_POST['account']) ? (int) $_POST['account'] : 1;

        if ($sum <= 0 || !$buyerId) {
            echo json_encode(['code' => 400, 'message' => 'Error, not enough data']);
            Yii::app()->end();
        }

        $buyer = User::model()->findByPk($buyerId);
        $buyer->setScenario('balance');

        if (!$buyer) {
            echo json_encode(['code' => 400, 'message' => 'Error, buyer not found']);
            Yii::app()->end();
        }

        $transaction = new TransactionCampaign();
        $transaction->sum = $sum;
        $transaction->buyerId = $buyerId;
        $transaction->description = 'Пополнение баланса пользователя ' . $buyerId;

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
            $buyer->lastTransactionTime = date('Y-m-d H:i:s');
            $moneyTransaction->save();
            if ($buyer->save()) {
                // если баланс пополнен, отправляем уведомление покупателю
                $buyer->sendBuyerNotification(User::BUYER_EVENT_TOPUP);
                echo json_encode(['code' => 0, 'id' => $buyerId, 'balance' => MoneyFormat::rubles($buyer->balance)]);
            } else {
                StringHelper::printr($buyer->errors);
            }
        } else {
            StringHelper::printr($transaction->errors);
        }
    }

    public function actionSetLimit()
    {
        $campaignId = (int) $_POST['id'];
        $limit = (int) $_POST['limit'];

        $campaign = Campaign::model()->findByPk($campaignId);

        if (!$campaign) {
            throw new CHttpException(400, 'Кампания не найдена');
        }

        $campaign->realLimit = $limit;

        if ($campaign->save()) {
            die('1');
        } else {
            die('0');
        }
    }

    /**
     * Вывод списка выкупаемых регионов.
     */
    public function actionRegions()
    {
        $this->render('regions', []);
    }
}
