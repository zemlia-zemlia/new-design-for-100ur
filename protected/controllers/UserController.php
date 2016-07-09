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
            $questionsCriteria = new CDbCriteria;
            $questionsCriteria->addColumnCondition(array('t.authorId'=>Yii::app()->user->id));
            $questionsCriteria->order = 't.id DESC';
            $questionsCriteria->with = 'answers';
            
            $questionsDataProvider = new CActiveDataProvider('Question', array(
                    'criteria'  => $questionsCriteria,
                    'pagination'    =>  array(
                            'pageSize'=>20,
                        ),
                ));
            $this->render('profile', array(
                'questionsDataProvider'     =>    $questionsDataProvider,
            ));
        }


        // creating a new user by registration form 
        public function actionCreate()
	{
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
           $criteria->limit = 1;
           
           //находим пользователя с данным мейлом и кодом подтверждения
           $user = User::model()->find($criteria);
           
           if(!empty($user))
           {
              $user->setScenario('confirm');
              if($user->active==0) {
                $user->activate();
                $newPassword = $user->password = $user->password2 = $user->generatePassword();
                $user->publishNewQuestions();
              }
                            
              if($user->save()) {
                  // после активации и сохранения пользователя, отправим ему на почту временный пароль
                  $user->sendNewPassword($newPassword);

                  $this->render('activationSuccess', array('user'=>$user));

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