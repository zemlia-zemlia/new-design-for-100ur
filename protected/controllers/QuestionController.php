<?php

use App\helpers\NumbersHelper;
use App\helpers\PhoneHelper;
use App\models\Answer;
use App\models\Campaign;
use App\models\Comment;
use App\models\DocType;
use App\models\Lead;
use App\models\Lead2Category;
use App\models\Order;
use App\models\Question;
use App\models\QuestionCategory;
use App\models\QuestionSearch;
use App\models\Town;
use App\models\User;
use App\repositories\AnswerRepository;
use App\repositories\QuestionRepository;
use App\repositories\UserRepository;
use App\services\AnswerService;
use App\services\CommentService;
use App\services\LeadService;
use App\services\QuestionRSSFeedService;
use App\services\QuestionService;

class QuestionController extends Controller
{
    public $layout = '//frontend/question';

    /** @var AnswerRepository */
    protected $answerRepository;

    /** @var QuestionRepository */
    protected $questionRepository;

    /** @var UserRepository */
    protected $userRepository;

    /** @var QuestionService */
    protected $questionService;

    /** @var AnswerService */
    protected $answerService;

    /** @var CommentService */
    protected $commentService;

    /** @var LeadService */
    protected $leadService;

    /** @var QuestionRSSFeedService */
    protected $questionRSSService;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $diContainer = Yii::app()->container->container;

        $this->answerRepository = $diContainer->get(AnswerRepository::class);
        $this->userRepository = $diContainer->get(UserRepository::class);
        $this->questionRepository = $diContainer->get(QuestionRepository::class);

        $this->answerService = $diContainer->get(AnswerService::class);
        $this->commentService = $diContainer->get(CommentService::class);
        $this->leadService = $diContainer->get(LeadService::class);
        $this->questionService = $diContainer->get(QuestionService::class);
        $this->questionRSSService = $diContainer->get(QuestionRSSFeedService::class);

        return parent::init();
    }

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
            ['allow', // allow all users
                'actions' => ['index', 'archive', 'view', 'create', 'thankYou', 'rss', 'rssAnswers', 'call', 'callBack', 'weCallYou', 'docsRequested', 'docs', 'getServices', 'services', 'upgrade', 'paymentSuccess', 'paymentFail', 'paymentCheck', 'paymentAviso', 'confirm', 'sendLead'],
                'users' => ['*'],
            ],
            ['allow', // allow authenticated user to perform 'search'
                'actions' => ['search', 'updateAnswer', 'checkCommentsAsRead', 'my'],
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
     *
     * @throws CHttpException
     */
    public function actionView(int $id)
    {
        $request = Yii::app()->request;

        $commentModel = new Comment();
        $answerModel = new Answer();

        // модель для формы вопроса
        $newQuestionModel = new Question();

        // редирект для страниц с пагинацией в адресе
        if ($request->getParam('Question_page')) {
            $this->redirect(['view', 'id' => $id], true, 301);
        }
        /** @var Question $model */
        $model = Question::model()->with('categories')->findByPk($id);
        if (!$model) {
            throw new CHttpException(404, 'Вопрос не найден');
        }

        // проверим, правильный ли статус у вопроса
        if (!in_array($model->status, [Question::STATUS_CHECK, Question::STATUS_PUBLISHED])) {
            throw new CHttpException(404, 'Вопрос не найден');
        }

        $justPublished = !is_null($request->getParam('justPublished'));

        if ($request->getParam('App_models_Answer')) {
            $answerModel = $this->answerService->createAnswer($request->getParam('App_models_Answer'), $model, Yii::app()->user);

            if (empty($answerModel->getErrors())) {
                $this->redirect(['/question/view', 'id' => $model->id]);
            }
        }

        if ($request->getParam('App_models_Comment')) {
            $commentModel = $this->commentService->create($request->getParam('App_models_Comment'), Yii::app()->user);

            if (empty($commentModel->getErrors())) {
                $this->redirect(['/question/view', 'id' => $model->id]);
            }
        }

        // выборка ответов для текущего вопроса
        $answersDataProvider = $this->answerRepository->getAnswersDataProviderByQuestion($model);

        // массив с id авторов ответов к текущему вопросу
        // запишем в него id авторов ответов, чтобы не дать юристу ответить на данный вопрос дважды
        $answersAuthorsIds = [];

        foreach ($answersDataProvider->data as $answer) {
            $answersAuthorsIds[] = $answer->authorId;
        }

        if (User::ROLE_JURIST == Yii::app()->user->role) {
            // найдем последний запрос на смену статуса
            $lastRequest = $this->userRepository
                ->getLastChangeStatusRequestAsArray(Yii::app()->user->getModel());

            $nextQuestionId = $model->getNextQuestionIdForYurist(Yii::app()->user->id);
            $model->checkCommentsAsRead(Yii::app()->user->id);
        } else {
            $lastRequest = null;
            $nextQuestionId = null;
        }

        $this->render('view', [
            'model' => $model,
            'answersDataProvider' => $answersDataProvider,
            'answersAuthors' => $answersAuthorsIds,
            'newQuestionModel' => $newQuestionModel,
            'answerModel' => $answerModel,
            'justPublished' => $justPublished,
            'commentModel' => $commentModel,
            'lastRequest' => $lastRequest,
            'nextQuestionId' => $nextQuestionId,
        ]);
    }

    /**
     * Создание вопроса.
     */
    public function actionCreate()
    {
        $this->layout = '//frontend/smart';

        $request = Yii::app()->request;
        $question = new Question();
        $question->setScenario('create');

        if (!Yii::app()->user->isGuest) {
            /** @var User $user */
            $user = User::model()->findByPk(Yii::app()->user->id);
            $myRecentQuestionsCount = $this->userRepository->getRecentQuestionCount($user, 1);
            if ($myRecentQuestionsCount > 0) {
                return $this->render('questionsLimit');
            }
        }

        // параметр, определяющий, будет ли в форме блок выбора цены (форма платного вопроса)
        $pay = !is_null($request->getParam('pay'));

        // Если перешли из виджета задай вопрос юристу
        if ($request->getParam('komm')) {
            $question->questionText = CHtml::encode($request->getParam('komm'));
        }

        $allDirectionsHierarchy = QuestionCategory::getDirections(true, true);
        $allDirections = QuestionCategory::getDirectionsFlatList($allDirectionsHierarchy);

        if ($request->getParam('App_models_Question')) {
            $question = $this->questionService->createQuestion($request->getParam('App_models_Question'), $allDirectionsHierarchy);

            if (empty($question->getErrors())) {
                if (Yii::app()->user->email && 1 == Yii::app()->user->active100) {
                    $this->redirect(['question/view', 'id' => $question->id]);
                }
                $this->redirect(['confirm', 'qId' => $question->id, 'sId' => $question->sessionId]);
            }
        }

        $question = $this->questionService->fillQuestionAttributesFromUserParams($question, Yii::app()->user->getModel());

        $this->render('create', [
            'model' => $question,
            'allDirections' => $allDirections,
            'pay' => $pay,
        ]);
    }

    /**
     * Страница редактирования ответа.
     *
     * @param int $id
     *
     * @throws CHttpException
     */
    public function actionUpdateAnswer(int $id)
    {
        $answer = Answer::model()->findByPk($id);
        if (!$answer) {
            throw new CHttpException(404, 'Ответ не найден');
        }
        if (!(User::ROLE_JURIST == Yii::app()->user->role && $answer->authorId == Yii::app()->user->id && time() - strtotime($answer->datetime) < Answer::EDIT_TIMEOUT)) {
            throw new CHttpException(403, 'Вы не можете редактировать этот ответ');
        }

        if (isset($_POST['App_models_Answer'])) {
            $answer->attributes = $_POST['App_models_Answer'];

            if ($answer->save()) {
                $this->redirect(['question/view', 'id' => $answer->questionId]);
            }
        }

        $this->render('application.views.answer.update', [
            'model' => $answer,
        ]);
    }

    /**
     * страница, где мы запрашиваем у пользователя его почту, записываем в вопрос и отправляем письмо со
     * ссылкой активации аккаунта.
     *
     * @throws CHttpException
     */
    public function actionConfirm()
    {
        $this->layout = '//frontend/smart';

        /** @var CHttpRequest $request */
        $request = Yii::app()->request;

        $qId = (int)$request->getParam('qId');
        $sId = $request->getParam('sId');

        if (!$qId || !$sId) {
            throw new CHttpException(404, 'Не задан ID вопроса');
        }
        $postedParams = $request->getParam('App_models_Question');

        $question = $this->questionService->confirm($qId, $sId, $postedParams);

        if (!empty($postedParams) && empty($question->getErrors())) {
            if (Question::STATUS_CHECK == $question->status) {
                $this->redirect(['question/view', 'id' => $question->id]);
            }
            $this->redirect(['thankYou']);
        }

        $this->render('confirm', [
            'question' => $question,
        ]);
    }

    /**
     * Выводит страницу с благодарностью за вопрос
     */
    public function actionThankYou()
    {
        $this->layout = '//frontend/smart';
        $this->render('thankYou');
    }

    /**
     * Страница последних вопросов.
     */
    public function actionIndex()
    {
        if ('/q/' != $_SERVER['REQUEST_URI']) {
            $this->redirect(Yii::app()->createUrl('question/index'), true, 301);
        }

        // Массив последних опубликованных вопросов
        $questions = $this->questionRepository->findRecentPublishedQuestions();

        // Годы и  месяцы, за которые есть вопросы
        $datesArray = $this->questionRepository->getYearsAndMonthsWithQuestions();

        $this->render('index', [
            'questions' => $questions,
            'datesArray' => $datesArray,
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param int $id the ID of the model to be loaded
     *
     * @return Question the loaded model
     *
     * @throws CHttpException
     */
    public function loadModel($id): Question
    {
        $model = Question::model()->findByPk($id);
        if (null === $model) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     *
     * @param Question $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && 'question-form' === $_POST['ajax']) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Генерирует RSS 2.0 фид с опубликованными вопросами
     */
    public function actionRss()
    {
        $questions = $this->questionRepository
            ->getPublishedQuestionsWithAnswersCountAsArray(200, 600);

        $feed = $this->questionRSSService->createFeed($questions, [
            'link' => Yii::app()->createUrl('/question/rss')
        ]);

        $feed->generateFeed();
        Yii::app()->end();
    }

    /**
     * Генерирует RSS 2.0 фид с опубликованными вопросами, у которых есть ответы
     */
    public function actionRssAnswers()
    {
        $questions = $this->questionRepository->getPublishedQuestionsWithAnswersAsArray(200, 600);

        $feed = $this->questionRSSService->createFeed($questions, [
            'link' => Yii::app()->createUrl('/question/rssAnswers')
        ]);

        $feed->generateFeed();
        Yii::app()->end();
    }

    /**
     * Поиск вопросов.
     */
    public function actionSearch()
    {
        // модель для формы поиска по вопросам
        $searchModel = new QuestionSearch();

        // лимит на количество найденных вопросов
        $searchModel->limit = 100;

        $searchModel->attributes = $_GET['App_models_QuestionSearch'];

        if ($searchModel->townId) {
            $searchModel->townName = Town::getName($searchModel->townId);
        }

        $questions = $searchModel->search();
        $questionDataProvider = new CArrayDataProvider($questions, [
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->render('search', [
            'searchModel' => $searchModel,
            'dataProvider' => $questionDataProvider,
        ]);
    }

    /**
     * Запрос звонка.
     */
    public function actionCall()
    {
        $this->layout = '//frontend/smart';
        $lead = new Lead();
        $lead->setScenario('createCall');

        if (!Yii::app()->user->isGuest) {
            $user = User::model()->findByPk(Yii::app()->user->id);
            $myRecentQuestionsCount = $user->getRecentQuestionCount(1);
            if ($myRecentQuestionsCount > 0) {
                return $this->render('questionsLimit');
            }
        }

        $allDirectionsHierarchy = QuestionCategory::getDirections(true, true);
        $allDirections = QuestionCategory::getDirectionsFlatList($allDirectionsHierarchy);

        if (isset($_POST['App_models_Lead'])) {
            $lead->attributes = $_POST['App_models_Lead'];
            $lead->phone = PhoneHelper::normalizePhone($lead->phone);
            $lead->sourceId = 3;
            $lead->type = Lead::TYPE_CALL;

            $duplicates = $lead->findDublicates(86400);
            if ($duplicates) {
                $lead->leadStatus = Lead::LEAD_STATUS_DUPLICATE;
            }

            if ($lead->validate()) {
                $lead->question = CHtml::encode('Нужна консультация юриста. Перезвоните мне. ' . $lead->question);

                if ($lead->save()) {
                    // сохраним категории, к которым относится вопрос, если категория указана
                    if (isset($_POST['App_models_Lead']['categories']) && 0 != $_POST['App_models_Lead']['categories']) {
                        $lead2category = new Lead2Category();
                        $lead2category->leadId = $lead->id;
                        $leadCategory = (int)$_POST['App_models_Lead']['categories'];
                        $lead2category->cId = $leadCategory;

                        if ($lead2category->save()) {
                            // проверим, не является ли указанная категория дочерней
                            // если является, найдем ее родителя и запишем в категории вопроса
                            foreach ($allDirectionsHierarchy as $parentId => $parentCategory) {
                                if (!$parentCategory['children']) {
                                    continue;
                                }

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
                    $this->redirect(['weCallYou']);
                }
            }
        }

        if (!$lead->name && Yii::app()->user->name) {
            $lead->name = Yii::app()->user->name;
        }

        if (!$lead->phone && Yii::app()->user->phone) {
            $lead->phone = Yii::app()->user->phone;
        }

        if (!$lead->townId && Yii::app()->user->townId) {
            $lead->townId = Yii::app()->user->townId;
        }

        $townsArray = Town::getTownsIdsNames();

        $this->render('call', [
            'model' => $lead,
            'townsArray' => $townsArray,
            'allDirections' => $allDirections,
        ]);
    }

    /**
     * @deprecated
     */
    public function actionCallBack()
    {
        $this->layout = '//frontend/smart';

        $lead = new Lead();
        $question = new Question();

        if (isset($_POST['App_models_Lead'])) {
            $lead->attributes = $_POST['App_models_Lead'];
            $question->townId = $lead->townId;
            $currentTownId = $lead->townId;

            // загрузили данные о лиде (город), теперь проверим, продажный ли регион этого города

            // Определим, для каких регионов и городов у нас есть рекламные кампании
            $payedRegions = [];
            $payedTowns = [];

            $payedTownsRegions = Campaign::getPayedTownsRegions();

            $payedRegions = $payedTownsRegions['regions'];
            $payedTowns = $payedTownsRegions['towns'];

            /*
             * показываем виджет только если пользователь находится в одном из продажных городов ИЛИ регионов
             */
            if (array_key_exists($currentTownId, $payedTowns) || array_key_exists($currentTown->regionId, $payedRegions)) {
                $isRegionPayed = true;
            } else {
                $isRegionPayed = false;
            }

            $this->render('callBack', [
                'lead' => $lead,
                'question' => $question,
                'isRegionPayed' => $isRegionPayed,
            ]);
        }
    }

    public function actionWeCallYou()
    {
        $this->layout = '//frontend/smart';
        $this->render('weCallYou');
    }

    public function actionDocs()
    {
        $this->layout = '//frontend/smart';

        $order = new Order();
        $order->setScenario('create');
        $author = new User();
        $author->setScenario('register');
        $docType = null;

        if (!Yii::app()->user->isGuest) {
            $currentUser = User::model()->findByPk(Yii::app()->user->id);
            $author->attributes = $currentUser->attributes;
        }

        if (isset($_GET['juristId']) && (int)$_GET['juristId'] > 0) {
            $juristId = (int)$_GET['juristId'];
            if (User::model()->findByAttributes(['role' => User::ROLE_JURIST, 'active100' => 1, 'id' => $juristId])) {
                $order->juristId = $juristId;
            }
        }

        if (isset($_POST['App_models_Order'])) {
            $order->attributes = $_POST['App_models_Order'];

            // найдем информацию по типу заказываемого документа
            if ($order->itemType) {
                $docType = DocType::model()->findByPk($order->itemType);
            }

            if (isset($_POST['App_models_User'])) {
                $author->attributes = $_POST['App_models_User'];
            }

            //if ($order->validate() && $author->validate()) {

            if (Yii::app()->user->isGuest) {
                $order->status = Order::STATUS_NEW;
                // для нового пользователя сгенерируем его секретный код и пароль
                $author->confirm_code = md5($author->email . mt_rand(100000, 999999));
                $author->password = $author->password2 = User::generatePassword(10);
                $author->role = User::ROLE_CLIENT;

                // перед сохранением пользователя проверим заказ
                if ($order->validate(['description', 'itemType']) && $author->save()) {
                    // после сохранения пользователя отправим ему ссылку на активацию
                    $author->sendConfirmation();
                    $order->userId = $author->id;
                }
            } else {
                $order->status = Order::STATUS_CONFIRMED;
                $order->userId = Yii::app()->user->id;
            }

            // если клиент указал конкретного юриста, ставим статус заявки Выбран юрист
            if ($order->jurist) {
                $order->status = Order::STATUS_JURIST_SELECTED;
            }

            if ($order->save()) {
                if ($order->juristId) {
                    $order->sendJuristNotification();
                }
                $this->redirect(['docsRequested']);
            }
            //}
        }

        $townsArray = Town::getTownsIdsNames();
        $docTypes = DocType::model()->findAll();
        $docTypesArray = [];
        foreach ($docTypes as $type) {
            $docTypesArray[$type->class][] = $type;
        }

        $this->render('docs', [
            'order' => $order,
            'author' => $author,
            'townsArray' => $townsArray,
            'docTypesArray' => $docTypesArray,
            'docType' => $docType,
        ]);
    }

    public function actionDocsRequested()
    {
        $this->layout = '//frontend/smart';
        $this->render('docsRequested');
    }

    public function actionServices()
    {
        $this->layout = '//frontend/smart';
        $lead = new Lead();
        $lead->setScenario('create');

        if (isset($_POST['App_models_Lead'])) {
            $lead->attributes = $_POST['App_models_Lead'];
            $lead->phone = preg_replace('/([^0-9])/i', '', $lead->phone);
            $lead->sourceId = 3;
            $lead->type = Lead::TYPE_SERVICES;

            if ($lead->validate()) {
                $lead->question = CHtml::encode('Нужны услуги юриста. ' . $lead->question);

                if ($lead->save()) {
                    $this->redirect(['getServices']);
                }
            }
        }

        $townsArray = Town::getTownsIdsNames();

        $this->render('services', [
            'model' => $lead,
            'townsArray' => $townsArray,
        ]);
    }

    public function actionGetServices()
    {
        $this->layout = '//frontend/smart';
        $this->render('getServices');
    }

    // изменения статуса вопроса на платный
    public function actionUpgrade($id)
    {
        $question = Question::model()->findByPk($id);

        if (!$question) {
            throw new CHttpException(404, 'Вопрос не найден');
        }

        $level = (isset($_GET['level']) && (int)$_GET['level'] > 0) ? (int)$_GET['level'] : Question::LEVEL_1;

        $questionPrice = Question::getPriceByLevel($level);
        $question->price = $questionPrice;

        $this->render('upgrade', [
            'question' => $question,
        ]);
    }

    /**
     *  платеж успешно совершен.
     */
    public function actionPaymentSuccess()
    {
        $params = $_GET;

        $this->render('paymentSuccess', ['params' => $params]);
    }

    // платеж не успешно
    public function actionPaymentFail()
    {
        //https://100yuristov.com/question/paymentFail/test/1/?orderSumAmount=99.00&cdd_exp_date=1221&shopArticleId=367734&paymentPayerCode=4100322062290&cdd_rrn=&external_id=deposit&paymentType=AC&requestDatetime=2016-10-06T17%3A39%3A22.418%2B03%3A00&depositNumber=sO8G8EwrcotOG1AgYAadKefc5cQZ.001f.201610&cps_user_country_code=PL&orderCreatedDatetime=2016-10-06T17%3A39%3A21.921%2B03%3A00&sk=y0ef7319b7a2ed83de96f44ec0cd4c83c&action=PaymentFail&shopId=73868&scid=542085&rebillingOn=false&orderSumBankPaycash=1003&cps_region_id=216&orderSumCurrencyPaycash=10643&merchant_order_id=21516_061016173905_00000_73868&unilabel=1f8875c8-0009-5000-8000-000015ab36bd&cdd_pan_mask=444444%7C4448&customerNumber=21516&yandexPaymentId=2570060865738&invoiceId=2000000925475
        $params = $_GET;
        $this->render('paymentFail', ['params' => $params]);
    }

    // запрос от яндекса на проверку платежа
    public function actionPaymentCheck()
    {
        $yaKassa = new YandexKassa($_POST);

        $paymentLog = fopen($_SERVER['DOCUMENT_ROOT'] . YandexKassa::PAYMENT_LOG_FILE, 'w+');

        foreach ($_POST as $k => $v) {
            fwrite($paymentLog, $k . '=>' . $v . '; ');
        }

        if (YandexKassa::checkMd5($_POST)) {
            fwrite($paymentLog, 'MD5 correct!');
            $yaKassa->formResponse(0, 'OK');
            //$yaKassa->formResponse(1,'Error'); // just for test
        } else {
            fwrite($paymentLog, 'MD5 incorrect!');
            $yaKassa->formResponse(1, 'Ошибка авторизации');
        }
    }

    // запроса от яндекса о платеже или отказе
    public function actionPaymentAviso()
    {
        $yaKassa = new YandexKassa($_POST);

        $paymentLog = fopen($_SERVER['DOCUMENT_ROOT'] . YandexKassa::PAYMENT_LOG_FILE, 'w+');

        foreach ($_POST as $k => $v) {
            fwrite($paymentLog, $k . '=>' . $v . '; ');
        }

        if (YandexKassa::checkMd5($_POST)) {
            fwrite($paymentLog, 'MD5 correct!');
            if ($yaKassa->payQuestion()) {
                $yaKassa->formResponse(0, 'OK', 'paymentAviso');
            } else {
                $yaKassa->formResponse(1, 'Error', 'paymentAviso');
            }
        } else {
            fwrite($paymentLog, 'MD5 incorrect!');
            $yaKassa->formResponse(1, 'Error', 'paymentAviso');
        }
    }

    /**
     * прием лида по POST запросу.
     *
     * @deprecated
     */
    public function actionSendLead()
    {
        if (!isset($_POST)) {
            echo json_encode([
                'code' => 400,
                'message' => 'No input data',
            ]);
            Yii::app()->end();
        }
        $model = new Lead();
        //$leadAppId = 'yurCrm';
        /*
         * захардкодим возможные приложения для поставки лидов, потом будем хранить их в базе
         * appId => [
         *      'secretKey' => 'xxxxx',
         *      'sourceId'  =>  N,
         * ]
         */
        $leadApps = [
            'yurCrm' => [
                'secretKey' => 'Let me speak from my heart',
                'sourceId' => 3,
            ],
            'yurCrmRegions' => [
                'secretKey' => 'Euro integration',
                'sourceId' => 1,
            ],
        ];

        // проверим параметр appId, есть ли он в списке известных приложений
        if (!array_key_exists($_POST['appId'], $leadApps)) {
            echo json_encode([
                'code' => 400,
                'message' => 'Unknown sender. Check App ID parameter',
            ]);
            Yii::app()->end();
        }

        $activeApp = $leadApps[$_POST['appId']];

        $model->attributes = $_POST;
        $model->sourceId = $activeApp['sourceId'];
        $model->type = Lead::TYPE_INCOMING_CALL;
        $model->phone = PhoneHelper::normalizePhone($model->phone);

        $appSecret = $activeApp['secretKey'];
        // сформируем подпись на основе принятых данных
        $signature = md5($model->name . $model->phone . $model->email . $model->question . $model->townId . $_POST['appId'] . $appSecret);

        // проверим подпись
        if ($signature !== $_POST['signature']) {
            echo json_encode([
                'code' => 403,
                'message' => 'Signature wrong',
            ]);
            Yii::app()->end();
        }

        if ($model->findDublicates()) {
            die(json_encode([
                'code' => 400,
                'message' => 'Dublicates found',
            ]));
            Yii::app()->end();
        }

        if ($model->save()) {
            echo json_encode([
                'code' => 200,
                'message' => 'OK',
            ]);
            Yii::app()->end();
        } else {
            echo json_encode([
                'code' => 500,
                'message' => 'App\models\Lead not saved.',
                'errors' => $model->errors,
            ]);
            Yii::app()->end();
        }
    }

    public function actionArchive($date)
    {
        $dateParts = explode('-', $date);
        $year = $dateParts[0];
        $month = $dateParts[1];

        $date = (new DateTime())->setDate($year, $month, 1);
        if (!($date instanceof DateTime) || $date > (new DateTime())) {
            throw new CHttpException(400, 'Некорректная дата');
        }

        $questionsDataProvider = new CActiveDataProvider(Question::class, [
            'criteria' => [
                'condition' => 'YEAR(publishDate)=' . $year . ' AND MONTH(publishDate)=' . $month . ' AND status IN (' . Question::STATUS_CHECK . ', ' . Question::STATUS_PUBLISHED . ')',
                'order' => 'publishDate DESC',
                'with' => 'answersCount',
            ],
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        // месяцы, за которые есть вопросы
        $datesArray = [];
        $datesRows = Yii::app()->db->createCommand()
            ->select('MONTH(publishDate) month')
            ->from('{{question}}')
            ->where('YEAR(publishDate) = :year AND status IN (:status1, :status2)', [
                ':status1' => Question::STATUS_CHECK,
                ':status2' => Question::STATUS_PUBLISHED,
                ':year' => $year,
            ])
            ->group('month')
            ->queryAll();
        foreach ($datesRows as $row) {
            if ($row['month']) {
                $datesArray[] = $row['month'];
            }
        }

        $this->render('archive', [
            'dataProvider' => $questionsDataProvider,
            'year' => $year,
            'month' => $month,
            'datesArray' => $datesArray,
        ]);
    }

    /**
     * Отметка комментариев к ответам вопроса как прочитанные.
     */
    public function actionCheckCommentsAsRead()
    {
        $request = Yii::app()->request;
        if (!$request->isAjaxRequest) {
            throw new CHttpException(400, 'Запрос должен быть в формате AJAX');
        }

        $userId = Yii::app()->user->id;
        $questionId = (int)$_POST['id'];

        $question = Question::model()->findByPk($questionId);

        if (!$question) {
            throw new CHttpException(404, 'Вопрос не найден');
        }

        if ($question->checkCommentsAsRead($userId)) {
            echo json_encode([
                'code' => 200,
                'message' => '',
                'id' => $question->id,
            ]);
            Yii::app()->end();
        } else {
            echo json_encode([
                'code' => 500,
                'message' => 'Не удалось отметить комментарии прочитанными',
                'id' => $question->id,
            ]);
            Yii::app()->end();
        }
    }

    /**
     * Отображение страницы Мои вопросы.
     */
    public function actionMy()
    {
        $user = User::model()->findByPk(Yii::app()->user->id);

        $questionCriteria = new CDbCriteria();
        $questionCriteria->order = 't.publishDate DESC';
        $questionCriteria->with = 'answersCount';
        $questionCriteria->addColumnCondition(['t.authorId' => $user->id]);
        $questionCriteria->addInCondition('t.status', [Question::STATUS_PUBLISHED, Question::STATUS_CHECK]);
        $questions = Question::model()->findAll($questionCriteria);

        $this->render('my', [
            'questions' => $questions,
            'user' => $user,
        ]);
    }
}
