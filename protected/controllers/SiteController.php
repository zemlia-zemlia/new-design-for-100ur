<?phpclass SiteController extends Controller{	public $layout='//frontend/main';                public function filters()	{		return array(			'accessControl', // perform access control for CRUD operations		);	}                /**	 * Specifies the access control rules.	 * This method is used by the 'accessControl' filter.	 * @return array access control rules	 */	public function accessRules()	{            return array(                                array('allow',  // разрешаем неавторизованным пользователям доступ к странице логина и капче                        'actions'=>array('index', 'login', 'logout', 'captcha','error', 'contacts', 'partners'),                        'users'=>array('*'),                ),                array('deny',  // запрещаем все, что не разрешено                        'users'=>array('*'),                ),            );	}                		public function actions()	{		return array(                    // captcha action renders the CAPTCHA image displayed on the contact page                    'captcha'=>array(                            'class'=>'CCaptchaAction',                            'backColor'=>0xFFFFFF,                    ),                    // page action renders "static" pages stored under 'protected/views/site/pages'                    // They can be accessed via: index.php?r=site/page&view=FileName                    'page'=>array(                            'class'=>'CViewAction',                    ),		);	}	/**	 * This is the action to handle external exceptions.	 */	public function actionError()	{	    if($error=Yii::app()->errorHandler->error)	    {	    	if(Yii::app()->request->isAjaxRequest)	    		echo  $error['message'];	    	else	        	$this->render('error', $error);	    }	}        // главная страница системы        public function actionIndex()	{            $criteria = new CDbCriteria;            $criteria->order = 't.id desc';            $criteria->limit = 5;            $criteria->with = array('categories', 'town', 'answersCount'=>array(                'having'=>'`s`>0',            ));            $criteria->addColumnCondition(array('status'    => Question::STATUS_PUBLISHED));                        $dataProvider = new CActiveDataProvider('Question', array(                'criteria'=>$criteria,                        'pagination'=>false,            ));                        $questionModel = new Question();            $this->render('index',array(                    'dataProvider'  =>  $dataProvider,                    'questionModel' =>  $questionModel,            ));	}                public function actionContacts()        {            $this->render('contacts');        }                public function actionPartners()        {            $this->render('partners');        }                        public function actionLogout()	{            Yii::app()->user->logout();            $this->redirect(Yii::app()->homeUrl);	}                        public function actionLogin()	{            $model=new LoginForm;            // если использовался вход по мейлу и паролю            if(isset($_POST['LoginForm']))            {                $model->attributes=$_POST['LoginForm'];                // validate user input and redirect to the previous page if valid                if($model->validate() && $model->login()) {                    $this->redirect('/site');                }            }            // display the login form            $this->render('login',array(                'model'             =>  $model,                ));	}}