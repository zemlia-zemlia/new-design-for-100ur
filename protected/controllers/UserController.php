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
				'actions'=>array('index', 'view','create','confirm','confirmationSent', 'restorePassword'),
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
        
        // creating a new user by registration form 
        public function actionCreate()
	{
            $model=new User;
            $yuristSettings = new YuristSettings;
            $model->setScenario('register');
            
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
              }
                            
              if($user->save()) {
                  if(Yii::app()->user->isGuest)
                  {
                    $this->render('activationSuccess');
                  } else {
                      $this->redirect('/user');
                  }
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
        
}