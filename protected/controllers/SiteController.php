<?phpclass SiteController extends Controller{	public $layout='//frontend/main';                public function filters()	{		return array(			'accessControl', // perform access control for CRUD operations		);	}                /**	 * Specifies the access control rules.	 * This method is used by the 'accessControl' filter.	 * @return array access control rules	 */	public function accessRules()	{            return array(                                array('allow',  // разрешаем неавторизованным пользователям доступ к странице логина и капче                        'actions'=>array('index', 'about', 'login', 'logout', 'captcha','error', 'contacts', 'partners', 'lp', 'konsultaciya_yurista_advokata', 'clearCache', 'offer', 'crm', 'lead', 'goryachaya_liniya', 'brakLead'),                        'users'=>array('*'),                ),                array('allow',                    'actions'   =>  array('info'),                    'expression'=>'Yii::app()->user->role == ' . User::ROLE_ROOT,                ),                array('deny',  // запрещаем все, что не разрешено                        'users'=>array('*'),                ),            );	}                		public function actions()	{		return array(                    // captcha action renders the CAPTCHA image displayed on the contact page                    'captcha'=>array(                            'class'=>'CCaptchaAction',                            'backColor'=>0xFFFFFF,                    ),                    // page action renders "static" pages stored under 'protected/views/site/pages'                    // They can be accessed via: index.php?r=site/page&view=FileName                    'page'=>array(                            'class'=>'CViewAction',                    ),		);	}	/**	 * This is the action to handle external exceptions.	 */	public function actionError()	{	    if($error=Yii::app()->errorHandler->error){	    	if(Yii::app()->request->isAjaxRequest){                    echo  $error['message'];                } else {                    $this->render('error', $error);                }	    }	}        // главная страница системы        public function actionIndex()	{            if($_SERVER['REQUEST_URI'] == '/site/') {                header('Location: ' . Yii::app()->urlManager->baseUrl, true, 301);            }                        $questions = Yii::app()->db->cache(600)->createCommand()                    ->select('q.id id, q.publishDate date, q.title title, COUNT(*) counter')                    ->from('{{question}} q')                    ->leftJoin('{{answer}} a', 'q.id=a.questionId')                    ->group('q.id')                    ->where('(q.status=:status1 OR q.status=:status2) AND a.id IS NOT NULL', array(':status1'=>  Question::STATUS_PUBLISHED, ':status2'=>  Question::STATUS_CHECK))                    ->limit(5)                    ->order('q.publishDate DESC')                    ->queryAll();                        $questionModel = new Question();            $this->render('index',array(                    'questions'     =>  $questions,                    'questionModel' =>  $questionModel,            ));	}                public function actionContacts()        {            $contactForm = new ContactForm;                        // в массиве $formResult будем хранить результат отправки формы            $formResult = array();                        if(isset($_POST['ContactForm'])) {                $contactForm->attributes = $_POST['ContactForm'];                                if($contactForm->validate()) {                    // Пытаемся отправить письмо                    $mailer = new GTMail;                    $mailer->subject = "Сообщение с сайта 100 юристов";                    $mailer->email = Yii::app()->params['adminNotificationsEmail'];                    $mailer->message = "Имя отправителя: " . CHtml::encode($contactForm->name) . "<br />" .                             "Email отправителя: " . CHtml::encode($contactForm->email) . "<br />" .                            "Сообщение:<br />" . CHtml::encode($contactForm->message);                                                        if($mailer->sendMail()) {                        $formResult = array('code'=>0, 'message'=>'Сообщение успешно отправлено');                        $contactForm = new ContactForm;                    } else {                        $formResult = array('code'=>500, 'message'=>'Ошибка, не удалось отправить сообщение');                    }                }            }                        $this->render('contacts', array(                'contactForm'   =>  $contactForm,                'formResult'    =>  $formResult,            ));        }                public function actionPartners()        {            $this->render('partners');        }                public function actionKonsultaciya_yurista_advokata()        {            $newQuestionModel = new Question;            $this->render('konsultaciya_yurista_advokata', array(                    'newQuestionModel'  =>  $newQuestionModel,                ));        }                public function actionOffer()        {            $this->render('offer');        }		                public function actionCrm()        {            $this->render('crm');        }                 public function actionAbout()        {            $this->render('about');        }                 public function actionLead()        {            $contactForm = new ContactForm;                        $this->render('lead', array(                'contactForm'   =>  $contactForm,            ));        }        public function actionGoryachaya_liniya()        {            $this->render('goryachaya_liniya');        }        public function actionInfo()        {            phpinfo();        } 		        public function actionLogin()	{            $model=new LoginForm;            // если использовался вход по мейлу и паролю            if(isset($_POST['LoginForm']))            {                $model->attributes=$_POST['LoginForm'];                // validate user input and redirect to the previous page if valid                if($model->validate() && $model->login()) {                    if(Yii::app()->user->checkAccess(User::ROLE_EDITOR)) {                        // контент-менеджера и админа сразу перекидываем в админку                        $this->redirect('/admin');                    } elseif(Yii::app()->user->role == User::ROLE_BUYER) {                        // покупателя сразу перекидываем в кабинет                        $this->redirect('/cabinet');                    } else {                        $this->redirect('/site');                    }                                    }            }            // display the login form            $this->render('login',array(                'model'             =>  $model,                ));	}                public function actionLogout()	{            Yii::app()->user->logout();            $this->redirect(Yii::app()->homeUrl);	}                public function actionClearCache()	{            if(Yii::app()->cache->flush()) echo "1";            else echo "0";	}                        public function actionLp()        {            $this->layout='//frontend/short';                                    $this->render('lp',array(                           ));                    }                // отбраковка лида        public function actionBrakLead($code)        {            $this->layout='//frontend/atom';                        if($code == '') {                throw new CHttpException(400,'Не передан код лида');            }                        $code = CHtml::encode($code);                        $lead = Lead100::model()->findByAttributes(array('secretCode'=>$code));                        if(!$lead) {                throw new CHttpException(404,'Лид не найден');            }                        if($lead->leadStatus != Lead100::LEAD_STATUS_SENT) {                throw new CHttpException(400,'Нельзя отправить на отбраковку лид, находящийся в текущем статусе');            }                        if(!(!is_null($lead->deliveryTime) && (time() - strtotime($lead->deliveryTime)<86400*3))) {                throw new CHttpException(400,'Нельзя отправить на отбраковку лид, отправленный покупателю более 3 суток назад');            }                                    if(isset($_POST['Lead100'])) {                $lead->attributes = $_POST['Lead100'];                                if(!$lead->brakReason) {                    $lead->addError('brakReason', 'Не указана причина отбраковки');                }                                if(!$lead->brakComment) {                    $lead->addError('brakComment', 'Не указан комментарий отбраковки');                }                                if($lead->brakReason == Lead100::BRAK_REASON_BAD_REGION && !$lead->newTownId) {                    $lead->addError('brakReason', 'Не указан новый город');                }                //                if(!empty($lead->errors)) {//                    CustomFuncs::printr($lead->errors);//                    exit;//                }                                if($lead->brakReason == Lead100::BRAK_REASON_BAD_REGION && $lead->newTownId) {                    $newLead = new Lead100;                    $newLead->townId = $lead->newTownId;                    $newLead->question = $lead->question;                    $newLead->name = $lead->name;                    $newLead->phone = $lead->phone;                    $newLead->sourceId = $lead->sourceId;                    $newLead->save();                }                                $lead->leadStatus = Lead100::LEAD_STATUS_NABRAK;                                if(!$lead->hasErrors() && $lead->save()) {                    $this->render('brakLeadSuccess');                    Yii::app()->end();                }            }                        //CustomFuncs::printr($lead->attributes);                        $this->render('brakLead', array(                'lead'  =>  $lead,            ));                    }}