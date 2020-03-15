<?php

use App\helpers\PhoneHelper;
use App\helpers\StringHelper;

class LeadController extends Controller
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
                'actions' => ['index', 'view', 'create', 'stats'],
                'users' => ['@'],
                'expression' => 'Yii::app()->user->role == ' . User::ROLE_ROOT . ' || Yii::app()->user->role == ' . User::ROLE_SECRETARY,
            ],
            ['allow',
                'actions' => ['forceSell'],
                'users' => ['@'],
                'expression' => 'Yii::app()->user->role == ' . User::ROLE_ROOT,
            ],
            ['allow',
                'actions' => ['update', 'delete', 'sendLeads', 'toQuestion', 'generate', 'dispatch', 'changeStatus'],
                'users' => ['@'],
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_MANAGER . ') || Yii::app()->user->checkAccess(' . User::ROLE_SECRETARY . ')',
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
        $model = $this->loadModel($id);
        $campaignIds = Campaign::getCampaignsForLead($model->id, true);
        $campaigns = [];
        if (is_array($campaignIds) && sizeof($campaignIds)) {
            $campaigns = Campaign::model()->findAll('id IN(' . implode(', ', $campaignIds) . ')');
        }

        $this->render('view', [
            'model' => $model,
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Lead();
        $apiResult = null;
        $allDirectionsHierarchy = QuestionCategory::getDirections(true, true);
        $allDirections = QuestionCategory::getDirectionsFlatList($allDirectionsHierarchy);
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Lead'])) {
            $model->attributes = $_POST['Lead'];
            $model->phone = PhoneHelper::normalizePhone($model->phone);
            $model->buyPrice *= 100;

            if ($model->testMode) {
                // тестовый режим. найдем по источнику его данные
                $source = $model->source;
                if ($source) {
                    $apiClient = new StoYuristovClient($source->appId, $source->secretKey, 1);

                    $apiClient->name = $model->name;
                    $apiClient->phone = $model->phone;
                    $apiClient->town = $model->town->name;
                    $apiClient->question = $model->question;

                    $apiResult = $apiClient->send();
                }
            } else {
                // проверим, нет ли лида с таким телефоном за последние 12 часов

                if ($model->findDublicates()) {
                    $model->addError('phone', 'Лид с таким номером телефона уже добавлен за последние 12 часов');
                } else {
                    if ($model->save()) {
                        if (Yii::app()->request->isAjaxRequest) {
                            echo 'ok';
                            Yii::app()->end();
                        }
                        $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        if (Yii::app()->request->isAjaxRequest) {
                            echo 'error';
                            Yii::app()->end();
                        }
                    }
                }
            }
        }

        $this->render('create', [
            'model' => $model,
            'allDirections' => $allDirections,
            'apiResult' => $apiResult,
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
        $model->setScenario('update');

        $allDirectionsHierarchy = QuestionCategory::getDirections(true, true);
        $allDirections = QuestionCategory::getDirectionsFlatList($allDirectionsHierarchy);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Lead'])) {
            $model->attributes = $_POST['Lead'];
            $model->phone = PhoneHelper::normalizePhone($model->phone);
            $model->buyPrice *= 100;

            if ($model->save()) {
                Lead2Category::model()->deleteAll('leadId=' . $model->id);
                if (is_array($model->categoriesId) && sizeof($model->categoriesId)) {
                    foreach ($model->categoriesId as $catId) {
                        $lead2category = new Lead2Category();
                        $lead2category->leadId = $model->id;
                        $lead2category->cId = $catId;
                        $lead2category->save();
                    }
                }

                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $model->categoriesId = [];
        foreach ($model->categories as $cat) {
            $model->categoriesId[] = $cat->id;
        }

        $this->render('update', [
            'model' => $model,
            'allDirections' => $allDirections,
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

        $this->redirect(['index']);
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $searchModel = new Lead();
        $searchModel->type = '';

        $criteria = new CDbCriteria();

        $criteria->order = 't.id DESC';
//        $criteria->with = array('questionObject.townByIP', 'town', 'town.region', 'source');
        $statusId = (isset($_GET['status'])) ? (int) $_GET['status'] : false;

        if (false !== $statusId) {
            $criteria->addColumnCondition(['t.leadStatus' => $statusId]);
            $criteria->addCondition('campaignId IS NOT NULL');
        }

        if (isset($_GET['Lead'])) {
            // если используется форма поиска по контактам
            $searchModel->attributes = $_GET['Lead'];
            $dataProvider = $searchModel->search();
        } else {
            // если форма не использовалась
            $dataProvider = new CActiveDataProvider('Lead', [
                'criteria' => $criteria,
                'pagination' => [
                    'pageSize' => 50,
                    'params' => $_GET['Lead'],
                ],
            ]);
        }

        $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionToQuestion($id)
    {
        $contact = $this->loadModel($id);

        $question = new Question();
        $question->scenario = 'convert';
        $question->townId = $contact->townId;
        $question->authorName = $contact->name;
        $question->questionText = $contact->question;
        $question->status = Question::STATUS_PUBLISHED;
        $question->publishDate = date('Y-m-d H:i:s');

        if ($question->save()) {
            $contact->questionId = $question->id;
            if ($contact->save()) {
                echo $contact->id;
            } else {
                StringHelper::printr($contact->errors);
                //throw new CHttpException(500,'Не удалось перевести лид в вопрос');
            }
        } else {
            StringHelper::printr($question->errors);
        }
    }

    /**
     *  распределяет лиды по кнопке.
     */
    public function actionSendLeads()
    {
        $criteria = new CDbCriteria();

        $criteria->addColumnCondition(['leadStatus' => Lead::LEAD_STATUS_DEFAULT]);
        $criteria->addColumnCondition(['question_date>' => date('Y-m-d')]);
        $criteria->with = ['town', 'town.region'];

        // сколько лидов обрабатывать за раз
        //$criteria->limit = 100;
        $criteria->limit = 100;

        $leads = Lead::model()->findAll($criteria);

        echo '<h2>Разбираем лиды..</h2>';
        foreach ($leads as $lead) {
            $campaignId = Campaign::getCampaignsForLead($lead->id);
            echo $lead->id . ' - ' . $campaignId . '<br />';
            if (!$campaignId) {
                continue;
            }

            $campaign = Campaign::model()->findByPk($campaignId);
            $lead->sellLead(null, $campaign);
        }

        //$this->redirect(array('/admin/lead/index', 'leadsSent'=>1));
    }

    // генерирует демо лиды
    public function actionGenerate()
    {
        $limit = 10;
        $towns = [598];
        $sourceId = 3;
        $question = 'Тестовый текст вопроса';
        $status = Lead::LEAD_STATUS_DEFAULT;
        $type = Lead::TYPE_QUESTION;
        $names = ['Август', 'Августин', 'Аврор', 'Агап', 'Адам', 'Аксён', 'Алевтин', 'Александр', 'Алексей', 'Алексий', 'Альберт', 'Анастасий', 'Анатолий', 'Анвар', 'Андрей', 'Андрон', 'Анисим', 'Антип', 'Антон', 'Антонин', 'Аристарх', 'Аркадий', 'Арсений', 'Артамон', 'Артём', 'Артемий', 'Артур', 'Архип', 'Аскольд', 'Афанасий', 'Афиноген'];

        for ($i = 0; $i < $limit; ++$i) {
            $lead = new Lead();

            $properties = [
                'sourceId' => $sourceId,
                'question' => $question,
                'name' => $names[mt_rand(0, sizeof($names) - 1)],
                'status' => $status,
                'type' => $type,
                'townId' => $towns[mt_rand(0, sizeof($towns) - 1)],
                'active' => 1,
                'phone' => '7900' . mt_rand(1000000, 9999999),
                'email' => 'bot_' . mt_rand(100000, 999999) . '@100yuristov.com',
            ];

            $lead->attributes = $properties;

            if ($lead->save()) {
                echo 'Лид ' . $lead->id . ' сохранен<br />';
            } else {
                StringHelper::printr($lead->attributes);
                StringHelper::printr($lead->errors);
            }
        }
    }

    /*
     * распределяет лидов по покупателям
     */

    public function actionDispatch()
    {
        $criteria = new CDbCriteria();

        $criteria->addColumnCondition(['leadStatus' => Lead::LEAD_STATUS_DEFAULT]);
        $criteria->addColumnCondition(['question_date>' => date('Y-m-d')]);
        $criteria->with = ['town', 'town.region'];

        // сколько лидов обрабатывать за раз
        $criteria->limit = 50;

        $leads = Lead::model()->findAll($criteria);

        foreach ($leads as $lead) {
            echo $lead->id . ' ' . $lead->name . ', город: ' . $lead->town->name . ', регион:' . $lead->town->region->name;
            echo '<p>Подходящие кампании:</p>';
            echo $campaignId = Campaign::getCampaignsForLead($lead->id);

            if (!$campaignId) {
                // если для лида нет ни одной кампании, идем к следующему лиду
                continue;
            }

            $campaign = Campaign::model()->findByPk($campaignId);
            if ($lead->sellLead(null, $campaign)) {
                echo 'Лид отправлен в кампанию';
            } else {
                echo 'С этим лидом что-то пошло не так';
            }
            echo '<hr />';
        }
    }

    /**
     * Изменение статуса лида через POST запрос
     */
    public function actionChangeStatus()
    {
        $leadId = (isset($_POST['id'])) ? (int) $_POST['id'] : false;
        $status = (isset($_POST['status'])) ? (int) $_POST['status'] : false;
        // возвращать ли деньги покупателю при отбраковке лида
        $refund = (isset($_POST['refund'])) ? (int) $_POST['refund'] : 1;

        if (false === $leadId || false === $status) {
            echo json_encode(['code' => 400, 'message' => 'Error, not enough data']);
            Yii::app()->end();
        }

        $lead = Lead::model()->findByPk($leadId);

        if (!$lead) {
            echo json_encode(['code' => 404, 'message' => 'Lead not found']);
            Yii::app()->end();
        }

        $lead->leadStatus = $status;

        // найдем кампанию, в которую отправлен лид
        // если новый статус - Брак, вернем деньги за лида на баланс пользователя
        // При условии, что не передан параметр, отменяющий возврат денег покупателю
        $campaign = $lead->campaign;
        if ($lead->campaign && Lead::LEAD_STATUS_BRAK == $lead->leadStatus && 0 !== $refund) {
            $buyer = $campaign->buyer;
            $buyer->setScenario('balance');
            $buyer->balance += $lead->price;
            // записываем данные о возврате средств на баланс пользователя
            $transaction = new TransactionCampaign();
            $transaction->sum = $lead->price;
            $transaction->campaignId = $campaign->id;
            $transaction->buyerId = $buyer->id;
            $transaction->description = 'Возврат за лид ID=' . $lead->id;

            if (!$transaction->save()) {
                StringHelper::printr($transaction->errors);
                echo json_encode(['code' => 500, 'id' => $lead->id, 'message' => 'Не удалось сохранить транзакцию']);
                Yii::app()->end();
            }

            if (!$buyer->save()) {
                echo json_encode(['code' => 500, 'id' => $lead->id, 'message' => 'Не удалось обновить баланс пользователя']);
                Yii::app()->end();
            }
        }

        if ($lead->save()) {
            echo json_encode(['code' => 0, 'id' => $lead->id, 'message' => 'Статус изменен']);
            Yii::app()->end();
        } else {
            echo json_encode(['code' => 500, 'id' => $lead->id, 'message' => 'Статус не изменен']);
            Yii::app()->end();
        }
    }

    /*
     * вывод статистики продаж лидов
     * GET параметры:
     * type (dates|campaigns) - разбивка по датам или кампаниям
     */

    public function actionStats()
    {
        // найдем все годы, в которые есть контакты
        $yearsRows = Yii::app()->db->cache(600)->createCommand()
                ->select('DISTINCT(YEAR(question_date)) y')
                ->from('{{lead}}')
                ->where('price != 0 AND YEAR(question_date)!=0')
                ->queryColumn();
        $yearsArray = [];
        foreach ($yearsRows as $k => $v) {
            $yearsArray[$v] = $v;
        }

        // по умолчанию группировка по датам
        $type = (isset($_GET['type'])) ? $_GET['type'] : 'dates';
        $month = (isset($_GET['month'])) ? $_GET['month'] : date('n');
        $year = (isset($_GET['year'])) ? $_GET['year'] : date('Y');

        $leadsRows = Yii::app()->db->createCommand()
                ->select('l.price summa, DATE(l.question_date) lead_date, l.campaignId campaignId, l.buyPrice, l.leadStatus')
                ->from('{{lead}} l')
                ->where('l.price != 0 AND MONTH(l.question_date)="' . $month . '" AND YEAR(l.question_date)="' . $year . '"')
                ->order('lead_date DESC')
                ->queryAll();

        $sumArray = [];
        $kolichArray = [];
        $buySumArray = [];

        if ('dates' == $type) {
            foreach ($leadsRows as $row) {
                if (Lead::LEAD_STATUS_BRAK != $row['leadStatus']) {
                    $sumArray[$row['lead_date']] += $row['summa'];
                    ++$kolichArray[$row['lead_date']];
                    $buySumArray[$row['lead_date']] += $row['buyPrice'];
                }
            }
        }

        if ('campaigns' == $type) {
            foreach ($leadsRows as $row) {
                if (Lead::LEAD_STATUS_SENT == $row['leadStatus']) {
                    $sumArray[$row['campaignId']] += $row['summa'];
                    ++$kolichArray[$row['campaignId']];
                }

                $buySumArray[$row['campaignId']] += $row['buyPrice'];
            }
        }

        // получим данные по расходам на Директ
        $expencesDirectArray = [];
        $expencesCallsArray = [];
        $expencesRows = Yii::app()->db->createCommand()
                ->select('date, expences, type, comment')
                ->from('{{expence}}')
                ->where('MONTH(date)="' . $month . '" AND YEAR(date)="' . $year . '"')
                ->order('date DESC')
                ->queryAll();

        foreach ($expencesRows as $index => $row) {
            if (Expence::TYPE_DIRECT == $row['type']) {
                $expencesDirectArray[$row['date']]['expence'] = $row['expences'];
            }
            if (Expence::TYPE_CALLS == $row['type']) {
                $expencesCallsArray[$row['date']]['expence'] += $row['expences'];
            }
        }

        // статистика по VIP вопросам
        $vipRows = Yii::app()->db->createCommand()
                ->select('SUM(value) sum, DATE(datetime) date')
                ->from('{{money}}')
                ->where('type=:type AND direction=:direction AND MONTH(datetime) = :month AND YEAR(datetime) = :year', [
                    ':type' => Money::TYPE_INCOME,
                    ':direction' => 504,
                    ':month' => $month,
                    ':year' => $year,
                ])
                ->group('date')
                ->queryAll();

        $vipStats = [];
        foreach ($vipRows as $row) {
            $vipStats[$row['date']] = $row['sum'];
        }

        $this->render('stats', [
            'type' => $type,
            'yearsArray' => $yearsArray,
            'month' => $month,
            'year' => $year,
            'sumArray' => $sumArray,
            'kolichArray' => $kolichArray,
            'buySumArray' => $buySumArray,
            'expencesDirectArray' => $expencesDirectArray,
            'expencesCallsArray' => $expencesCallsArray,
            'vipStats' => $vipStats,
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param int $id the ID of the model to be loaded
     *
     * @return Lead the loaded model
     *
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Lead::model()->findByPk($id);
        if (null === $model) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     *
     * @param Lead $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && 'lead-form' === $_POST['ajax']) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Принудительная продажа лида в кампанию.
     */
    public function actionForceSell()
    {
        $leadId = (int) $_POST['leadId'];
        $campaignId = (int) $_POST['campaignId'];

        $lead = Lead::model()->findByPk($leadId);
        $campaign = Campaign::model()->findByPk($campaignId);

        if (is_null($lead) || is_null($campaign)) {
            echo json_encode(['code' => 404, 'message' => 'Лид или кампания не найдены']);
            Yii::app()->end();
        }

        if (true === $lead->sellLead(null, $campaign)) {
            echo json_encode(['code' => 0, 'message' => 'Лид продан']);
            Yii::app()->end();
        } else {
            echo json_encode(['code' => 500, 'message' => 'Лид не продан из-за ошибки на сервере']);
            Yii::app()->end();
        }
    }
}
