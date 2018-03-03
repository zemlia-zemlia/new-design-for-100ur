<?php

class QuestionController extends Controller {

    public $layout = '//frontend/question';

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
    public function filters() {
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
    public function accessRules() {
        return array(
            array('allow', // allow all users 
                'actions' => array('index', 'archive', 'view', 'create', 'thankYou', 'rss', 'rssAnswers', 'call', 'callBack', 'weCallYou', 'docsRequested', 'docs', 'getServices', 'services', 'upgrade', 'paymentSuccess', 'paymentFail', 'paymentCheck', 'paymentAviso', 'confirm', 'sendLead'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'search'
                'actions' => array('search', 'updateAnswer', 'checkCommentsAsRead'),
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
        // редирект для страниц с пагинацией в адресе
        if ($_GET['Question_page']) {
            $this->redirect(array('view', 'id' => $id), true, 301);
        }

        $model = Question::model()->with('categories')->findByPk($id);
        if (!$model) {
            throw new CHttpException(404, 'Вопрос не найден');
        }

        // проверим, правильный ли статус у вопроса
        if (!in_array($model->status, array(Question::STATUS_CHECK, Question::STATUS_PUBLISHED))) {
            throw new CHttpException(404, 'Вопрос не найден');
        }

        // если передан GET параметр autologin, попытаемся залогинить пользователя
        User::autologin($_GET);

        $commentModel = new Comment;

        $justPublished = ($_GET['justPublished']) ? true : false;

        $answerModel = new Answer();

        if (isset($_POST['Answer'])) {
            // отправлен ответ, сохраним его
            $answerModel->attributes = $_POST['Answer'];
            $answerModel->authorId = Yii::app()->user->id;
            $answerModel->questionId = $model->id;
            $answerModel->datetime = date("Y-m-d h:i:s");

            if (Yii::app()->user->checkAccess(User::ROLE_ROOT)) {
                $answerModel->setScenario('addVideo');
            }

            if ($answerModel->save()) {
                // записываем время ответа в запись о пользователе
                Yii::app()->db->createCommand()
                        ->update('{{user}}', array('lastAnswer' => $answerModel->datetime), 'id=:id', array(':id' => Yii::app()->user->id));
                
                $this->redirect(array('/question/view', 'id' => $model->id));
            }
        }

        if (isset($_POST['Comment'])) {
            // отправлен ответ, сохраним его
            $commentModel->attributes = $_POST['Comment'];
            $commentModel->authorId = Yii::app()->user->id;

            // комментарии от юристов сразу помечаем как проверенные
            if (Yii::app()->user->role == User::ROLE_JURIST) {
                $commentModel->status = Comment::STATUS_CHECKED;
            }

            // проверим, является ли данный комментарий дочерним для другого комментария
            if (isset($commentModel->parentId) && $commentModel->parentId > 0) {
                // является, сохраним его как дочерний комментарий
                $rootComment = Comment::model()->findByPk($commentModel->parentId);
                $commentModel->appendTo($rootComment);
            } else {
                // не является, сохраним его как комментарий верхнего уровня
            }
//                CustomFuncs::printr($commentModel->attributes);
//                exit;
            // сохраняем комментарий с учетом его иерархии
            if ($commentModel->saveNode()) {
                $this->redirect(array('/question/view', 'id' => $model->id));
            }
        }

        // выборка ответов
        $criteria = new CDbCriteria;
        $criteria->order = 't.id ASC';
        $criteria->with = "comments";
        $criteria->addColumnCondition(array('questionId' => $model->id));

        $answersDataProvider = new CActiveDataProvider('Answer', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));

        // массив с id авторов ответов к текущему вопросу
        // запишем в него авторов ответов, чтобы не дать юристу ответить на данный вопрос дважды
        $answersAuthors = array();

        foreach ($answersDataProvider->data as $answer) {
            $answersAuthors[] = $answer->authorId;
        }

        $categories = $model->categories;

        // массив для хранения id категорий данного вопроса
        $categoriesArray = array();

        foreach ($categories as $cat) {
            $categoriesArray[] = $cat->id;
        }

        if (Yii::app()->user->role == User::ROLE_JURIST) {
            // найдем последний запрос на смену статуса
            $lastRequest = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from("{{userStatusRequest}}")
                    ->where("yuristId=:id AND isVerified=0", array(':id' => Yii::app()->user->id))
                    ->order('id DESC')
                    ->limit(1)
                    ->queryAll();
        } else {
            $lastRequest = null;
        }

        // модель для формы вопроса
        $newQuestionModel = new Question();
        
        if(Yii::app()->user->role == User::ROLE_JURIST) {
            $model->checkCommentsAsRead(Yii::app()->user->id);
        }

        $this->render('view', array(
            'model' => $model,
            'answersDataProvider' => $answersDataProvider,
            'answersAuthors' => $answersAuthors,
            'newQuestionModel' => $newQuestionModel,
            'answerModel' => $answerModel,
            'justPublished' => $justPublished,
            'commentModel' => $commentModel,
            'lastRequest' => $lastRequest,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $this->layout = "//frontend/smart";

        $lead = new Lead100();
        $question = new Question();
        $question->setScenario('create');

        // параметр, определяющий, будет ли в форме блок выбора цены (форма платного вопроса)
        $pay = (isset($_GET['pay'])) ? true : false;

        $allDirectionsHierarchy = QuestionCategory::getDirections(true, true);
        $allDirections = QuestionCategory::getDirectionsFlatList($allDirectionsHierarchy);


        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Question'])) {
            $question->attributes = $_POST['Question'];
            $question->phone = preg_replace('/([^0-9])/i', '', $question->phone);
            
            // если пользователь пришел по партнерской ссылке, запишем в вопрос id источника
            if(Yii::app()->user->getState('sourceId')) {
                $source = Leadsource100::model()->findByPk(Yii::app()->user->getState('sourceId'));
                if($source->type == Leadsource100::TYPE_QUESTION) {
                    $question->sourceId = Yii::app()->user->getState('sourceId');
                    $question->buyPrice = Yii::app()->params['questionPrice'];
                }
            }

            /* if(!$question->townId) {
              $question->townId = (Yii::app()->user->getState('currentTownId')) ? Yii::app()->user->getState('currentTownId') : 0;
              } */

            if ($question->sessionId == '' && $question->questionText != '' && $question->authorName != '') {
                if (!$question->preSave()) {
                    // если вопрос не предсохранился, очищаем свойство sessionId
                    $question->sessionId = '';
                }
            } else {
                /*
                 * если вопрос был предсохранен, создадим объект Question из записи в базе,
                 * чтобы при сохранении вопроса произошел update записи
                 */
                if ($question->sessionId != '') {
                    $question = Question::model()->find(array(
                        'condition' => 'sessionId = "' . $question->sessionId . '"'
                    ));
                }
                $question->attributes = $_POST['Question'];
                $question->phone = Question::normalizePhone($question->phone);
                $question->status = Question::STATUS_NEW;
                $question->ip = (isset($_SERVER['HTTP_X_REAL_IP'])) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
                $question->townIdByIP = Yii::app()->user->getState('currentTownId');
            }

            $question->setScenario('create');
            $question->validate();

            if (empty($question->errors)) {
                // Создаем лид
                $lead->name = $question->authorName;
                $lead->question = $question->questionText;
                $lead->phone = $question->phone;
                $lead->email = $question->email;
                $lead->townId = $question->townId;
                
                
                if($source && $source->type == Leadsource100::TYPE_LEAD) {
                    $lead->sourceId = $source->id;
                    // посчитаем цену покупки лида, исходя из города и региона
                    $prices = $lead->calculatePrices();
                    if($prices[0]) {
                        $lead->buyPrice = $prices[0];
                    } else {
                        $lead->buyPrice = 0;
                    }
                } else {
                    $lead->sourceId = 3; // 100 юристов
                }
                
                $lead->leadStatus = Lead100::LEAD_STATUS_DEFAULT; // по умолчанию лид никуда не отправляем
                //CustomFuncs::printr($lead);exit;

                $duplicates = $lead->findDublicates(86400);
                //CustomFuncs::printr($duplicates);exit;
                if ($duplicates) {
                    throw new CHttpException(400, 'Похоже, Вы пытаетесь отправить заявку несколько раз. Ваша заявка уже сохранена.');
                }

                if ($lead->save()) {
                    $question->status = Question::STATUS_NEW;

                    if ($question->save()) {
                        // после сохранения вопроса удаляем id источника из сессии, чтобы вебмастер не добавил несколько вопросов
                        Yii::app()->user->setState('sourceId', null);
                        $lead->questionId = $question->id;
                        $lead->save();

                        // сохраним категории, к которым относится вопрос, если категория указана
                        if (isset($_POST['Question']['categories']) && $_POST['Question']['categories'] != 0) {
                            $q2cat = new Question2category();
                            $q2cat->qId = $question->id;
                            $questionCategory = $_POST['Question']['categories'];
                            $q2cat->cId = $questionCategory;
                            // сохраняем указанную категорию
                            if ($q2cat->save()) {
                                // проверим, не является ли указанная категория дочерней
                                // если является, найдем ее родителя и запишем в категории вопроса
                                foreach ($allDirectionsHierarchy as $parentId => $parentCategory) {
                                    if (!$parentCategory['children'])
                                        continue;

                                    foreach ($parentCategory['children'] as $childId => $childCategory) {
                                        if ($childId == $questionCategory) {
                                            $q2cat = new Question2category();
                                            $q2cat->qId = $question->id;
                                            $q2cat->cId = $parentId;
                                            $q2cat->save();
                                            break;
                                        }
                                    }
                                }
                            }
                            // если у вопроса есть категории, запишем их и лиду
                            foreach($question->categories as $cat) {
                                $lead2category = new Lead2Category;
                                $lead2category->leadId = $lead->id;
                                $lead2category->cId = $cat->id;
                                $lead2category->save();
                            }
                        }

                        $this->redirect(array('confirm', 'qId' => $question->id, 'sId' => $question->sessionId));
                    }
                }
            }
        }

        //$townsArray = Town::getTownsIdsNames();
        //$townsArray = array();
        //CustomFuncs::printr($townsArray);

        $this->render('create', array(
            'model'         => $question,
            'allDirections' => $allDirections,
            'categoryId'    => $categoryId,
            'pay'           => $pay,
        ));
    }

    /**
     * Страница редактирования ответа
     * @param type $id
     */
    public function actionUpdateAnswer($id) {

        $answer = Answer::model()->findByPk($id);
        if (!$answer) {
            throw new CHttpException(404, 'Ответ не найден');
        }
        if (!(Yii::app()->user->role == User::ROLE_JURIST && $answer->authorId == Yii::app()->user->id && time() - strtotime($answer->datetime) < Answer::EDIT_TIMEOUT)) {
            throw new CHttpException(403, 'Вы не можете редактировать этот ответ');
        }

        if (isset($_POST['Answer'])) {
            $answer->attributes = $_POST['Answer'];

            if ($answer->save()) {
                $this->redirect(array('question/view', 'id' => $answer->questionId));
            }
        }

        $this->render('application.views.answer.update', array(
            'model' => $answer,
        ));
    }

    /*
     * страница, где мы запрашиваем у пользователя его почту, записываем в вопрос и отправляем письмо со
     * ссылкой активации аккаунта
     */

    public function actionConfirm() {
        $this->layout = "//frontend/smart";

        $qId = (isset($_GET['qId'])) ? (int) $_GET['qId'] : false;
        $sId = (isset($_GET['sId'])) ? $_GET['sId'] : false;

        if (!$qId || !$sId) {
            throw new CHttpException(404, 'Не задан ID вопроса');
        }

        $criteria = new CDbCriteria;
        $criteria->addColumnCondition(array('id' => $qId, 'sessionId' => $sId));
        $question = Question::model()->find($criteria);

        if (!$question) {
            throw new CHttpException(404, 'Не найден вопрос');
        }

        if ($question->email) {
            throw new CHttpException(400, 'У данного вопроса уже задан Email');
        }

        if (isset($_POST['Question']) && isset($_POST['Question']['email'])) {
            $question->email = $_POST['Question']['email'];

            if ($question->createAuthor()) {
                //CustomFuncs::printr($question->attributes);
                if ($question->save()) {
                    if ($question->status == Question::STATUS_CHECK) {
                        //Telegram::send("StoYuristov", "Опубликован новый вопрос...");
                        $this->redirect(array('question/view', 'id' => $question->id));
                    }
                    $this->redirect(array('thankYou'));
                }
            }
        }

        $this->render('confirm', array(
            'question' => $question,
        ));
    }

    public function actionThankYou() {
        $this->layout = '//frontend/smart';
        $this->render('thankYou');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        if ($_SERVER['REQUEST_URI'] != '/q/') {
            $this->redirect(Yii::app()->createUrl('question/index'), true, 301);
        }

        $criteria = new CDbCriteria;
        $criteria->limit = 40;
        $criteria->with = 'answersCount';
        $criteria->addCondition('status IN (' . Question::STATUS_PUBLISHED . ', ' . Question::STATUS_CHECK . ')');
        $criteria->order = 'publishDate DESC';

        $questions = Question::model()->cache(600)->findAll($criteria);

        /*
         * SELECT YEAR(publishDate) year, MONTH(publishDate) month, COUNT(*) counter FROM `100_question` 
          WHERE status IN (2,4)
          GROUP BY year, month
         */
        // Годы и  месяцы, за которые есть вопросы
        $datesArray = array();
        $datesRows = Yii::app()->db->createCommand()
                ->select('YEAR(publishDate) year, MONTH(publishDate) month')
                ->from('{{question}}')
                ->where('status IN (:status1, :status2)', array(':status1' => Question::STATUS_CHECK, ':status2' => Question::STATUS_PUBLISHED))
                ->group('year, month')
                ->order('publishDate DESC')
                ->queryAll();
        foreach ($datesRows as $row) {
            if ($row['year'] && $row['month']) {
                $datesArray[$row['year']][] = $row['month'];
            }
        }

        $this->render('index', array(
            'questions' => $questions,
            'datesArray' => $datesArray,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Question('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Question']))
            $model->attributes = $_GET['Question'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Question the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Question::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Question $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'question-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    // generates RSS 2.0 feed with active trips
    public function actionRss() {
        $criteria = new CDbCriteria;
        $criteria->addInCondition('t.status', array(Question::STATUS_PUBLISHED, Question::STATUS_CHECK));
        $criteria->order = "t.id DESC";
        $criteria->with = array('answersCount');
        $criteria->limit = 200;
        $questions = Question::model()->cache(600)->findAll($criteria);

        Yii::import('ext.feed.*');
        // RSS 2.0 is the default type
        $feed = new EFeed();

        $feed->title = Yii::app()->name;
        $feed->description = 'Вопросы квалифицированным юристам';


        $feed->addChannelTag('language', 'ru-ru');
        $feed->addChannelTag('pubDate', date(DATE_RSS, time()));
        $feed->addChannelTag('link', 'https://100yuristov.com/question/rss');



        foreach ($questions as $question) {
            $item = $feed->createNewItem();


            if ($question->answersCount) {
                $item->title = CHtml::encode($question->title) . ' (' . $question->answersCount . ' ' . CustomFuncs::numForms($question->answersCount, 'ответ', "ответа", "ответов") . ")";
            } else {
                $item->title = CHtml::encode($question->title);
            }

            $item->link = Yii::app()->createUrl('question/view', array('id' => $question->id));
            $item->date = ($question->publishDate) ? date(DATE_RSS, strtotime($question->publishDate)) : date(DATE_RSS, strtotime($question->createDate));

            $item->description = CHtml::encode($question->questionText);

            $feed->addItem($item);
        }
        $feed->generateFeed();
        Yii::app()->end();
    }

    // generates RSS 2.0 feed with active questions with answers
    public function actionRssAnswers() {

        $questions = Yii::app()->db->cache(600)->createCommand()
                ->select("q.id, q.title, q.publishDate, q.createDate, q.questionText, COUNT(*) answersCount")
                ->from("{{question}} q")
                ->leftJoin("{{answer}} a", "a.questionId=q.id")
                ->group("q.id")
                ->where("q.status IN(:status1, :status2) AND a.id IS NOT NULL", array(":status1" => Question::STATUS_CHECK, ":status2" => Question::STATUS_PUBLISHED))
                ->order("q.publishDate DESC, q.id DESC")
                ->limit(200)
                ->queryAll();
        //CustomFuncs::printr($questions);exit;


        Yii::import('ext.feed.*');
        // RSS 2.0 is the default type
        $feed = new EFeed();

        $feed->title = Yii::app()->name;
        $feed->description = 'Вопросы квалифицированным юристам';


        $feed->addChannelTag('language', 'ru-ru');
        $feed->addChannelTag('pubDate', date(DATE_RSS, time()));
        $feed->addChannelTag('link', 'https://100yuristov.com/question/rssAnswers');



        foreach ($questions as $question) {
            $item = $feed->createNewItem();


            if ($question['answersCount']) {
                $item->title = CHtml::encode($question['title']) . ' (' . $question['answersCount'] . ' ' . CustomFuncs::numForms($question['answersCount'], 'ответ', "ответа", "ответов") . ")";
            } else {
                $item->title = CHtml::encode($question['title']);
            }

            $item->link = Yii::app()->createUrl('question/view', array('id' => $question['id']));
            $item->date = ($question['publishDate']) ? date(DATE_RSS, strtotime($question['publishDate'])) : date(DATE_RSS, strtotime($question['createDate']));

            $item->description = CHtml::encode($question['questionText']);

            $feed->addItem($item);
        }
        $feed->generateFeed();
        Yii::app()->end();
    }

    public function actionSearch() {

        // модель для формы поиска по вопросам
        $searchModel = new QuestionSearch();

        // лимит на количество найденных вопросов
        $searchModel->limit = 100;
//            $searchModel->today = true;
//            $searchModel->townId = 598;
//            $searchModel->noAnswers = true;


        $searchModel->attributes = $_GET['QuestionSearch'];

        if ($searchModel->townId) {
            $searchModel->townName = Town::getName($searchModel->townId);
        }

//            CustomFuncs::printr($searchModel);exit;
        $questions = $searchModel->search();
        $questionDataProvider = new CArrayDataProvider($questions, array(
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));




        $this->render('search', array(
            'searchModel' => $searchModel,
            'dataProvider' => $questionDataProvider,
        ));
    }

    public function actionCall() {
        $this->layout = "//frontend/smart";
        $lead = new Lead100();
        $lead->setScenario('create');
        
        $allDirectionsHierarchy = QuestionCategory::getDirections(true, true);
        $allDirections = QuestionCategory::getDirectionsFlatList($allDirectionsHierarchy);

        if (isset($_POST['Lead100'])) {
            $lead->attributes = $_POST['Lead100'];
            $lead->phone = Question::normalizePhone($lead->phone);
            $lead->sourceId = 3;
            $lead->type = Lead100::TYPE_CALL;

            $existingLeads = Yii::app()->db->createCommand()
                    ->select('phone')
                    ->from('{{lead100}}')
                    ->where('question_date>NOW()- INTERVAL 12 HOUR')
                    ->queryAll();
            // массив, в котором будут храниться телефоны лидов, которые добавлены в базу за последний день, чтобы не добавить одного лида несколько раз
            $existingLeadsPhones = array();

            foreach ($existingLeads as $existingLead) {
                $existingLeadsPhones[] = Question::normalizePhone($existingLead['phone']);
            }

            if (in_array($lead->phone, $existingLeadsPhones)) {
                $lead->addError('phone', "Похоже, вы пытаетесь задать свой вопрос повторно");
            } else {
                if ($lead->validate()) {
                    $lead->question = CHtml::encode('Нужна консультация юриста. Перезвоните мне. ' . $lead->question);

                    if ($lead->save()) {
                        // сохраним категории, к которым относится вопрос, если категория указана
                        if (isset($_POST['Lead100']['categories']) && $_POST['Lead100']['categories'] != 0) {
                            
                            $lead2category = new Lead2Category;
                            $lead2category->leadId = $lead->id;
                            $leadCategory = (int)$_POST['Lead100']['categories'];
                            $lead2category->cId = $leadCategory;
                            
                            if ($lead2category->save()) {
                                // проверим, не является ли указанная категория дочерней
                                // если является, найдем ее родителя и запишем в категории вопроса
                                foreach ($allDirectionsHierarchy as $parentId => $parentCategory) {
                                    if (!$parentCategory['children'])
                                        continue;

                                    foreach ($parentCategory['children'] as $childId => $childCategory) {
                                        if ($childId == $leadCategory) {
                                            $lead2category = new Lead2Category();
                                            $lead2category->leadId = $lead->id;
                                            $lead2category->cId = $parentId;
                                            $lead2category->save();
                                            break;
                                        }
                                    }
                                }
                            }

                        }
                        $this->redirect(array('weCallYou'));
                    }
                }
            }
        }

        $townsArray = Town::getTownsIdsNames();

        $this->render('call', array(
            'model'         => $lead,
            'townsArray'    => $townsArray,
            'allDirections' => $allDirections,
        ));
    }
    
    public function actionCallBack()
    {
        $this->layout = "//frontend/smart";
        
        $lead = new Lead100();
        $question = new Question;
        
        if (isset($_POST['Lead100'])) {
            $lead->attributes = $_POST['Lead100'];
            $question->townId = $lead->townId;
            $currentTownId = $lead->townId;
            
            // загрузили данные о лиде (город), теперь проверим, продажный ли регион этого города
            
            // Определим, для каких регионов и городов у нас есть рекламные кампании
            $payedRegions = array();
            $payedTowns = array();

            $payedTownsRegions = Campaign::getPayedTownsRegions();

            $payedRegions = $payedTownsRegions['regions'];
            $payedTowns = $payedTownsRegions['towns'];

            /*
             * показываем виджет только если пользователь находится в одном из продажных городов ИЛИ регионов
             */
            if(array_key_exists($currentTownId, $payedTowns) || array_key_exists($currentTown->regionId, $payedRegions)) {
                $isRegionPayed = true;
            } else {
                $isRegionPayed = false;
            }
            
            $this->render('callBack', array(
                'lead'          =>  $lead,
                'question'      =>  $question,
                'isRegionPayed' =>  $isRegionPayed,
            ));
        }
    }

    public function actionWeCallYou() {
        $this->layout = "//frontend/smart";
        $this->render('weCallYou');
    }

    public function actionDocs() {
        $this->layout = "//frontend/smart";

        $order = new Order();
        $order->setScenario('create');
        $author = new User();
        $author->setScenario('register');
        $docType = null;
        
        if(!Yii::app()->user->isGuest) {
            $currentUser = User::model()->findByPk(Yii::app()->user->id);
            $author->attributes = $currentUser->attributes;
        }
        
        if(isset($_GET['juristId']) && (int)$_GET['juristId']>0) {
            $juristId = (int)$_GET['juristId'];
            if(User::model()->findByAttributes(['role' => User::ROLE_JURIST, 'active100' => 1, 'id' => $juristId])) {
                $order->juristId = $juristId;
            }            
        }

        if (isset($_POST['Order'])) {
            $order->attributes = $_POST['Order'];
            
            // найдем информацию по типу заказываемого документа
            if($order->itemType) {
                $docType = DocType::model()->findByPk($order->itemType);
            }
            
            if(isset($_POST['User'])) {
                $author->attributes = $_POST['User'];
            }

            //if ($order->validate() && $author->validate()) {
                
                if(Yii::app()->user->isGuest) {
                    $order->status = Order::STATUS_NEW;
                    // для нового пользователя сгенерируем его секретный код и пароль
                    $author->confirm_code = md5($author->email . mt_rand(100000, 999999));
                    $author->password = $author->password2 = User::generatePassword(10);
                    $author->role = User::ROLE_CLIENT;
                    
                    // перед сохранением пользователя проверим заказ
                    if($order->validate(['description', 'itemType']) && $author->save()) {
                        // после сохранения пользователя отправим ему ссылку на активацию
                        $author->sendConfirmation();
                        $order->userId = $author->id;
                    }
                } else {
                    $order->status = Order::STATUS_CONFIRMED;
                    $order->userId = Yii::app()->user->id;
                }

                // если клиент указал конкретного юриста, ставим статус заявки Выбран юрист
                if($order->jurist) {
                    $order->status = Order::STATUS_JURIST_SELECTED;
                }
                
                if ($order->save()) {
                    if($order->juristId) {
                        $order->sendJuristNotification();
                    }
                    $this->redirect(array('docsRequested'));
                }
            //}
        }

        $townsArray = Town::getTownsIdsNames();
        $docTypes = DocType::model()->findAll();
        $docTypesArray = [];
        foreach($docTypes as $type) {
            $docTypesArray[$type->class][] = $type;
        }

        $this->render('docs', array(
            'order'         =>  $order,
            'author'        =>  $author,
            'townsArray'    =>  $townsArray,
            'docTypesArray' =>  $docTypesArray,
            'docType'       =>  $docType,
        ));
    }

    public function actionDocsRequested() {
        $this->layout = "//frontend/smart";
        $this->render('docsRequested');
    }

    public function actionServices() {
        $this->layout = "//frontend/smart";
        $lead = new Lead100();
        $lead->setScenario('create');
        

        if (isset($_POST['Lead100'])) {
            $lead->attributes = $_POST['Lead100'];
            $lead->phone = preg_replace('/([^0-9])/i', '', $lead->phone);
            $lead->sourceId = 3;
            $lead->type = Lead100::TYPE_SERVICES;

            if ($lead->validate()) {
                $lead->question = CHtml::encode('Нужны услуги юриста. ' . $lead->question);

                if ($lead->save()) {
                    $this->redirect(array('getServices'));
                }
            }
        }

        $townsArray = Town::getTownsIdsNames();

        $this->render('services', array(
            'model' => $lead,
            'townsArray' => $townsArray,
        ));
    }

    public function actionGetServices() {
        $this->layout = "//frontend/smart";
        $this->render('getServices');
    }

    // изменения статуса вопроса на платный
    public function actionUpgrade($id) {
        $question = Question::model()->findByPk($id);

        if (!$question) {
            throw new CHttpException(404, 'Вопрос не найден');
        }

        $level = (isset($_GET['level']) && (int) $_GET['level'] > 0) ? (int) $_GET['level'] : Question::LEVEL_1;

        $questionPrice = Question::getPriceByLevel($level);
        $question->price = $questionPrice;

        $this->render('upgrade', array(
            'question' => $question,
        ));
    }

    /**
     *  платеж успешно совершен
     */
    public function actionPaymentSuccess() {
        $params = $_GET;


        $this->render('paymentSuccess', array('params' => $params));
    }

    // платеж не успешно
    public function actionPaymentFail() {
        //https://100yuristov.com/question/paymentFail/test/1/?orderSumAmount=99.00&cdd_exp_date=1221&shopArticleId=367734&paymentPayerCode=4100322062290&cdd_rrn=&external_id=deposit&paymentType=AC&requestDatetime=2016-10-06T17%3A39%3A22.418%2B03%3A00&depositNumber=sO8G8EwrcotOG1AgYAadKefc5cQZ.001f.201610&cps_user_country_code=PL&orderCreatedDatetime=2016-10-06T17%3A39%3A21.921%2B03%3A00&sk=y0ef7319b7a2ed83de96f44ec0cd4c83c&action=PaymentFail&shopId=73868&scid=542085&rebillingOn=false&orderSumBankPaycash=1003&cps_region_id=216&orderSumCurrencyPaycash=10643&merchant_order_id=21516_061016173905_00000_73868&unilabel=1f8875c8-0009-5000-8000-000015ab36bd&cdd_pan_mask=444444%7C4448&customerNumber=21516&yandexPaymentId=2570060865738&invoiceId=2000000925475
        $params = $_GET;
        $this->render('paymentFail', array('params' => $params));
    }

    // запрос от яндекса на проверку платежа
    public function actionPaymentCheck() {

        $yaKassa = new YandexKassa($_POST);

        $paymentLog = fopen($_SERVER['DOCUMENT_ROOT'] . YandexKassa::PAYMENT_LOG_FILE, 'w+');

        foreach ($_POST as $k => $v) {
            fwrite($paymentLog, $k . '=>' . $v . '; ');
        }

        if (YandexKassa::checkMd5($_POST)) {
            fwrite($paymentLog, "MD5 correct!");
            $yaKassa->formResponse(0, 'OK');
            //$yaKassa->formResponse(1,'Error'); // just for test
        } else {
            fwrite($paymentLog, "MD5 incorrect!");
            $yaKassa->formResponse(1, 'Ошибка авторизации');
        }
    }

    // запроса от яндекса о платеже или отказе
    public function actionPaymentAviso() {
        $yaKassa = new YandexKassa($_POST);

        $paymentLog = fopen($_SERVER['DOCUMENT_ROOT'] . YandexKassa::PAYMENT_LOG_FILE, 'w+');

        foreach ($_POST as $k => $v) {
            fwrite($paymentLog, $k . '=>' . $v . '; ');
        }

        if (YandexKassa::checkMd5($_POST)) {
            fwrite($paymentLog, "MD5 correct!");
            if ($yaKassa->payQuestion()) {
                $yaKassa->formResponse(0, 'OK', 'paymentAviso');
            } else {
                $yaKassa->formResponse(1, 'Error', 'paymentAviso');
            }
        } else {
            fwrite($paymentLog, "MD5 incorrect!");
            $yaKassa->formResponse(1, 'Error', 'paymentAviso');
        }
    }

    /**
     * прием лида по POST запросу
     */
    public function actionSendLead() {
        // отключаем вывод профилирования на странице
        //Yii::app()->log->setRoutes(array('CProfileLogRoute'=>array('enabled'=>false)));


        if (!isset($_POST)) {
            echo json_encode(array('code' => 400, 'message' => 'No input data'));
            exit;
        }
        $model = new Lead100;
        //$leadAppId = 'yurCrm';
        /*
         * захардкодим возможные приложения для поставки лидов, потом будем хранить их в базе
         * appId => [
         *      'secretKey' => 'xxxxx',
         *      'sourceId'  =>  N,
         * ]
         */
        $leadApps = array(
            'yurCrm' => array(
                'secretKey' => 'Let me speak from my heart',
                'sourceId' => 3,
            ),
            'yurCrmRegions' => array(
                'secretKey' => 'Euro integration',
                'sourceId' => 1,
            ),
        );

        // проверим параметр appId, есть ли он в списке известных приложений
        if (!array_key_exists($_POST['appId'], $leadApps)) {
            echo json_encode(array('code' => 400, 'message' => 'Unknown sender. Check App ID parameter'));
            exit;
        }

        $activeApp = $leadApps[$_POST['appId']];

        $model->attributes = $_POST;
        $model->sourceId = $activeApp['sourceId'];
        $model->type = Lead100::TYPE_INCOMING_CALL;
        $model->phone = Question::normalizePhone($model->phone);

        $appSecret = $activeApp['secretKey'];
        // сформируем подпись на основе принятых данных
        $signature = md5($model->name . $model->phone . $model->email . $model->question . $model->townId . $_POST['appId'] . $appSecret);

        // проверим подпись
        if ($signature !== $_POST['signature']) {
            echo json_encode(array('code' => 403, 'message' => 'Signature wrong'));
            exit;
        }


//            echo json_encode($model->name);exit;

        if ($model->findDublicates()) {
            die(json_encode(array('code' => 400, 'message' => 'Dublicates found')));
            exit;
        }

        if ($model->save()) {
            echo json_encode(array('code' => 200, 'message' => 'OK'));
//                echo json_encode($model->id);
            exit;
        } else {
            echo json_encode(array('code' => 500, 'message' => 'Lead not saved.', 'errors' => $model->errors));
            exit;
        }
    }

    public function actionArchive($date) {
        $dateParts = explode('-', $date);
        $year = $dateParts[0];
        $month = $dateParts[1];

        $questionsDataProvider = new CActiveDataProvider('Question', array(
            'criteria' => array(
                'condition' => 'YEAR(publishDate)=' . $year . ' AND MONTH(publishDate)=' . $month . ' AND status IN (' . Question::STATUS_CHECK . ', ' . Question::STATUS_PUBLISHED . ')',
                'order' => 'publishDate DESC',
                'with' => 'answersCount',
            ),
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));

        // месяцы, за которые есть вопросы
        $datesArray = array();
        $datesRows = Yii::app()->db->createCommand()
                ->select('MONTH(publishDate) month')
                ->from('{{question}}')
                ->where('YEAR(publishDate) = :year AND status IN (:status1, :status2)', array(':status1' => Question::STATUS_CHECK, ':status2' => Question::STATUS_PUBLISHED, ':year' => $year))
                ->group('month')
                ->queryAll();
        foreach ($datesRows as $row) {
            if ($row['month']) {
                $datesArray[] = $row['month'];
            }
        }

        $this->render('archive', array(
            'dataProvider' => $questionsDataProvider,
            'year' => $year,
            'month' => $month,
            'datesArray' => $datesArray,
        ));
    }
    
    /**
     * Отметка комментариев к ответам вопроса как прочитанные
     */
    public function actionCheckCommentsAsRead()
    {
        $request = Yii::app()->request;
        if(!$request->isAjaxRequest) {
            throw new CHttpException(400, 'Запрос должен быть в формате AJAX');
        }
        
        $userId = Yii::app()->user->id;
        $questionId = (int)$_POST['id'];
        
        $question = Question::model()->findByPk($questionId);
        
        if(!$question) {
            throw new CHttpException(404, 'Вопрос не найден');
        }
        
        if ($question->checkCommentsAsRead($userId)) {
            echo json_encode(array('code' => 200, 'message' => '', 'id' => $question->id));
            exit;
        } else {
            echo json_encode(array('code' => 500, 'message' => 'Не удалось отметить комментарии прочитанными', 'id' => $question->id));
            exit;
        }
        
        
    }

}
