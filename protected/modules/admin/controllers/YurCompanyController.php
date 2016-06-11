<?php

class YurCompanyController extends Controller
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
				'actions'=>array('index','view','RemovePhoto'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
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
		$model=new YurCompany;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['YurCompany']))
		{
                    $model->attributes=$_POST['YurCompany'];
                    
                    // загрузка логотипа
                    if(!empty($_FILES)) {
                            $file = CUploadedFile::getInstance($model,'photoFile');
                            if($file && $file->getError()==0) // если файл нормально загрузился
                            {
                                // определяем имя файла для хранения на сервере
                                $newFileName = md5($file->getName().$file->getSize().mt_rand(10000,100000)).".".$file->getExtensionName();
                                Yii::app()->ih
                                ->load($file->tempName)
                                ->resize(1000, 300, true)
                                ->save(Yii::getPathOfAlias('webroot') . YurCompany::COMPANY_PHOTO_PATH . '/' . $newFileName)
                                ->reload()
                                ->resizeCanvas(210,210, array(255,255,255))
                                ->save(Yii::getPathOfAlias('webroot') . YurCompany::COMPANY_PHOTO_PATH . YurCompany::COMPANY_PHOTO_THUMB_FOLDER . '/' . $newFileName);

                                $model->logo = $newFileName;
                            }
                        }
                    $model->phone1 = preg_replace('/([^0-9])/i', '', $model->phone1);
                    $model->phone2 = preg_replace('/([^0-9])/i', '', $model->phone2);
                    $model->phone3 = preg_replace('/([^0-9])/i', '', $model->phone3);
                    
                    $model->authorId = Yii::app()->user->id;
                    
                    if($model->save()){
                        $this->redirect(array('view','id'=>$model->id));
                    }
		}

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

		if(isset($_POST['YurCompany']))
		{
                    $model->attributes=$_POST['YurCompany'];
                        
                    // загрузка логотипа
                    if(!empty($_FILES)) {
                            $file = CUploadedFile::getInstance($model,'photoFile');
                            if($file && $file->getError()==0) // если файл нормально загрузился
                            {
                                // определяем имя файла для хранения на сервере
                                $newFileName = md5($file->getName().$file->getSize().mt_rand(10000,100000)).".".$file->getExtensionName();
                                Yii::app()->ih
                                ->load($file->tempName)
                                ->resize(1000, 300, true)
                                ->save(Yii::getPathOfAlias('webroot') . YurCompany::COMPANY_PHOTO_PATH . '/' . $newFileName)
                                ->reload()
                                ->adaptiveThumb(210,210, array(255,255,255))
                                ->save(Yii::getPathOfAlias('webroot') . YurCompany::COMPANY_PHOTO_PATH . YurCompany::COMPANY_PHOTO_THUMB_FOLDER . '/' . $newFileName);

                                $model->logo = $newFileName;
                            }
                        }
                    $model->phone1 = preg_replace('/([^0-9])/i', '', $model->phone1);
                    $model->phone2 = preg_replace('/([^0-9])/i', '', $model->phone2);
                    $model->phone3 = preg_replace('/([^0-9])/i', '', $model->phone3);
                    
                    if($model->save()) {
                        $this->redirect(array('view','id'=>$model->id));
                    }
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
                $this->redirect(array('/admin/yurCompany'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('YurCompany',array(
                    'criteria'=>array(
                        'order'=>'id DESC',
                    ),
                    'pagination'=>array(
                        'pageSize'=>50,
                    ),
                    ));
                
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new YurCompany('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['YurCompany']))
			$model->attributes=$_GET['YurCompany'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return YurCompany the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=YurCompany::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param YurCompany $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='yur-company-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        
        public function actionRemovePhoto($id)
        {
            $model = YurCompany::model()->findByPk($id);
            if($model->logo != '') {
                @unlink($_SERVER['DOCUMENT_ROOT'] . YurCompany::COMPANY_PHOTO_PATH . '/' . $model->logo);
                @unlink($_SERVER['DOCUMENT_ROOT'] . YurCompany::COMPANY_PHOTO_PATH . '/' . YurCompany::COMPANY_PHOTO_THUMB_FOLDER . '/' . $model->logo);
            }
            //exit;
            $model->logo = '';
            if($model->save()) {
                $this->redirect(array('/admin/yurCompany/view', 'id'=>$model->id));
            } else {
                throw new CHttpException(500,'Не удалось сохранить компанию после удаления фото');
            }
            
        }
}
