<?php

class QuestionController extends Controller
{

	public $layout='//frontend/main';

        /*
         * адреса для оплаты вопросов через Яндекс кассу
         * /question/paymentSuccess - страница успешной оплаты
         * /question/paymentFail - страница неуспешной оплаты
         * /question/paymentCheck - страница для передачи запроса на проверку заказа
         * /question/paymentAviso - страница для передачи уведомления о переводе/отказе
         */
        
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
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
                array('allow', // allow all users 
                        'actions'=>array('index', 'view', 'create', 'thankYou','rss', 'call', 'weCallYou', 'docsRequested', 'docs', 'getServices', 'services', 'upgrade', 'paymentSuccess', 'paymentFail', 'paymentCheck', 'paymentAviso', 'confirm', 'sendLead'),
                        'users'=>array('*'),
                ),
                array('allow', // allow authenticated user to perform 'search'
                        'actions'=>array('search'),
                        'users'=>array('@'),
                ),
                array('deny',  // deny all users
                        'users'=>array('*'),
                ),
            );
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
            $this->layout = "//frontend/short";
            
            $model = Question::model()->with('categories')->findByPk($id);
            if(!$model) {
                throw new CHttpException(404,'Вопрос не найден');
            }
            
            // проверим, правильный ли статус у вопроса
            if(!in_array($model->status, array(Question::STATUS_CHECK, Question::STATUS_PUBLISHED))) {
                throw new CHttpException(404,'Вопрос не найден');
            }
            
            // если передан GET параметр autologin, попытаемся залогинить пользователя
            User::autologin($_GET);
            
            $commentModel = new Comment;
            
            $justPublished = ($_GET['justPublished'])?true:false;
            
            $answerModel = new Answer();

            if(isset($_POST['Answer'])) {
                // отправлен ответ, сохраним его
                $answerModel->attributes = $_POST['Answer'];
                $answerModel->authorId = Yii::app()->user->id;
                $answerModel->questionId = $model->id;
                
                if($answerModel->save()){
                    $this->redirect(array('/question/view', 'id'=>$model->id));
                }
                
            }
            
            if(isset($_POST['Comment'])) {
                // отправлен ответ, сохраним его
                $commentModel->attributes = $_POST['Comment'];
                $commentModel->authorId = Yii::app()->user->id;
                                
                if($commentModel->save()){
                    $this->redirect(array('/question/view', 'id'=>$model->id));
                }
                
            }
            
            
            $criteria = new CDbCriteria;
            $criteria->order = 't.id ASC';
            $criteria->addColumnCondition(array('questionId'=>$model->id));
            
            $answersDataProvider = new CActiveDataProvider('Answer', array(
                'criteria'=>$criteria,        
                'pagination'=>array(
                            'pageSize'=>20,
                        ),
            ));
            
            $categories = $model->categories;
            
            // массив для хранения id категорий данного вопроса
            $categoriesArray = array();
            
            foreach($categories as $cat) {
                $categoriesArray[] = $cat->id;
            }
            
            
            
            $questionsSimilar = Yii::app()->db->createCommand()
                    ->select('q.id id, q.publishDate date, q.title title, COUNT(*) counter')
                    ->from('{{question}} q')
                    ->leftJoin('{{answer}} a', 'q.id=a.questionId')
                    ->join("{{question2category}} q2c", "q2c.qId=q.id AND q2c.cId IN (:catId)", array(':catId'=>$categoriesArray))
                    ->group('q.id')
                    ->where('q.status=:status AND a.id IS NOT NULL', array(':status'=>  Question::STATUS_PUBLISHED))
                    ->limit(8)
                    ->order('q.publishDate DESC')
                    ->queryAll();
            
                    
            // модель для формы вопроса
            $newQuestionModel = new Question();
                        
            $this->render('view',array(
                    'model'                 =>  $model,
                    'answersDataProvider'   =>  $answersDataProvider,
                    'newQuestionModel'      =>  $newQuestionModel,
                    'questionsSimilar'      =>  $questionsSimilar,
                    'answerModel'           =>  $answerModel,
                    'justPublished'         =>  $justPublished,
                    'commentModel'          =>  $commentModel,
            ));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$lead = new Lead100();
                $question = new Question();
                $question->setScenario('create');
                
                // параметр, определяющий, будет ли в форме блок выбора цены (форма платного вопроса)
                $pay = (isset($_GET['pay']))?true:false;
                
                $allDirectionsHierarchy = QuestionCategory::getDirections(true, true);              
                $allDirections = QuestionCategory::getDirectionsFlatList($allDirectionsHierarchy);

                
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Question']))
		{
			$question->attributes = $_POST['Question'];
                        $question->phone = preg_replace('/([^0-9])/i', '', $question->phone);
                        
                       
                        if($question->sessionId == '' && $question->questionText!='' && $question->authorName!='') {
                            $question->preSave();
                        } else {
                            /*
                             * если вопрос был предсохранен, создадим объект Question из записи в базе,
                             * чтобы при сохранении вопроса произошел update записи
                             */
                            $question = Question::model()->find(array(
                                'condition' =>  'sessionId = "'.$question->sessionId . '"'
                            ));
                            $question->attributes = $_POST['Question'];
                            $question->phone = Question::normalizePhone($question->phone);
                            $question->status = Question::STATUS_NEW;
                        }
                        
                        $question->setScenario('create');
                        $question->validate();
                        
                        $lead->name = $question->authorName;
                        $lead->question = $question->questionText;
                        $lead->phone = $question->phone;
                        $lead->email = $question->email;
                        $lead->townId = $question->townId;
                        $lead->sourceId = 3; // Lidlaw
                        $lead->leadStatus = Lead100::LEAD_STATUS_DEFAULT; // по умолчанию лид никуда не отправляем
			//CustomFuncs::printr($lead);exit;
                        
                        $duplicates = $lead->findDublicates(600);
                        //CustomFuncs::printr($duplicates);exit;
                        if($duplicates) {
                            throw new CHttpException(400,'Похоже, Вы пытаетесь отправить заявку несколько раз. Ваша заявка уже сохранена.');
                        }
                        
                        if($lead->save()) {
                                $question->status = Question::STATUS_NEW;
                                
                                // проверим, есть ли в базе пользователь с таким мейлом
                                $findUserResult = Yii::app()->db->createCommand()
                                        ->select('id')
                                        ->from("{{user}}")
                                        ->where("email=:email AND email!=''", array(":email"=>$lead->email))
                                        ->limit(1)
                                        ->queryRow();
                                if($findUserResult) {
                                    // если есть, то запишем id этого пользователя в авторы вопроса
                                    $question->authorId = $findUserResult['id'];
                                } else {
                                    // если пользователь не найден, при создании вопроса создадим пользователя
                                    // будем делать это на этапе подписки на ответы при создании вопроса
                                    //$question->createAuthor();
                                    
                                }
                                
                                
                                $question->save();
                                
                                // сохраним категории, к которым относится вопрос, если категория указана
                                if(isset($_POST['Question']['categories']) && $_POST['Question']['categories']!=0) {
                                    $q2cat = new Question2category();
                                    $q2cat->qId = $question->id;
                                    $questionCategory = $_POST['Question']['categories'];
                                    $q2cat->cId = $questionCategory;
                                    // сохраняем указанную категорию
                                    if($q2cat->save()) {
                                        // проверим, не является ли указанная категория дочерней
                                        // если является, найдем ее родителя и запишем в категории вопроса
                                        foreach($allDirectionsHierarchy as $parentId=>$parentCategory) {
                                            if(!$parentCategory['children']) continue;
                                            
                                            foreach($parentCategory['children'] as $childId=>$childCategory) {
                                                if($childId == $questionCategory) {
                                                    $q2cat = new Question2category();
                                                    $q2cat->qId = $question->id;
                                                    $q2cat->cId = $parentId;
                                                    $q2cat->save();
                                                    break;
                                                }
                                            }
                                        }

                                    }
                                    
                                } 
                            
				//$this->redirect(array('thankYou'));
				$this->redirect(array('confirm', 'qId'=>$question->id, 'sId'=>$question->sessionId));
                        } else {
                            //CustomFuncs::printr($lead->errors);
                            //throw new CHttpException(400,'Что-то пошло не так. Ваш вопрос не удалось отправить.');
                        }
		}
                
                $townsArray = Town::getTownsIdsNames();
                
		$this->render('create',array(
			'model'         =>  $question,
                        'allDirections' =>  $allDirections,
                        'categoryId'    =>  $categoryId,
                        'townsArray'    =>  $townsArray,
                        'pay'           =>  $pay,
		));
	}
        
        /*
         * страница, где мы запрашиваем у пользователя его почту, записываем в вопрос и отправляем письмо со
         * ссылкой активации аккаунта
         */
        public function actionConfirm()
        {
            $this->layout = '//frontend/short';
            
            $qId = (isset($_GET['qId']))?(int)$_GET['qId']:false;
            $sId = (isset($_GET['sId']))?$_GET['sId']:false;
            
            if(!$qId || ! $sId) {
                throw new CHttpException(404,'Не задан ID вопроса');
            }
            
            $criteria = new CDbCriteria;
            $criteria->addColumnCondition(array('id'=>$qId, 'sessionId'=>$sId));
            $question = Question::model()->find($criteria);
            
            if(!$question) {
                throw new CHttpException(404,'Не найден вопрос');
            }
            
            if($question->email) {
                throw new CHttpException(400,'У данного вопроса уже задан Email');
            }
            
            if(isset($_POST['Question']) && isset($_POST['Question']['email'])) {
                $question->email = $_POST['Question']['email'];
                
                if($question->createAuthor()) {
                    if($question->save()) {
                        $this->redirect(array('thankYou'));
                    }
                } 
                
            }
            
            $this->render('confirm', array(
                'question'   =>  $question,
            ));
        }

        public function actionThankYou()
        {
            $this->layout = '//frontend/short';
            $this->render('thankYou');
        }

        /**
	 * Lists all models.
	 */
	public function actionIndex()
	{            
            $criteria = new CDbCriteria;
            $criteria->limit = 40;
            $criteria->with = 'answersCount';
            $criteria->addCondition('status IN (' . Question::STATUS_PUBLISHED . ', ' . Question::STATUS_CHECK . ')');
            $criteria->order = 'publishDate DESC';
            
            $questions = Question::model()->cache(600)->findAll($criteria);       
                        
            $this->render('index',array(
                'questions'   =>  $questions,
            ));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Question('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Question']))
			$model->attributes=$_GET['Question'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Question the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Question::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Question $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='question-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        // generates RSS 2.0 feed with active trips
        public function actionRss()
        {
            $criteria = new CDbCriteria;
            $criteria->addColumnCondition(array('t.status' =>  Question::STATUS_PUBLISHED));
            $criteria->order = "t.id DESC";
            $criteria->with = array('answersCount');
            $questions = Question::model()->cache(600)->findAll($criteria);
                        
            Yii::import('ext.feed.*');
            // RSS 2.0 is the default type
            $feed = new EFeed();

            $feed->title= Yii::app()->name;
            $feed->description = 'Вопросы квалифицированным юристам';


            $feed->addChannelTag('language', 'ru-ru');
            $feed->addChannelTag('pubDate', date(DATE_RSS, time()));
            $feed->addChannelTag('link', 'http://www.100yuristov.com/question/rss' );

            // * self reference
            //$feed->addChannelTag('atom:link','http://www.100yuristov.com/question/rss');

            foreach($questions as $question)
            {
                $item = $feed->createNewItem();

                
                if($question->answersCount) {
                    $item->title = CHtml::encode($question->title) . ' (' . $question->answersCount . ' ' . CustomFuncs::numForms($question->answersCount, 'ответ', "ответа", "ответов") . ")";
                } else {
                    $item->title = CHtml::encode($question->title);
                }
                
                $item->link = "http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('question/view',array('id'=>$question->id));
                $item->date = time();

                $item->description = CHtml::encode($question->questionText);

                $feed->addItem($item);
            }
            $feed->generateFeed();
            Yii::app()->end();
        }
        
        public function actionSearch()
        {
            $this->layout = '//frontend/short';
            
            // модель для формы поиска по вопросам
            $searchModel = new QuestionSearch();
            
            // лимит на количество найденных вопросов
            $searchModel->limit = 100;
//            $searchModel->today = true;
//            $searchModel->townId = 598;
//            $searchModel->noAnswers = true;
            
            
            $searchModel->attributes = $_GET['QuestionSearch'];

            if($searchModel->townId) {
                $searchModel->townName = Town::getName($searchModel->townId);
            }
            $questions = $searchModel->search();
            $questionDataProvider = new CArrayDataProvider($questions, array(
                'pagination'    =>  array(
                    'pageSize'  =>  20,
                ),
            ));

            
            
            
            $this->render('search',array(
                    'searchModel'   =>  $searchModel,
                    'dataProvider'  =>  $questionDataProvider,
		));
        }
        
        
        public function actionCall()
        {
            //$this->layout = "//frontend/short";
            
            $lead = new Lead100();
            
            if(isset($_POST['Lead100'])) {
                $lead->attributes = $_POST['Lead100'];
                $lead->phone = preg_replace('/([^0-9])/i', '', $lead->phone);
                $lead->sourceId = 3;
                $lead->type = Lead100::TYPE_CALL;
                
                if($lead->validate()) {
                    $lead->question = CHtml::encode('Нужна консультация юриста. Перезвоните мне. ' . $lead->question);
                
                    if($lead->save()) {
                        $this->redirect(array('weCallYou'));
                    }
                }      
                
            }
            
            $townsArray = Town::getTownsIdsNames();
            
            $this->render('call', array(
                'model'         =>  $lead,
                'townsArray'    =>  $townsArray,
            ));
            
        }
        
        public function actionWeCallYou()
        {
            $this->layout = "//frontend/short";
            $this->render('weCallYou');
        }
        
        
        public function actionDocs()
        {
            //$this->layout = "//frontend/short";
            
            $lead = new Lead100();
            
            if(isset($_POST['Lead100'])) {
                $lead->attributes = $_POST['Lead100'];
                $lead->phone = preg_replace('/([^0-9])/i', '', $lead->phone);
                $lead->sourceId = 3;
                $lead->type = Lead100::TYPE_DOCS;
                
                if($lead->validate()) {
                    $docType = $_POST['question_hidden'];
                    $lead->question = CHtml::encode('Заявка на документ. ' . $docType . '. ' . $lead->question);

                    if($lead->save()) {
                        $this->redirect(array('docsRequested'));
                    }
                }      
                
            }
            
            $townsArray = Town::getTownsIdsNames();
            
            $this->render('docs', array(
                'model'         =>  $lead,
                'townsArray'    =>  $townsArray,
            ));
            
        }
        
        public function actionDocsRequested()
        {
            $this->layout = "//frontend/short";
            $this->render('docsRequested');
        }
        
        public function actionServices()
        {
            
            $lead = new Lead100();
            
            if(isset($_POST['Lead100'])) {
                $lead->attributes = $_POST['Lead100'];
                $lead->phone = preg_replace('/([^0-9])/i', '', $lead->phone);
                $lead->sourceId = 3;
                $lead->type = Lead100::TYPE_SERVICES;
                
                if($lead->validate()) {
                    $lead->question = CHtml::encode('Нужны услуги юриста. ' . $lead->question);
                
                    if($lead->save()) {
                        $this->redirect(array('getServices'));
                    }
                }      
                
            }
            
            $townsArray = Town::getTownsIdsNames();
            
            $this->render('services', array(
                'model'         =>  $lead,
                'townsArray'    =>  $townsArray,
            ));
            
        }
        
        
        public function actionGetServices()
        {
            $this->layout = "//frontend/short";
            $this->render('getServices');
        }
        
        
        // изменения статуса вопроса на платный
        public function actionUpgrade($id)
        {
            $question = Question::model()->findByPk($id);
            
            if(!$question) {
                throw new CHttpException(404,'Вопрос не найден');
            }
            
            $level = (isset($_GET['level']) && (int)$_GET['level']>0)?(int)$_GET['level']:  Question::LEVEL_1;
            
            $questionPrice = Question::getPriceByLevel($level);
            $question->price = $questionPrice;
            
            $this->render('upgrade', array(
                'question'  =>  $question,
            ));
        }

        // платеж успешно
        public function actionPaymentSuccess()
        {
            $params = $_GET;
            
            
            $this->render('paymentSuccess', array('params'=>$params));
        }
        
        // платеж не успешно
        public function actionPaymentFail()
        {
            //https://100yuristov.com/question/paymentFail/test/1/?orderSumAmount=99.00&cdd_exp_date=1221&shopArticleId=367734&paymentPayerCode=4100322062290&cdd_rrn=&external_id=deposit&paymentType=AC&requestDatetime=2016-10-06T17%3A39%3A22.418%2B03%3A00&depositNumber=sO8G8EwrcotOG1AgYAadKefc5cQZ.001f.201610&cps_user_country_code=PL&orderCreatedDatetime=2016-10-06T17%3A39%3A21.921%2B03%3A00&sk=y0ef7319b7a2ed83de96f44ec0cd4c83c&action=PaymentFail&shopId=73868&scid=542085&rebillingOn=false&orderSumBankPaycash=1003&cps_region_id=216&orderSumCurrencyPaycash=10643&merchant_order_id=21516_061016173905_00000_73868&unilabel=1f8875c8-0009-5000-8000-000015ab36bd&cdd_pan_mask=444444%7C4448&customerNumber=21516&yandexPaymentId=2570060865738&invoiceId=2000000925475
            $params = $_GET;
            $this->render('paymentFail', array('params'=>$params));
        }
        
        // запрос от яндекса на проверку платежа
        public function actionPaymentCheck()
        {
            
            $yaKassa = new YandexKassa($_POST);
            
            $paymentLog = fopen($_SERVER['DOCUMENT_ROOT'] . YandexKassa::PAYMENT_LOG_FILE, 'w+');
            
            foreach($_POST as $k=>$v) {
                fwrite($paymentLog, $k . '=>' . $v . '; ');
            }
            
            if(YandexKassa::checkMd5($_POST)) {
                fwrite($paymentLog, "MD5 correct!");
                $yaKassa->formResponse(0,'OK');
                //$yaKassa->formResponse(1,'Error'); // just for test
            } else {
                fwrite($paymentLog, "MD5 incorrect!");
                $yaKassa->formResponse(1,'Ошибка авторизации');
            }
        }
        
        // запроса от яндекса о платеже или отказе
        public function actionPaymentAviso()
        {
            $yaKassa = new YandexKassa($_POST);
            
            $paymentLog = fopen($_SERVER['DOCUMENT_ROOT'] . YandexKassa::PAYMENT_LOG_FILE, 'w+');
            
            foreach($_POST as $k=>$v) {
                fwrite($paymentLog, $k . '=>' . $v . '; ');
            }
            
            if(YandexKassa::checkMd5($_POST)) {
                fwrite($paymentLog, "MD5 correct!");
                if($yaKassa->payQuestion()) {
                    $yaKassa->formResponse(0,'OK', 'paymentAviso');
                } else {
                    $yaKassa->formResponse(1,'Error', 'paymentAviso');
                }  
            } else {
                fwrite($paymentLog, "MD5 incorrect!");
                $yaKassa->formResponse(1,'Error', 'paymentAviso');
            }
        }
        
        public function actionSendLead()
        {
            // отключаем вывод профилирования на странице
            //Yii::app()->log->setRoutes(array('CProfileLogRoute'=>array('enabled'=>false)));
            
            
            if(!isset($_POST)) {
                echo json_encode(array('code'=>400,'message'=>'No input data'));
                exit;
            }
            $model = new Lead100;
            $leadAppId = 'yurCrm';
            
            // проверим параметр appId
            if($leadAppId != $_POST['appId']) {
                echo json_encode(array('code'=>400,'message'=>'Unknown sender. Check App ID parameter'));
                exit;
            }
            
            $model->attributes = $_POST;
            $model->sourceId = 3;
            $model->type = Lead100::TYPE_INCOMING_CALL;
            $model->phone = Question::normalizePhone($model->phone);
            
            $appSecret = 'Let me speak from my heart';
            // сформируем подпись на основе принятых данных
            $signature = md5($model->name . $model->phone . $model->email . $model->question . $model->townId . $_POST['appId'] . $appSecret);
            
            // проверим подпись
            if($signature !== $_POST['signature']) {
                echo json_encode(array('code'=>403,'message'=>'Signature wrong'));
                exit;
            }
            
            
//            echo json_encode($model->name);exit;
            
            if($model->findDublicates()) {
                die(json_encode(array('code'=>400, 'message'=>'Dublicates found')));
                exit;
            }
            
            if($model->save()) {
                echo json_encode(array('code'=>200,'message'=>'OK'));
//                echo json_encode($model->id);
                exit;
            } else {
                echo json_encode(array('code'=>500,'message'=>'Lead not saved.', 'errors'=>$model->errors));
                exit;
            }
        }
}
