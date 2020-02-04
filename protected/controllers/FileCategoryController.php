<?php

class FileCategoryController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
    public $layout='//admin/main';

	/**
	 * @return array action filters
	 */
//	public function filters()
//	{
//		return array(
//			'accessControl', // perform access control for CRUD operations
//			'postOnly + delete', // we only allow deletion via POST request
//		);
//	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
//	public function accessRules()
//	{
//		return array(
//			array('allow',  // allow all users to perform 'index' and 'view' actions
//				'actions'=>array('index','view'),
//				'users'=>array('*'),
//			),
//			array('allow', // allow authenticated user to perform 'create' and 'update' actions
//				'actions'=>array('create','update'),
//				'users'=>array('@'),
//			),
//			array('allow', // allow admin user to perform 'admin' and 'delete' actions
//				'actions'=>array('admin','delete'),
//				'users'=>array('admin'),
//			),
//			array('deny',  // deny all users
//				'users'=>array('*'),
//			),
//		);
//	}

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
	public function actionCreate($id = 0)
	{
//        $category1=new Category;
//        $category1->title='Ford';
//        $category2=new Category;
//        $category2->title='Mercedes';
//        $category3=new Category;
//        $category3->title='Audi';
//        $root=Category::model()->findByPk(1);
//        $category1->appendTo($root);
//        $category2->insertAfter($category1);
//        $category3->insertBefore($category1);
//




		$model=new FileCategory;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FileCategory']))
		{
		    if ($id != 0) {
                $model->attributes = $_POST['FileCategory'];
                $root = FileCategory::model()->findByPk($id);
                $model->appendTo($root);
            }
            else {
                $model->attributes = $_POST['FileCategory'];

                $model->saveNode();
            }

            Yii::app()->user->setFlash('success', "Категория добавлена");
            return $this->redirect('/docs/index');
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

		if(isset($_POST['FileCategory']))
		{
			$model->attributes=$_POST['FileCategory'];

			if($model->saveNode())
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
		$this->loadModel($id)->deleteNode();

        Yii::app()->user->setFlash('success', "Категория удалена");
        return $this->redirect('/docs/index');

//		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
//		if(!isset($_GET['ajax']))
//			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
//        $root = new FileCategory();

//        $root->name ='еще233 корень';
////        $root->root = 0;
//        $root->saveNode();
//        $root = new FileCategory();
//        $root->name ='User Files';
//        $root->root = 0;
//        $root->saveNode();
//        $cat1 = FileCategory::model()->findByPk(19);
//
//        $node = FileCategory::model()->findByPk(12);
//        $cat1->moveAsFirst($node);

		$dataProvider = new CActiveDataProvider('FileCategory');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new FileCategory('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['FileCategory']))
			$model->attributes=$_GET['FileCategory'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return FileCategory the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=FileCategory::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param FileCategory $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='file-category-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
