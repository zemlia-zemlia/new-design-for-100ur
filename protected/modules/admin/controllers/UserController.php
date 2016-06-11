<?php

class UserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//admin/main';
        public $defaultAction = 'profile';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
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
	public function accessRules()
	{
		return array(
                        array('allow',
                            'actions'   =>  array('restorePassword', 'captcha','confirm'),
                            'users'     =>  array('*'),
                        ),
			array('allow', // действия, разрешенные для всех авторизованных пользователей
				'actions'=>array('profile','update','view', 'removeAvatar', 'changePassword'),
				'users'=>array('@'),
			),
                        array('allow', // действия, разрешенные для всех пользователей типа менеджер
				'actions'=>array('create','ConfirmationSent','index','delete', 'stats'),
				'users'=>array('@'),
                                'expression'=>'Yii::app()->user->checkAccess(' . User::ROLE_MANAGER . ')',
			),
                        array('allow', // действия, разрешенные для всех пользователей типа менеджер
				'actions'=>array('mystats'),
				'users'=>array('@'),
                                'expression'=>'Yii::app()->user->role == ' . User::ROLE_JURIST,
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

        public function actions()
        {
            return array(
                'captcha'=>array(
                    'class'=>'CCaptchaAction',
                    'foreColor'=>0xff0000,
                    'minLength'=>6,
                    'maxLength'=>8,
                ),
            );
        }
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		
                                 
            $model = User::model()->findByPk($id);
       
            $this->render('view',array(
			'model'             =>  $model,
		));
	}
        

        public function actionProfile()
	{
            $userId = Yii::app()->user->id;
            $model = User::model()->findByPk($userId);
            
            $this->render('view',array(
			'model' =>  $model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new User;
                $model->setScenario('create');
                $yuristSettings = new YuristSettings();
                
                // массив кодов и названий ролей пользователей
                $rolesNames = User::getRoleNamesArray();
                
                // массив пользователей  ролью Менеджер
                $allManagersNames = User::getManagersNames();
                
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
                    $model->attributes=$_POST['User'];
                    // генерируем пароль для пользователя
                    $newPassword = User::generatePassword(8);
                    $model->password = $newPassword;
                    $model->confirm_code = md5($model->email.mt_rand(100000,999999));
                    
                                        
                    if(!empty($_FILES))
                    {
                        $file = CUploadedFile::getInstance($model,'avatarFile');
                        
                        if($file && $file->getError()==0) // если файл нормально загрузился
                        {
                            // определяем имя файла для хранения на сервере
                            $newFileName = md5($file->getName().$file->getSize().mt_rand(10000,100000)).".".$file->getExtensionName();
                            Yii::app()->ih
                            ->load($file->tempName)
                            ->resize(600, 600, true)
                            ->save(Yii::getPathOfAlias('webroot') . User::USER_PHOTO_PATH . '/' . $newFileName)
                            ->reload()
                            ->adaptiveThumb(120,120, array(255,255,255))
                            ->save(Yii::getPathOfAlias('webroot') . User::USER_PHOTO_PATH . User::USER_PHOTO_THUMB_FOLDER . '/' . $newFileName);
                        
                            $model->avatar = $newFileName;
                                                        
                        }

                    }
                    
                    if($model->save()) {
                        /*
                         * после сохранения модели, в свойстве password хранится зашифрованный пароль
                         * а в переменной $newPassword - незашифрованный
                         */
                        if($model->role == User::ROLE_EXECUTOR) {
                            // при регистрации исполнителя не отправляем ему письмо
                            $this->redirect(array('ConfirmationSent'));
                        }
                        
                        // если мы добавили юриста
                        if($model->role == User::ROLE_JURIST && isset($_POST['YuristSettings'])) {
                            $yuristSettings->attributes = $_POST['YuristSettings'];
                            $yuristSettings->yuristId = $model->id;
                            $yuristSettings->save();
                        }
                        
                        if($model->sendConfirmation($newPassword)) {
                            $this->redirect(array('ConfirmationSent'));
                        } else {
                            throw new CHttpException(500,'Что-то пошло не так. Мы не смогли отправить пользователю письмо с подтверждением регистрации на сайте. Не беспокойтесь, с аккаунтом все в порядке, просто письмо с подтверждением не отправилось.');
                        }   
                            
                    }
		}

                $townsArray = Town::getTownsIdsNames();
                
		$this->render('create',array(
			'model'             =>  $model,
                        'rolesNames'        =>  $rolesNames,
                        'allManagersNames'  =>  $allManagersNames,
                        'yuristSettings'    =>  $yuristSettings,
                        'townsArray'        =>  $townsArray,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
                $model->setScenario('update');

                if($model->settings) {
                    $yuristSettings = $model->settings;
                } else {
                    $yuristSettings = new YuristSettings();
                    $yuristSettings->yuristId = $model->id;
                }
                
                if(!Yii::app()->user->checkAccess(User::ROLE_MANAGER) && Yii::app()->user->id!==$model->id) {
                    throw new CHttpException(403, 'У вас нет прав редактировать этот профиль');
                    //echo "У вас нет прав редактировать этот профиль";
                }
                
                // массив кодов и названий ролей пользователей
                $rolesNames = User::getRoleNamesArray();
                
                // массив пользователей  ролью Менеджер
                $allManagersNames = User::getManagersNames();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
                    // присваивание атрибутов пользователя
                    $model->attributes=$_POST['User'];
                    $yuristSettings->attributes = $_POST['YuristSettings'];
                    
                    // если мы редактировали юриста
                    if(isset($_POST['YuristSettings'])) {
                        $yuristSettings->attributes = $_POST['YuristSettings'];
                                     
                        $yuristSettings->save();
                        
                    }
                    
                    // загрузка аватарки
                    if(!empty($_FILES))
                    {
                        $file = CUploadedFile::getInstance($model,'avatarFile');
                        
                        if($file && $file->getError()==0) // если файл нормально загрузился
                        {
                            // определяем имя файла для хранения на сервере
                            $newFileName = md5($file->getName().$file->getSize().mt_rand(10000,100000)).".".$file->getExtensionName();
                            Yii::app()->ih
                            ->load($file->tempName)
                            ->resize(600, 600, true)
                            ->save(Yii::getPathOfAlias('webroot') . User::USER_PHOTO_PATH . '/' . $newFileName)
                            ->reload()
                            ->adaptiveThumb(120,120, array(255,255,255))
                            ->save(Yii::getPathOfAlias('webroot') . User::USER_PHOTO_PATH . User::USER_PHOTO_THUMB_FOLDER . '/' . $newFileName);
                        
                            $model->avatar = $newFileName;
                                                        
                        }

                    }
                    if($model->save() && $yuristSettings->hasErrors() == false){
                        $this->redirect(array('view','id'=>$model->id));
                    }
		} else {
                    $model->password = '';
                }
                             
                $townsArray = Town::getTownsIdsNames();
                
		$this->render('update',array(
			'model'             =>  $model,
                        'rolesNames'        =>  $rolesNames,
                        'allManagersNames'  =>  $allManagersNames,
                        'yuristSettings'    =>  $yuristSettings,
                        'townsArray'        =>  $townsArray,
		));
	}
        
        public function actionChangePassword($id)
        {
            // если пользователь не админ, он может менять пароль только у себя
            if(Yii::app()->user->id !== $id && !Yii::app()->user->checkAccess(User::ROLE_ROOT)) {
                throw new CHttpException(403, 'У вас нет прав менять пароль другого пользователя'); 
            }
            
            $model = User::model()->findByPk($id);
            $model->password = '';
            
            // если была заполнена форма
            if($_POST['User']) {
                $model->attributes = $_POST['User'];
                if($model->validate()){
                    // если данные пользователя прошли проверку (пароль не слишком короткий)
                    // шифруем пароль перед сохранением в базу
                    $model->password = User::hashPassword($model->password);
                    $model->password2 = $model->password;
                    if($model->save()){
                        $this->redirect(array('view','id'=>$model->id));
                    }
                }
            }
            $this->render('changePassword',array(
			'model'             =>  $model,
                ));
        }

        /**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$criteria = new CDbCriteria;
                $criteria->order = 't.active DESC, t.role DESC';
                
                // добавим условие выборки контактов по офису

                $officeId = 0;

                $criteria->addColumnCondition(array('t.officeId'    =>  $officeId));
                
                $usersArray = User::model()->findAll($criteria);
                
		$this->render('index',array(
			'usersArray'  =>  $usersArray,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
        
        public function actionConfirmationSent()
        {
           $this->render('confirmationSent');  
        }
        
        public function actionConfirm()
        {
           $email = CHtml::encode($_GET['email']);
           $code = CHtml::encode($_GET['code']);
           
           $criteria = new CDbCriteria;
           $criteria->addColumnCondition(array('email'=>$email));
           $criteria->addColumnCondition(array('confirm_code'=>$code));
           $criteria->addColumnCondition(array('active'=>0));
           $criteria->limit = 1;
           
           //находим пользователя с данным мейлом и кодом подтверждения
           $user = User::model()->find($criteria);
           
           if(!empty($user))
           {
              $user->setScenario('confirm');
              if($user->active==0) 
              {
                $user->activate();
              }
              
              if($user->save())
              {
                  if(Yii::app()->user->isGuest)
                  {
                    $this->render('activationSuccess');
                  }
                  else
                  {
                      $this->redirect('/user');
                  }
              }
              else
              {
                  if(!empty($user->errors))
                  {
                      print "<pre>";
                      print_r($user->errors);
                      print "</pre>";
                  }
                  $this->render('activationFailed', array('message'=>'Ошибка - не удалось активировать аккаунт из-за ошибки в программе.<br />
                      Обратитесь, пожалуйста, к администратору сайта через E-mail ' . Yii::app()->params['adminEmail']));
              }
              
           }
           else 
           {
                $this->render('activationFailed', array('message'=>'Пользователь с данным мейлом не найден или уже активирован'));
           }
        }
        
        // восстановление пароля пользователя
        public function actionRestorePassword()
        {
            $this->layout='//frontend/login';
            
            // $model - модель с формой восстановления пароля
            $model=new RestorePasswordForm;
            
            if(isset($_POST['RestorePasswordForm']))
            {
                // получили данные из формы восстановления пароля
                $model->attributes = $_POST['RestorePasswordForm'];
                $email = CHtml::encode($model->email);
                // ищем пользователя по введенному Email, если не найден, получим NULL
                $user = User::model()->findByAttributes(array('email'=>$email));
                if($user)
                {
                    // если пользователь существует, генерируем ему новый пароль
                    $newPassword = User::generatePassword(6);
                    $user->setScenario("restorePassword");
                    if($user->changePassword($newPassword))
                    {
                        // если удалось изменить пароль
                        $message = "Пароль изменен и отправлен на E-mail";
                    }
                    else
                    {
                        // если не удалось изменить пароль
                        $message = "Ошибка! Не удалось изменить пароль";
                    }
                    $this->render('restorePassword', array('model'=>$model, 'message'=>$message));
                }
            }
            else
            {
                // форма не была отправлена, отображаем форму
                $this->render('restorePassword', array('model' => $model));
            }
            
        }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Пользователь не найден');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param User $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionRemoveAvatar($id)
        {
            $user = User::model()->findByPk($id);
            $user->scenario = 'removeAvatar';
            
            if($user->id != Yii::app()->user->id && !Yii::app()->user->checkAccess(User::ROLE_ROOT)) {
                throw new CHttpException(403,'Отказано в доступе к удалению аватара');
            }
            
            @unlink(Yii::getPathOfAlias('webroot') . User::USER_PHOTO_PATH . "/" . $user->avatar);
            @unlink(Yii::getPathOfAlias('webroot') . User::USER_PHOTO_PATH  . User::USER_PHOTO_THUMB_FOLDER . "/" . $user->avatar);
            $user->avatar = '';
            
            if($user->save()) {
                $this->redirect(array('user/view', 'id'=>$user->id));
            } else {
                CustomFuncs::printr($user->errors);
                throw new CHttpException(500,'Не удалось удалить аватар');
            }
        }
        
        // показывает юристу его показатели
        public function actionMystats()
        {
            if($_GET['month']) {
                $month = (int)$_GET['month'];
            } else {
                $month = date("n");
            }
            if($_GET['year']) {
                $year = (int)$_GET['year'];
            } else {
                $year = date("Y");
            }
            
            // найдем все годы, в которые есть контакты
            $yearsRows = Yii::app()->db->cache(600)->createCommand()
                    ->select('DISTINCT(YEAR(question_date)) y')
                    ->from('{{contact}}')
                    ->where('YEAR(question_date)!=0')
                    ->queryColumn();
            $yearsArray = array();
            foreach($yearsRows as $k=>$v) {
                $yearsArray[$v] = $v;
            }
            
            // находим все контакты заданных юристов, появившиеся в заданный месяц
            $leadsRows = Yii::app()->db->createCommand()
                    ->select('COUNT(*) counter, c.status, u.id userId')
                    ->from('{{contact}} c')
                    ->join('{{user}} u', 'c.employeeId = u.id')
                    ->where(array('and', 'u.id = :uid', 'MONTH(question_date)=:month', 'YEAR(question_date)=:year'), array(":month"=>$month, ":year"=>$year, ":uid"=>Yii::app()->user->id))
                    ->group('c.status')
                    ->queryAll();
            //CustomFuncs::printr($leadsRows);
            $leadsArray = array();
            
            foreach($leadsRows as $leadsRow) {
                if($leadsRow['status'] == Contact::STATUS_LEAD || $leadsRow['status'] == Contact::STATUS_CLIENT || $leadsRow['status'] == Contact::STATUS_CONSULT) {
                    $leadsArray['active'] += $leadsRow['counter'];
                } elseif($leadsRow['status'] == Contact::STATUS_BRAK){
                    $leadsArray['brak'] += $leadsRow['counter'];
                } elseif($leadsRow['status'] == Contact::STATUS_NABRAK){
                    $leadsArray['nabrak'] += $leadsRow['counter'];
                } elseif($leadsRow['status'] == Contact::STATUS_OTKAZ){
                    $leadsArray['otkaz'] += $leadsRow['counter'];
                } 
                $leadsArray['total'] += $leadsRow['counter'];
            }
            
            //CustomFuncs::printr($leadsArray);
            
            // находим договоры, заключенные в выбранный месяц с разбивкй по каналам
            $agreementsRows = Yii::app()->db->cache(60)->createCommand()
                    ->select('COUNT(*) counter, SUM(a.totalPrice) sum, a.status agreement_status')
                    ->from('{{agreement}} a')
                    ->join('{{contact}} c', 'a.contactId = c.id')
                    ->where(array('and', 'a.employeeId = :uid' ,'MONTH(a.create_date)=:month', 'YEAR(a.create_date)=:year'), array(":month"=>$month, ":year"=>$year, ":uid"=>Yii::app()->user->id))
                    ->group('agreement_status')
                    ->queryAll();
            
            //CustomFuncs::printr($agreementsRows);
            $agreementsArray = array();

            foreach($agreementsRows as $agreementsRow) {
                                
                // отдельно посчитаем данные по расторгнутым договорам
                if($agreementsRow['agreement_status'] == Agreement::STATUS_ABORTED) {
                    $agreementsArray['aborted']['counter'] += $agreementsRow['counter'];
                    $agreementsArray['aborted']['sum'] += $agreementsRow['sum'];

                } else {
                    // а отдельно - по всем остальным
                    $agreementsArray['counter'] += $agreementsRow['counter'];
                    $agreementsArray['sum'] += $agreementsRow['sum'];

                }
            }
            //CustomFuncs::printr($agreementsArray);
            
            $this->render('myStats', array(
                'month'             =>  $month,
                'year'              =>  $year,
                'yearsArray'        =>  $yearsArray,
                'leadsArray'        =>  $leadsArray,
                'agreementsArray'   =>  $agreementsArray,
            ));
        }
}