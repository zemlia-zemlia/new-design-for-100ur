<?php

class UserStatusRequestController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//frontend/short';

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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','view', 'create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
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
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new UserStatusRequest;
                
                // модель для работы со сканом
                $userFile = new UserFile;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UserStatusRequest']))
		{
                    $model->attributes=$_POST['UserStatusRequest'];
                    $model->yuristId = Yii::app()->user->id;
                    
                    $model->validateRequest();
                    
//                    CustomFuncs::printr($model->errors);exit;
                    
                    // загрузка скана
                    if(!empty($_FILES) && !$model->errors)
                    {

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
                            $userFile->type = $model->status;

                            if(!$userFile->save()){
                                echo "Не удалось сохранить скан";
                                CustomFuncs::printr($userFile->errors);
                                exit;
                            } else {
                                // после сохранения файла сохраним ссылку на него в объекте запроса
                                $model->fileId = $userFile->id;
                            }

                        }

                    }
                
                    if(!$model->errors && $model->save()) {
                        $this->redirect(array('/user'));
                    }
		}
                
                $currentUser = User::model()->with('settings')->findByPk(Yii::app()->user->id);

		$this->render('create',array(
			'model'         =>  $model,
                        'userFile'      =>  $userFile,
                        'currentUser'   =>  $currentUser,
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UserStatusRequest']))
		{
			$model->attributes=$_POST['UserStatusRequest'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
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
		$dataProvider=new CActiveDataProvider('UserStatusRequest');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new UserStatusRequest('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UserStatusRequest']))
			$model->attributes=$_GET['UserStatusRequest'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return UserStatusRequest the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=UserStatusRequest::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param UserStatusRequest $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-status-request-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}