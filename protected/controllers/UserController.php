<?php

class UserController extends Controller
{
	
	public $layout='//frontend/main';
        public $defaultAction = 'profile';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index', 'view','create','confirm','confirmationSent', 'restorePassword', 'captcha'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('update', 'profile', 'changePassword', 'updateAvatar', 'invites','deleteAvatar','clearInfo', 'requestConfirmation'),
				'users'=>array('@'),
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
        
        public function actionProfile()
        {
            $this->layout = '//frontend/short';
            
            $user = User::model()->findByPk(Yii::app()->user->id);
            
            $questionsCriteria = new CDbCriteria;
            
            if(Yii::app()->user->role == User::ROLE_CLIENT) {
                $questionsCriteria->addColumnCondition(array('t.authorId'=>Yii::app()->user->id));
                $questionsCriteria->with = 'answers';
            } else {
                $questionsCriteria->with = array(
                    'answers'   =>  array(
                        'condition'   =>  'answers.authorId = ' . Yii::app()->user->id,
                ));
            }
            
            $questionsCriteria->order = 't.id DESC';
            
            $questions = Question::model()->findAll($questionsCriteria);
           
             
            $questionsDataProvider = new CArrayDataProvider($questions, array(
                    'pagination'    =>  array(
                            'pageSize'=>20,
                        ),
                ));
            
//            $questionsDataProvider = new CActiveDataProvider('Question', array(
//                    'criteria'  => $questionsCriteria,
//                    'pagination'    =>  array(
//                            'pageSize'=>20,
//                        ),
//                ));
            $this->render('profile', array(
                'questionsDataProvider'     =>  $questionsDataProvider,
                'user'                      =>  $user,
            ));
        }


        // creating a new user by registration form 
        public function actionCreate()
	{
            $this->layout = '//frontend/short';
            $model=new User;
            $yuristSettings = new YuristSettings;
            $model->setScenario('register');
            
            if(!$model->role) {
                $model->role = User::ROLE_CLIENT;
            }
            
            $rolesNames = array(
                User::ROLE_CLIENT   =>  'Пользователь',
                User::ROLE_JURIST   =>  'Юрист',
            );
            
            if(isset($_POST['User'])) {
                $model->attributes=$_POST['User'];
                
                // можно зарегистрироваться с ролью Юрист или Пользователь
                // все, кто не юристы - пользователи
                if($model->role != User::ROLE_JURIST) {
                    $model->role = User::ROLE_CLIENT;
                }
                
                $model->confirm_code = md5($model->email.mt_rand(100000,999999));
                $model->password = $model->password2 = User::generatePassword(6);
                
                if($model->save()) {	
                    if($model->sendConfirmation($newPassword)) {
                        $this->redirect(array('ConfirmationSent'));
                    } else {
                        throw new CHttpException(500,'Что-то пошло не так. Мы не смогли отправить Вам письмо с подтверждением регистрации на сайте. Не беспокойтесь, с вашим аккаунтом все в порядке, просто письмо с подтверждением придет немного позже.');
                    }
                }
                
            }
            
            $townsArray = Town::getTownsIdsNames();
            
            $this->render('create',array(
                'model'             =>  $model,
                'yuristSettings'    =>  $yuristSettings,
                'townsArray'        =>  $townsArray,
                'rolesNames'        =>  $rolesNames,
            ));
        }
        
        
        // страница редактирования пользователя 
        public function actionUpdate($id)
	{
            $this->layout = '//frontend/short';
            $model= User::model()->findByPk($id);
                        
            if(!$model) {
                throw new CHttpException(404,'Пользователь не найден');
            }
            
            if($model->id != Yii::app()->user->id && Yii::app()->user->role != User::ROLE_ROOT) {
                throw new CHttpException(403,'Ошибка доступа: вы не можете редактировать чужой профиль');
            }
            
            $model->setScenario('update');
            
            // модель для работы со сканом
            $userFile = new UserFile;
            
            if($model->role == User::ROLE_JURIST || $model->role == User::ROLE_OPERATOR) {
                if($model->settings) {
                    $yuristSettings = $model->settings;
                } else {
                    $yuristSettings = new YuristSettings();
                    $yuristSettings->yuristId = $model->id;
                }
                
            } else {
                $yuristSettings = new YuristSettings();
            }
            
            $rolesNames = array(
                User::ROLE_CLIENT   =>  'Пользователь',
                User::ROLE_JURIST   =>  'Юрист',
                User::ROLE_OPERATOR =>  'Оператор',
            );
            
            if(isset($_POST['User'])) {
                // присваивание атрибутов пользователя
                $model->attributes=$_POST['User'];
                $yuristSettings->attributes = $_POST['YuristSettings'];

                // если мы редактировали юриста
                if(isset($_POST['YuristSettings'])) {
                    $yuristSettings->attributes = $_POST['YuristSettings'];

                    $yuristSettings->save();

                }
                
                // загрузка аватарки и скана
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
                    
                    $scan = CUploadedFile::getInstance($userFile,'userFile');
                    if($scan && $scan->getError()==0) // если файл нормально загрузился
                    {
                        $scanFileName = md5($scan->getName().$scan->getSize().mt_rand(10000,100000)).".".$scan->getExtensionName();
                        Yii::app()->ih
                            ->load($scan->tempName)
                            ->save(Yii::getPathOfAlias('webroot') . UserFile::USER_FILES_FOLDER . '/' . $scanFileName);
                        // CustomFuncs::printr($scan);
                        // exit;
                        
                        $userFile->userId = Yii::app()->user->id;
                        $userFile->name = $scanFileName;
                        $userFile->type = $yuristSettings->status;
                        
                        if(!$userFile->save()){
                            echo "Не удалось сохранить скан";
                            CustomFuncs::printr($userFile->errors);
                            exit;
                        }
                        
                    }

                }
                
                if($model->save()) {	
                    if($model->save() && $yuristSettings->hasErrors() == false){
                        $this->redirect(array('profile'));
                    } else {
                        CustomFuncs::printr($model->errors);
                        CustomFuncs::printr($yuristSettings->errors);
                        throw new CHttpException(500,'Что-то пошло не так. Не удалось сохранить данные профиля.');
                    }
                }
                
            } else {
                $model->password = '';
            }
            
            $townsArray = Town::getTownsIdsNames();
            
            $this->render('update',array(
                'model'             =>  $model,
                'yuristSettings'    =>  $yuristSettings,
                'userFile'          =>  $userFile,
                'townsArray'        =>  $townsArray,
                'rolesNames'        =>  $rolesNames,
            ));
        }
        
        
        public function actionChangePassword($id)
        {
            $this->layout = '//frontend/short';
            // если пользователь не админ, он может менять пароль только у себя
            if(Yii::app()->user->id !== $id && !Yii::app()->user->checkAccess(User::ROLE_ROOT)) {
                throw new CHttpException(403, 'У вас нет прав менять пароль другого пользователя'); 
            }
            
            $model = User::model()->findByPk($id);
            $model->password = '';
            $model->setScenario('changePassword');
            
            // если была заполнена форма
            if($_POST['User']) {
                $model->attributes = $_POST['User'];
//                CustomFuncs::printr($model);exit;
                if($model->validate()){
                    // если данные пользователя прошли проверку (пароль не слишком короткий)
                    // шифруем пароль перед сохранением в базу
                    $model->password = User::hashPassword($model->password);
                    $model->password2 = $model->password;
                    if($model->save()){
                        
                        $this->redirect(array('profile'));
                    }
                }
            }
            $this->render('changePassword',array(
			'model'             =>  $model,
                ));
        }

        
        
        public function actionConfirmationSent()
        {
           $this->layout = '//frontend/short';
           $this->render('confirmationSent');  
        }
        
        public function actionConfirm()
        {
           $this->layout = '//frontend/short';
           
           $email = CHtml::encode($_GET['email']);
           $code = CHtml::encode($_GET['code']);
           
           $criteria = new CDbCriteria;
           $criteria->addColumnCondition(array('email'=>$email));
           $criteria->addColumnCondition(array('confirm_code'=>$code));
           $criteria->limit = 1;
                      
           //находим пользователя с данным мейлом и кодом подтверждения
           $user = User::model()->find($criteria);
           
           if(!empty($user))
           {
              $user->setScenario('confirm');
              if($user->active==0) {
                $user->activate();
                $user->registerDate = date('Y-m-d');
                $newPassword = $user->password = $user->password2 = $user->generatePassword();
                $user->publishNewQuestions();
              }
                            
              if($user->save()) {
                  // после активации и сохранения пользователя, отправим ему на почту временный пароль
                  if($newPassword) {
                    $user->sendNewPassword($newPassword);
                  }
                  
                  // логиним пользователя
                  $loginModel = new LoginForm;
                  $loginModel->email = $email;
                  $loginModel->password = $newPassword;
                  
                  if($loginModel->login()) {
                      // если залогинили, находим последний вопрос и перенаправляем на страницу вопроса
                      $question = Yii::app()->db->createCommand()
                              ->select('id')
                              ->from('{{question}}')
                              ->where('authorId=' . $user->id)
                              ->limit(1)
                              ->queryRow();
                      if($question) {
                          $link = Yii::app()->createUrl('question/view', array('id'=>$question['id'])) . '?justPublished=1';
                          $this->redirect($link);
                      } else {
                          $this->render('activationSuccess', array(
                            'user'          =>  $user, 
                            'loginModel'    =>  $loginModel,
                          ));
                      }
                  }
                  /*
                   * 
                   */
              } else {
                  if(!empty($user->errors)) {
                      print "<pre>";
                      print_r($user->errors);
                      print "</pre>";
                  }
                  
                  $this->render('activationFailed', array('message'=>'Ошибка - не удалось активировать аккаунт из-за ошибки в программе.<br />
                      Обратитесь, пожалуйста, к администратору сайта через E-mail info@100yuristov.com'));
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
        
}