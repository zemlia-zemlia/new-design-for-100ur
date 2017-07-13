<?php

class LeadController extends Controller {

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
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // 
                'actions' => array('index', 'view', 'create', 'stats'),
                'users' => array('@'),
                'expression' => 'Yii::app()->user->role == ' . User::ROLE_ROOT . ' || Yii::app()->user->role == ' . User::ROLE_SECRETARY,
            ),
            array('allow', // 
                'actions' => array('update', 'delete', 'sendLeads', 'toQuestion', 'generate', 'dispatch', 'changeStatus'),
                'users' => array('@'),
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_MANAGER . ') || Yii::app()->user->checkAccess(' . User::ROLE_SECRETARY . ')',
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
        $model = new Lead100;
        $apiResult = null;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Lead100'])) {
            $model->attributes = $_POST['Lead100'];
            
            if($model->testMode) {
                // тестовый режим. найдем по источнику его данные
                $source = $model->source;
                if($source) {
                    $apiClient = new StoYuristovClient($source->appId, $source->secretKey, 1);
                    CustomFuncs::printr($apiClient);
                    
                    $apiClient->name = $model->name;
                    $apiClient->phone = $model->phone;
                    $apiClient->town = $model->town->name;
                    $apiClient->question = $model->question;

                    $apiResult = $apiClient->send();
                }
                
            } else {

                // проверим, нет ли лида с таким телефоном за последние 12 часов

    /*          $existingLeads = Yii::app()->db->createCommand()
                        ->select('phone')
                        ->from('{{lead100}}')
                        ->where('question_date>NOW()- INTERVAL 12 HOUR')
                        ->queryAll();
                // массив, в котором будут храниться телефоны лидов, которые добавлены в базу за последний день, чтобы не добавить одного лида несколько раз
                $existingLeadsPhones = array();

                foreach ($existingLeads as $existingLead) {
                    $existingLeadsPhones[] = Question::normalizePhone($existingLead['phone']);
                }

                $normalizedPhone = Question::normalizePhone($model->phone);
    */
                if ($model->findDublicates()) {
                    $model->addError('phone', "Лид с таким номером телефона уже добавлен за последние 12 часов");
                } else {
                    if ($model->save()) {
                        if (Yii::app()->request->isAjaxRequest) {
                            echo 'ok';
                            exit;
                        }
                        $this->redirect(array('view', 'id' => $model->id));
                    } else {
                        if (Yii::app()->request->isAjaxRequest) {
                            echo 'error';
                            exit;
                        }
                    }
                }
            }
        }

        $this->render('create', array(
            'model'     => $model,
            'apiResult' => $apiResult,
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

        if (isset($_POST['Lead100'])) {
            $model->attributes = $_POST['Lead100'];
            //CustomFuncs::printr($model);exit;
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
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
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        $this->redirect(array('index'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {

        $searchModel = new Lead100;

        $criteria = new CDbCriteria;

        $criteria->order = 't.id DESC';
        $criteria->with = array('questionObject.townByIP', 'town', 'town.region', 'source');
        $statusId = (isset($_GET['status'])) ? (int) $_GET['status'] : false;

        if ($statusId !== false) {
            $criteria->addColumnCondition(array('t.leadStatus' => $statusId));
            $criteria->addColumnCondition(array('campaignId!' => 'NULL'));
        }

        if (isset($_GET['Lead100'])) {
            // если используется форма поиска по контактам
            $searchModel->attributes = $_GET['Lead100'];
            $dataProvider = $searchModel->search();
        } else {
            // если форма не использовалась
            $dataProvider = new CActiveDataProvider('Lead100', array(
                'criteria' => $criteria,
                'pagination' => array(
                    'pageSize' => 50,
                ),
            ));
        }


        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ));
    }

    public function actionToQuestion($id) {
        $contact = $this->loadModel($id);

        $question = new Question();
        $question->scenario = 'convert';
        $question->townId = $contact->townId;
        $question->authorName = $contact->name;
        $question->questionText = $contact->question;
        $question->status = Question::STATUS_PUBLISHED;
        $question->publishDate = date("Y-m-d H:i:s");

        if ($question->save()) {
            $contact->questionId = $question->id;
            if ($contact->save()) {
                echo $contact->id;
            } else {
                CustomFuncs::printr($contact->errors);
                //throw new CHttpException(500,'Не удалось перевести лид в вопрос');
            }
        } else {
            CustomFuncs::printr($question->errors);
        }
    }

    // распределяет лиды: в CRM или в лид-сервисы
    public function actionSendLeads() {

        $criteria = new CDbCriteria;

        $criteria->addColumnCondition(array('leadStatus' => Lead100::LEAD_STATUS_DEFAULT));
        $criteria->addColumnCondition(array('question_date>' => date('Y-m-d')));
        $criteria->with = array('town', 'town.region');

        // сколько лидов обрабатывать за раз
        //$criteria->limit = 100;
        $criteria->limit = 10;

        $leads = Lead100::model()->findAll($criteria);

        foreach ($leads as $lead) {
            $campaignId = Campaign::getCampaignsForLead($lead->id);
            //echo $lead->id . ' - ' . $campaignId . PHP_EOL;
            if (!$campaignId) {
                continue;
            }

            $lead->sendToCampaign($campaignId);
        }

        //$this->redirect(array('/admin/lead/index', 'leadsSent'=>1));
    }

    // генерирует демо лиды
    public function actionGenerate() {
        $limit = 10;
        $towns = array(598);
        $sourceId = 3;
        $question = 'Тестовый текст вопроса';
        $status = Lead100::LEAD_STATUS_DEFAULT;
        $type = Lead100::TYPE_QUESTION;
        $names = array('Август', 'Августин', 'Аврор', 'Агап', 'Адам', 'Аксён', 'Алевтин', 'Александр', 'Алексей', 'Алексий', 'Альберт', 'Анастасий', 'Анатолий', 'Анвар', 'Андрей', 'Андрон', 'Анисим', 'Антип', 'Антон', 'Антонин', 'Аристарх', 'Аркадий', 'Арсений', 'Артамон', 'Артём', 'Артемий', 'Артур', 'Архип', 'Аскольд', 'Афанасий', 'Афиноген');

        for ($i = 0; $i < $limit; $i++) {
            $lead = new Lead100;

            $properties = array(
                'sourceId' => $sourceId,
                'question' => $question,
                'name' => $names[mt_rand(0, sizeof($names) - 1)],
                'status' => $status,
                'type' => $type,
                'townId' => $towns[mt_rand(0, sizeof($towns) - 1)],
                'active' => 1,
                'phone' => mt_rand(1000000000, 9999999999),
                'email' => 'bot_' . mt_rand(100000, 999999) . '@100yuristov.com',
            );

            $lead->attributes = $properties;

            if ($lead->save()) {
                echo "Лид " . $lead->id . ' сохранен<br />';
            } else {
                CustomFuncs::printr($lead->errors);
            }
        }
    }

    /*
     * распределяет лидов по покупателям
     */

    public function actionDispatch() {
        $criteria = new CDbCriteria;

        $criteria->addColumnCondition(array('leadStatus' => Lead100::LEAD_STATUS_DEFAULT));
        $criteria->addColumnCondition(array('question_date>' => date('Y-m-d')));
        $criteria->with = array('town', 'town.region');

        // сколько лидов обрабатывать за раз
        $criteria->limit = 50;

        $leads = Lead100::model()->findAll($criteria);

        foreach ($leads as $lead) {
            echo $lead->id . " " . $lead->name . ', город: ' . $lead->town->name . ', регион:' . $lead->town->region->name;
            echo "<p>Подходящие кампании:</p>";
            echo $campaignId = Campaign::getCampaignsForLead($lead->id);

            if (!$campaignId) {
                // если для лида нет ни одной кампании, идем к следующему лиду
                continue;
            }

            if ($lead->sendToCampaign($campaignId)) {
                echo "Лид отправлен в кампанию";
            } else {
                echo "С этим лидом что-то пошло не так";
            }
            echo "<hr />";
        }
    }

    public function actionChangeStatus() {
        $leadId = (isset($_POST['id'])) ? (int) $_POST['id'] : false;
        $status = (isset($_POST['status'])) ? (int) $_POST['status'] : false;

        if ($leadId === false || $status === false) {
            echo json_encode(array('code' => 400, 'message' => 'Error, not enough data'));
            exit;
        }

        $lead = Lead100::model()->findByPk($leadId);

        if (!$lead) {
            echo json_encode(array('code' => 404, 'message' => 'Lead100 not found'));
            exit;
        }

        $lead->leadStatus = $status;

        // найдем кампанию, в которую отправлен лид
        // если новый статус - Брак, вернем деньги за лида на баланс пользователя
        $campaign = $lead->campaign;
        if ($lead->campaign && $lead->leadStatus == Lead100::LEAD_STATUS_BRAK) {
            $buyer = $campaign->buyer;
            $buyer->setScenario('balance');
            $buyer->balance += $lead->price;
            // записываем данные о возврате средств на баланс пользователя
            $transaction = new TransactionCampaign;
            $transaction->sum = (int) $lead->price;
            $transaction->campaignId = $campaign->id;
            $transaction->buyerId = $buyer->id;
            $transaction->description = 'Возврат за лид ID=' . $lead->id;

            if (!$transaction->save()) {
                CustomFuncs::printr($transaction->errors);
                echo json_encode(array('code' => 500, 'id' => $lead->id, 'message' => 'Не удалось сохранить транзакцию'));
                exit;
            }

            if (!$buyer->save()) {
                echo json_encode(array('code' => 500, 'id' => $lead->id, 'message' => 'Не удалось обновить баланс пользователя'));
                exit;
            }
        }

        if ($lead->save()) {
            echo json_encode(array('code' => 0, 'id' => $lead->id, 'message' => 'Статус изменен'));
            exit;
        } else {
            echo json_encode(array('code' => 500, 'id' => $lead->id, 'message' => 'Статус не изменен'));
            exit;
        }
    }

    /*
     * вывод статистики продаж лидов
     * GET параметры:
     * type (dates|campaigns) - разбивка по датам или кампаниям
     */

    public function actionStats() {

        // найдем все годы, в которые есть контакты
        $yearsRows = Yii::app()->db->cache(600)->createCommand()
                ->select('DISTINCT(YEAR(question_date)) y')
                ->from('{{lead100}}')
                ->where('price != 0 AND YEAR(question_date)!=0')
                ->queryColumn();
        $yearsArray = array();
        foreach ($yearsRows as $k => $v) {
            $yearsArray[$v] = $v;
        }



        // по умолчанию группировка по датам
        $type = (isset($_GET['type'])) ? $_GET['type'] : 'dates';
        $month = (isset($_GET['month'])) ? $_GET['month'] : date("n");
        $year = (isset($_GET['year'])) ? $_GET['year'] : date("Y");

        $leadsRows = Yii::app()->db->createCommand()
                ->select('l.price summa, DATE(l.question_date) lead_date, l.campaignId campaignId, l.buyPrice, l.leadStatus')
                ->from('{{lead100}} l')
                ->where('l.price != 0 AND MONTH(l.question_date)="' . $month . '" AND YEAR(l.question_date)="' . $year . '"')
                ->order('lead_date DESC')
                ->queryAll();

        //CustomFuncs::printr($leadsRows);

        $sumArray = array();
        $kolichArray = array();
        $buySumArray = array();


        if ($type == 'dates') {
            foreach ($leadsRows as $row) {
                if ($row['leadStatus'] == Lead100::LEAD_STATUS_SENT) {
                    $sumArray[$row['lead_date']] += $row['summa'];
                    $kolichArray[$row['lead_date']] ++;
                }

                $buySumArray[$row['lead_date']] += $row['buyPrice'];
            }
        }

        if ($type == 'campaigns') {
            foreach ($leadsRows as $row) {
                if ($row['leadStatus'] == Lead100::LEAD_STATUS_SENT) {
                    $sumArray[$row['campaignId']] += $row['summa'];
                    $kolichArray[$row['campaignId']] ++;
                }

                $buySumArray[$row['campaignId']] += $row['buyPrice'];
            }
        }

        // получим данные по расходам на Директ
        $expencesArray = array();
        $expencesRows = Yii::app()->db->createCommand()
                ->select('date, expences')
                ->from('{{direct}}')
                ->where('MONTH(date)="' . $month . '" AND YEAR(date)="' . $year . '"')
                ->order('date DESC')
                ->queryAll();

        foreach ($expencesRows as $row) {
            $expencesArray[$row['date']] = $row['expences'];
        }

        //CustomFuncs::printr($expencesArray);
        //CustomFuncs::printr($kolichArray);
        // статистика по VIP вопросам
        $vipRows = Yii::app()->db->createCommand()
                ->select('SUM(value) sum, DATE(datetime) date')
                ->from('{{money}}')
                ->where('type=:type AND direction=:direction AND MONTH(datetime) = :month AND YEAR(datetime) = :year', array(
                    ':type' => Money::TYPE_INCOME,
                    ':direction' => 504,
                    ':month' => $month,
                    ':year' => $year,
                ))
                ->group('date')
                ->queryAll();

        $vipStats = array();
        foreach ($vipRows as $row) {
            $vipStats[$row['date']] = $row['sum'];
        }

        $this->render('stats', array(
            'type' => $type,
            'yearsArray' => $yearsArray,
            'month' => $month,
            'year' => $year,
            'sumArray' => $sumArray,
            'kolichArray' => $kolichArray,
            'buySumArray' => $buySumArray,
            'expencesArray' => $expencesArray,
            'vipStats' => $vipStats,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Lead100 the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Lead100::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Lead100 $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'lead-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
