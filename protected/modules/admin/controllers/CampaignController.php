<?php

class CampaignController extends Controller
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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'topup'),
                                'expression'=>'Yii::app()->user->checkAccess(' . User::ROLE_ROOT . ')',
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
            $model = Campaign::model()->with('transactions')->findByPk($id);
            $transactionsDataProvider = new CArrayDataProvider($model->transactions);
            
            $leadsStats = NULL;
            
            $leadSearchModel = new Lead100;
            $leadSearchModel->scenario = 'search';
            
            
            $leadSearchModel->attributes = $_GET['Lead100'];

            // по умолчанию собираем статистику по проданным лидам за последние 30 дней
            $dateTo = ($leadSearchModel->date2 != '') ? CustomFuncs::invertDate($leadSearchModel->date2) : date("Y-m-d");
            $dateFrom = ($leadSearchModel->date1 != '') ? CustomFuncs::invertDate($leadSearchModel->date1) : date("Y-m-d", time()-86400*30);
            $leadsStats = Lead100::getStatsByPeriod($dateFrom, $dateTo, null, $model->id);               
            
            
            $this->render('view',   array(
                'model'                     =>  $model,
                'transactionsDataProvider'  =>  $transactionsDataProvider,
                'leadsStats'                =>  $leadsStats,
                'searchModel'               =>  $leadSearchModel,
            ));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
            $model  =   new Campaign;

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if(isset($_POST['Campaign']))
            {
                $model->attributes=$_POST['Campaign'];
                if($model->save()) {
                    $this->redirect(array('view','id'=>$model->id));
                }
            }

            $buyersArray = User::getAllBuyersIdsNames();
            $regions = array('0'=>'Не выбран') + Region::getAllRegions();

            $this->render('create', array(
                    'model'         =>  $model,
                    'buyersArray'   =>  $buyersArray,
                    'regions'       =>  $regions,
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

            if(isset($_POST['Campaign']))
            {
                $oldActivity = $model->active; // запомним статус активности кампании
                
                $model->attributes = $_POST['Campaign'];
                $buyer = $model->buyer; // покупатель

                if($_POST['town'] == '') {
                    $model->townId = 0;
                }
                if($model->save()) {
                    
                    // если статус активности сменился с Модерация на другой, отправим уведомление
                    if($model->active != $oldActivity && $oldActivity == Campaign::ACTIVE_MODERATION) {
                        $buyer->sendBuyerNotification(User::BUYER_EVENT_CONFIRM, $model);
                    }
                    $this->redirect(array('view','id'=>$model->id));
                }
            }

            $buyersArray = User::getAllBuyersIdsNames();
            $regions = array('0'=>'Не выбран') + Region::getAllRegions();

            $this->render('update',array(
                    'model'         =>  $model,
                    'buyersArray'   =>  $buyersArray,
                    'regions'       =>  $regions,
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
            $criteria->order = 't.balance DESC';
            $criteria->condition = 'role=' . User::ROLE_BUYER;
            
            
            if(!isset($_GET['show_inactive'])) {
                $criteria->with = array(array('leadsCount', 'leadsTodayCount', 'campaigns', 'condition' => 'campaigns.active=1', 'order' => 'campaigns.active ASC'));

                $showInactive = false;
            } else {
                $criteria->with = array(array('campaigns', 'order' => 'campaigns.active ASC'));
                $showInactive = true;
            }
            
            //CustomFuncs::printr($criteria);
                        
            $dataProvider = new CActiveDataProvider('User', array(
                'criteria'  =>  $criteria,
                'pagination' => false,
            ));  
            
            $criteriaModeration = new CDbCriteria();
            $criteriaModeration->addCondition('active=' . Campaign::ACTIVE_MODERATION);
            $criteriaModeration->with = array('leadsCount', 'leadsTodayCount');
            
            $dataProviderModeration = new CActiveDataProvider('Campaign', array(
                    'criteria'  =>  $criteriaModeration,
                    'pagination'    =>  false,
                ));  
            
            
            $this->render('index',array(
			'dataProvider'              =>  $dataProvider,
                        'showInactive'              =>  $showInactive,
                        'dataProviderModeration'    =>  $dataProviderModeration,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Campaign('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Campaign']))
			$model->attributes=$_GET['Campaign'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Campaign the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Campaign::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Campaign $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='campaign-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionTopup()
        {
            $buyerId = isset($_POST['buyerId'])?(int)$_POST['buyerId']:0;
            $sum = isset($_POST['sum'])?(int)$_POST['sum']:0;
            
            if($sum<=0 || !$buyerId) {
                echo json_encode(array('code'=>400, 'message'=>'Error, not enough data'));
                exit;
            }
            
            $buyer = User::model()->findByPk($buyerId);
            $buyer->setScenario('balance');
            
            if(!$buyer) {
                echo json_encode(array('code'=>400, 'message'=>'Error, buyer not found'));
                exit;
            }
            
            $transaction = new TransactionCampaign;
            $transaction->sum = $sum;
            $transaction->buyerId = $buyerId;
            $transaction->description = "Пополнение баланса пользователя";
            
            $buyer->balance += $sum;
            
            if($transaction->save()) {
                if($buyer->save()) {
                    // если баланс пополнен, отправляем уведомление покупателю
                    $buyer->sendBuyerNotification(User::BUYER_EVENT_TOPUP);
                    echo json_encode(array('code'=>0,'id'=>$buyerId, 'balance'=>$buyer->balance));
                } else {
                    CustomFuncs::printr($buyer->errors);
                }
            } else {
                CustomFuncs::printr($transaction->errors);
            }
            
        }
}
