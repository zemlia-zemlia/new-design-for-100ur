<?phpclass SiteController extends Controller
{
    public $layout = '//frontend/index';
    public function filters()
    {
        return array(            'accessControl', // perform access control for CRUD operations            array(                'COutputCache',                'duration' => 0,                'requestTypes' => array('GET'),                'varyByExpression' => "Yii::app()->user->id",                'varyByParam' => 'code',            ),        );
    }
    /**         * Specifies the access control rules.         * This method is used by the 'accessControl' filter.         * @return array access control rules         */
    public function accessRules()
    {
        return array(            array('allow', // разрешаем неавторизованным пользователям доступ к странице логина и капче                'actions' => array('index', 'about', 'login', 'logout', 'captcha', 'error', 'contacts', 'partners', 'lp', 'konsultaciya_yurista_advokata', 'clearCache', 'offer', 'crm', 'lead', 'goryachaya_liniya', 'brakLead', 'klienti_dlya_yuristov', 'referal', 'passwordChanged', 'turbo', 'yuristam'),                'users' => array('*'),            ),            array('allow',                'actions' => array('info'),                'expression' => 'Yii::app()->user->role == ' . User::ROLE_ROOT,            ),            array('allow',                'actions' => array('rangs'),                'expression' => 'Yii::app()->user->role == ' . User::ROLE_JURIST,            ),            array('allow',                'actions' => array('upload'),                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_EDITOR . ')',            ),            array('deny', // запрещаем все, что не разрешено                'users' => array('*'),            ),        );
    }
    public function actions()
    {
        return array(            // captcha action renders the CAPTCHA image displayed on the contact page            'captcha' => array(                'class' => 'CCaptchaAction',                'backColor' => 0xFFFFFF,            ),            // page action renders "static" pages stored under 'protected/views/site/pages'            // They can be accessed via: index.php?r=site/page&view=FileName            'page' => array(                'class' => 'CViewAction',            ),        );
    }
    /**         * This is the action to handle external exceptions.         */
    public function actionError()
    {
        $this->layout = '//frontend/smart';
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }
    // главная страница системы
    public function actionIndex()
    {
        if ($_SERVER['REQUEST_URI'] == '/site/') {
            header('Location: ' . Yii::app()->urlManager->baseUrl, true, 301);
        }
        $questionModel = new Question();
        $this->render('index', array(            //'questions' => $questions,            'questionModel' => $questionModel,        ));
    }
    public function actionContacts()
    {
        $contactForm = new ContactForm;
        // в массиве $formResult будем хранить результат отправки формы
        $formResult = array();
        if (isset($_POST['ContactForm'])) {
            $contactForm->attributes = $_POST['ContactForm'];
            if ($contactForm->validate()) {                // Пытаемся отправить письмо
                $mailer = new GTMail;
                $mailer->subject = "Сообщение с сайта 100 юристов";
                $mailer->email = Yii::app()->params['adminNotificationsEmail'];
                $mailer->message = "Имя отправителя: " . CHtml::encode($contactForm->name) . "<br />" .                    "Email отправителя: " . CHtml::encode($contactForm->email) . "<br />" .                    "Сообщение:<br />" . CHtml::encode($contactForm->message);
                if ($mailer->sendMail()) {
                    $formResult = array('code' => 0, 'message' => 'Сообщение успешно отправлено');
                    $contactForm = new ContactForm;
                } else {
                    $formResult = array('code' => 500, 'message' => 'Ошибка, не удалось отправить сообщение');
                }
            }
        }
        $this->render('contacts', array(            'contactForm' => $contactForm,            'formResult' => $formResult,        ));
    }
    public function actionPartners()
    {
        $this->layout = '//frontend/lp';
        $this->render('partners');
    }
    public function actionKonsultaciya_yurista_advokata()
    {
        $this->redirect('/', true, 301);
    }
    public function actionOffer()
    {
        $this->render('offer');
    }
    public function actionCrm()
    {
        $this->render('crm');
    }
    public function actionAbout()
    {
        $this->render('about');
    }
    public function actionLead()
    {
        $contactForm = new ContactForm;
        $this->layout = '//frontend/lp';
        $this->render('lead', array(            'contactForm' => $contactForm,        ));
    }
    public function actionGoryachaya_liniya()
    {
        $this->layout = '//frontend/question';
        $this->render('goryachaya_liniya');
    }
    public function actionInfo()
    {
        phpinfo();
    }
    /**         * Страница с описанием работы для юриста         */
    public function actionYuristam()
    {
        $this->layout = '//frontend/question';
        $this->render('yuristam');
    }
    public function actionLogin()
    {
        $this->layout = '//frontend/smart';
        $model = new LoginForm;
        // если использовался вход по мейлу и паролю
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                LoggerFactory::getLogger('db')->log(Yii::app()->user->roleName . ' #' . Yii::app()->user->id . ' (' . Yii::app()->user->shortName . ') залогинился на сайте', 'User', Yii::app()->user->id);
                (new UserActivity())->logActivity(Yii::app()->user->getModel(), UserActivity::ACTION_LOGIN);
                if (Yii::app()->user->checkAccess(User::ROLE_EDITOR)) {                    // контент-менеджера и админа сразу перекидываем в админку                    $_SESSION['editor_logged_in'] = 1; // чтобы получить доступ к CKfinder                    $this->redirect('/admin');
                } elseif (Yii::app()->user->role == User::ROLE_BUYER) {                    // покупателя сразу перекидываем в кабинет
                    $this->redirect('/cabinet');
                } elseif (Yii::app()->user->role == User::ROLE_PARTNER) {                    // вебмастера сразу перекидываем в его кабинет
                    $this->redirect('/webmaster/');
                } elseif (Yii::app()->user->role == User::ROLE_JURIST) {                    // вебмастера сразу перекидываем в его кабинет
                    $this->redirect('/question/search/');
                } else {
                    $this->redirect('/site');
                }
            }
        }
        // display the login form
        $this->render('login', array(            'model' => $model,        ));
    }
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
    public function actionClearCache()
    {
        if (Yii::app()->cache->flush()) {
            echo "1";
        } else {
            echo "0";
        }
    }
    public function actionLp()
    {
        $this->layout = '//frontend/short';
        $this->render('lp', array());
    }
    /**         * отбраковка лида         *         * @param string $code секретный код, который позволяет 1 раз забраковать лида,         * перейдя по ссылке без авторизации         * @throws CHttpException         */
    public function actionBrakLead($code)
    {
        $this->layout = '//frontend/atom';
        if ($code == '') {
            return $this->render('brakError', [                'errorTitle' => 'Не передан код лида',                'errorMessage' => 'Возможно, вы перешли по неправильной ссылке',            ]);
        }
        $code = CHtml::encode($code);
        $lead = Lead::model()->findByAttributes(array('secretCode' => $code));
        $lead->setScenario('brak');
        if (!$lead) {
            return $this->render('brakError', [                'errorTitle' => 'Лид не найден',                'errorMessage' => 'Лид не найден в базе. Возможно, вы перешли по неработающей ссылке',            ]);
        }
        if ($lead->leadStatus != Lead::LEAD_STATUS_SENT) {
            return $this->render('brakError', [                'errorTitle' => 'Этот лид нельзя забраковать',                'errorMessage' => 'Нельзя отправить на отбраковку лид, находящийся в текущем статусе',            ]);
        }
        if (!(!is_null($lead->deliveryTime) && (time() - strtotime($lead->deliveryTime) < 86400 * Yii::app()->params['leadHoldPeriodDays']))) {
            return $this->render('brakError', [                'errorTitle' => 'Период отбраковки лида истёк',                'errorMessage' => 'Нельзя отправить на отбраковку лид, отправленный покупателю более ' . Yii::app()->params['leadHoldPeriodDays'] . ' суток назад',            ]);
        }
        // проверим, не достигнут ли лимит брака для кампании
        $campaign = $lead->campaign;
        if ($campaign) {
            $date = date('Y-m-d', strtotime($lead->deliveryTime)); // дата, на которую считать процент брака
            if ($campaign->checkCanBrak($date) == false) {
                return $this->render('brakLeadLimit', [                    'campaign' => $campaign,                ]);
            }
        }
        if (isset($_POST['Lead'])) {
            $lead->attributes = $_POST['Lead'];
            if (!$lead->brakReason) {
                $lead->addError('brakReason', 'Не указана причина отбраковки');
            }
            if (!$lead->brakComment) {
                $lead->addError('brakComment', 'Не указан комментарий отбраковки');
            }
            if ($lead->brakReason == Lead::BRAK_REASON_BAD_REGION && !$lead->newTownId) {
                $lead->addError('brakReason', 'Не указан новый город');
            }
            if ($lead->brakReason == Lead::BRAK_REASON_BAD_REGION && $lead->newTownId == $lead->townId) {
                $lead->addError('brakReason', 'Новый город не должен быть таким же, что и старый');
            }
            /*                 * Если в форме отбраковки указана причина "Неправильный регион" и задан                 * новый город лида, создаем новый лид с такими же свойствами, как старый, но                 * уже с новым регионом                 */
            if ($lead->brakReason == Lead::BRAK_REASON_BAD_REGION && $lead->newTownId) {
                $newLead = new Lead;
                $newLead->townId = $lead->newTownId;
                $newLead->question = $lead->question;
                $newLead->name = $lead->name;
                $newLead->phone = $lead->phone;
                $newLead->sourceId = 41; // источник - нерегион
            }
            $lead->leadStatus = Lead::LEAD_STATUS_NABRAK;
            if (!$lead->hasErrors() && $lead->save()) {                // новый лид сохраняем только когда сохранен старый
                if ($newLead instanceof Lead) {
                    $newLead->save();
                }
                $this->render('brakLeadSuccess');
                Yii::app()->end();
            }
        }
        //CustomFuncs::printr($lead->attributes);
        $this->render('brakLead', array(            'lead' => $lead,        ));
    }
    public function actionUpload()
    {
        if (!empty($_FILES)) {
            $file = CUploadedFile::getInstanceByName('file');
            if ($file && $file->getError() == 0) { // если файл нормально загрузился
                $newFileName = md5($file->getName() . $file->getSize() . mt_rand(10000, 100000)) . "." . $file->getExtensionName();
                $path = Post::PHOTO_PATH . '/' . $newFileName;
                if ($file->saveAs(Yii::getPathOfAlias('webroot') . $path)) {
                    $array = array(//                        'url' => Yii::app()->urlManager->baseUrl . $path,                        'url' => $path,                        'name' => $newFileName                    );
                    echo stripslashes(json_encode($array));
                }
            }
        }
    }
    /**         * Реферальная программа         */
    public function actionReferal()
    {
        $this->layout = "//frontend/question";
        $referals = [];
        // Если пользователь не гость, ищем пользователей, которых он пригласил
        if (!Yii::app()->user->isGuest) {
            $criteria = new CDbCriteria();
            $criteria->addColumnCondition(['refId' => Yii::app()->user->id, 'active100' => 1]);
            $criteria->order = "id DESC";
            $criteria->with = ["answersCount", "questionsCount"];
            $referals = User::model()->findAll($criteria);
        }
        $this->render('referal', [            'referals' => $referals,        ]);
    }
    /**         * Страница результат успешной смены пароля         */
    public function actionPasswordChanged()
    {
        $this->layout = '//frontend/smart';
        $isYurcrmRegistered = isset($_GET['yurcrm']);
        $this->render('passwordChanged', [            'isYurcrmRegistered' => $isYurcrmRegistered,        ]);
    }
    // generates RSS 2.0 feed with active trips
    public function actionTurbo()
    {
        $criteria = new CDbCriteria();
        $criteria->limit = 1000;
        $criteria->order = 'publish_date DESC';
        $criteria->addCondition('description1 != "" AND seoH1!=""');
        $categories = QuestionCategory::model()->findAll($criteria);
        $outputString = '<rss xmlns:yandex="http://news.yandex.ru"    xmlns:media="http://search.yahoo.com/mrss/"    xmlns:turbo="http://turbo.yandex.ru"    version="2.0">';
        $outputString .= '<channel>             <title>100 Юристов</title>            <link>' . Yii::app()->urlManager->baseUrl . '</link>            <language>ru</language>            <description>Юридическая консультация и услуги юристов онлайн</description>';
        foreach ($categories as $category) {
            $link = Yii::app()->createUrl('/questionCategory/alias', $category->getUrl());
            $outputString .= '<item turbo="true"><link>' . $link . '</link>';
            $outputString .= '<turbo:content><![CDATA[';
            $outputString .= '<header>                       <figure>                           <img                            src="' . Yii::app()->urlManager->baseUrl . $category->getImagePath() . '" />                       </figure>                       <h1>' . $category->seoH1 . '</h1>                   </header>';
            $outputString .= $category->description1;
            $outputString .= ']]></turbo:content></item>';
        }
        $outputString .= '</channel></rss>';
        echo $outputString;
        Yii::app()->end();
    }
    public function actionRangs()
    {
        $this->layout = "//frontend/question";
        $rangs = new YuristRang(Yii::app()->params['rangs']);
        $rangsInfo = $rangs->getRangs();
        $user = User::model()->findByPk(Yii::app()->user->id);
        $this->render('rangs', [            'rangsInfo' => $rangsInfo,            'user' => $user,        ]);
    }
}
