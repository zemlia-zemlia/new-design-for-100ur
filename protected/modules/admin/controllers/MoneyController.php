<?php

class MoneyController extends Controller
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
			
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index', 'view', 'create', 'update', 'delete'),
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
		$model=new Money;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Money']))
		{
                    $model->attributes=$_POST['Money'];
                    $model->datetime = CustomFuncs::invertDate($model->datetime);
                    if($model->save()) {
                        $this->redirect(array('view','id'=>$model->id));
                    } else {
                        $model->datetime = CustomFuncs::invertDate($model->datetime);
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

		if(isset($_POST['Money']))
		{
                    $model->attributes=$_POST['Money'];
                    $model->datetime = CustomFuncs::invertDate($model->datetime);
                    if($model->save()) {
                        $this->redirect(array('view','id'=>$model->id));
                    }
                    
		}
                $model->datetime = CustomFuncs::invertDate($model->datetime);
		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		
                // рассчитаем баланс каждого счета исходя из транзакций по нему
                $balances = array();
                
                $balanceRows = Yii::app()->db->createCommand()
                        ->select('type, value, accountId')
                        ->from('{{money}}')
                        ->queryAll();
                foreach($balanceRows as $row) {
                    if($row['type'] == Money::TYPE_INCOME) {
                        $balances[$row['accountId']] += $row['value'];
                    } else {
                        $balances[$row['accountId']] -= $row['value'];
                    }
                }
                
                $accounts = Money::getAccountsArray();
                
                $dataProvider=new CActiveDataProvider('Money', array('criteria'=>array(
                    'order'=>'datetime DESC, id DESC',
                ),
                    'pagination'    =>  array(
                        'pageSize'  =>  20,
                    ),
                ));
		$this->render('index',array(
			'dataProvider'  =>  $dataProvider,
                        'balances'      =>  $balances,
                        'accounts'      =>  $accounts,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Money('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Money']))
			$model->attributes=$_GET['Money'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Money the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Money::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Money $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='money-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
