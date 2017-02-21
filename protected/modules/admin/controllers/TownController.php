<?php

class TownController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//admin/main';

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
                        array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','create','update','delete', 'admin', 'RemovePhoto'),
				'users'=>array('*'),
                                'expression'=>'Yii::app()->user->checkAccess(' . User::ROLE_ROOT . ')',

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
		$model=new Town;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Town']))
		{
			$model->attributes=$_POST['Town'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
                
                // для работы визуального редактора подключим необходимую версию JQuery
                $scriptMap = Yii::app()->clientScript->scriptMap;
                $scriptMap['jquery.js'] = '/js/jquery-1.8.3.min.js'; 
                $scriptMap['jquery.min.js'] = '/js/jquery-1.8.3.min.js'; 
                Yii::app()->clientScript->scriptMap = $scriptMap;

		$this->render('create',array(
			'model'=>$model,
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

		if(isset($_POST['Town']))
		{
			$model->attributes=$_POST['Town'];
                        
                        if(!empty($_FILES)) {
                            $file = CUploadedFile::getInstance($model,'photoFile');

                            if($file && $file->getError()==0) // если файл нормально загрузился
                            {
                                // определяем имя файла для хранения на сервере
                                $newFileName = md5($file->getName().$file->getSize().mt_rand(10000,100000)).".".$file->getExtensionName();
                                Yii::app()->ih
                                ->load($file->tempName)
                                ->resize(1000, 300, true)
                                ->save(Yii::getPathOfAlias('webroot') . Town::TOWN_PHOTO_PATH . '/' . $newFileName)
                                ->reload()
                                ->adaptiveThumb(200,200, array(255,255,255))
                                ->save(Yii::getPathOfAlias('webroot') . Town::TOWN_PHOTO_PATH . Town::TOWN_PHOTO_THUMB_FOLDER . '/' . $newFileName);

                                $model->photo = $newFileName;
                                
                                
                            }

                        }
                    
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
                
                // для работы визуального редактора подключим необходимую версию JQuery
                $scriptMap = Yii::app()->clientScript->scriptMap;
                $scriptMap['jquery.js'] = '/js/jquery-1.8.3.min.js'; 
                $scriptMap['jquery.min.js'] = '/js/jquery-1.8.3.min.js'; 
                Yii::app()->clientScript->scriptMap = $scriptMap;

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
		$townsArray = Yii::app()->db->cache(3600)->createCommand()
                        ->order('counter DESC')
                        ->select('t.id, t.name, t.ocrug, COUNT(*) counter')
                        ->from('{{town}} t')
                        ->leftJoin('{{question}} q', 't.id = q.townId')
                        ->group('t.id')
                        ->where('q.status!=' . Question::STATUS_SPAM)
                        ->limit(50)
                        ->queryAll();
                
                
		$this->render('index',array(
			'townsArray'=>$townsArray,
		));
	}
        
        public function actionRemovePhoto($id)
        {
            $model = Town::model()->findByPk($id);
            if($model->photo != '') {
                //echo $_SERVER['DOCUMENT_ROOT'] . Town::TOWN_PHOTO_PATH . '/' . $model->photo;
                //echo $_SERVER['DOCUMENT_ROOT'] . Town::TOWN_PHOTO_PATH . '/' . Town::TOWN_PHOTO_THUMB_FOLDER . '/' . $model->photo;
                @unlink($_SERVER['DOCUMENT_ROOT'] . Town::TOWN_PHOTO_PATH . '/' . $model->photo);
                @unlink($_SERVER['DOCUMENT_ROOT'] . Town::TOWN_PHOTO_PATH . '/' . Town::TOWN_PHOTO_THUMB_FOLDER . '/' . $model->photo);
            }
            //exit;
            $model->photo = '';
            if($model->save()) {
                $this->redirect(array('town/view', 'id'=>$model->id));
            } else {
                throw new CHttpException(500,'Не удалось сохранить город после удаления фото');
            }
            
        }

                /**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Town('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Town']))
			$model->attributes=$_GET['Town'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Town the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Town::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Town $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='town-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
