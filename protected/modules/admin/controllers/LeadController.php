<?php

class LeadController extends Controller
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
                        array('allow', // 
                                'actions'=>array('index', 'view', 'update', 'delete', 'sendLeads', 'toQuestion', 'generate', 'dispatch'),
                                'users'=>array('@'),
                                'expression'=>'Yii::app()->user->checkAccess(' . User::ROLE_MANAGER . ') || Yii::app()->user->checkAccess(' . User::ROLE_SECRETARY . ')',
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
		$model=new Lead;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Lead']))
		{
			$model->attributes=$_POST['Lead'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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

		if(isset($_POST['Lead']))
		{
			$model->attributes=$_POST['Lead'];
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

		$this->redirect(array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Lead', array(
                    'criteria'  =>  array(
                        'order' =>  'id DESC',
                    ),
                    'pagination'    =>  array(
                        'pageSize'=>50,
                    ),
                ));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	
        
        public function actionToQuestion($id)
        {
            $contact = $this->loadModel($id);
            
            $question = new Question();
            $question->townId = $contact->townId;
            $question->authorName = $contact->name;
            $question->questionText = $contact->question;
            $question->status = Question::STATUS_PUBLISHED;
            
            if($question->save()) {
                $contact->questionId = $question->id;
                if($contact->save()) {
                    echo $contact->id;
                } else {
                    CustomFuncs::printr($contact->errors);
                    //throw new CHttpException(500,'Не удалось перевести лид в вопрос');
                }
            } else {
                CustomFuncs::printr($question->errors);
            }
            
            
        }
        
        // распределяет лиды: в CRM или в лид-сервисы
        public function actionSendLeads()
        {

            // найдем все лиды, не отправленные ни в офис, ни в лид-сервисы
            $leads = Lead::model()->findAllByAttributes(array('leadStatus'=>  Lead::LEAD_STATUS_DEFAULT));

            foreach($leads as $lead) {
                $lead->sendLead();
            }
            
            $this->redirect(array('/admin/lead/index', 'leadsSent'=>1));

        }
        
        // генерирует демо лиды
        public function actionGenerate()
        {
            $limit = 10;
            $towns = array(99,563, 10, 598);
            $sourceId = 3;
            $question = 'Текст вопроса';
            $status = Lead::LEAD_STATUS_DEFAULT;
            $type = Lead::TYPE_QUESTION;
            $names = array('Август', 'Августин', 'Аврор', 'Агап', 'Адам', 'Аксён', 'Алевтин', 'Александр', 'Алексей', 'Алексий', 'Альберт', 'Анастасий', 'Анатолий', 'Анвар', 'Андрей', 'Андрон', 'Анисим', 'Антип', 'Антон', 'Антонин', 'Аристарх', 'Аркадий', 'Арсений', 'Артамон', 'Артём', 'Артемий', 'Артур', 'Архип', 'Аскольд', 'Афанасий', 'Афиноген');
            
            for($i=0; $i<$limit; $i++) {
                $lead = new Lead;
                
                $properties = array(
                    'sourceId'  =>  $sourceId,
                    'question'  =>  $question,
                    'name'      =>  $names[mt_rand(0,sizeof($names)-1)],
                    'status'    =>  $status,
                    'type'      =>  $type,
                    'townId'    =>  $towns[mt_rand(0,sizeof($towns)-1)],
                    'active'    =>  1,
                    'phone'     =>  mt_rand(1000000000, 9999999999),
                    'email'     =>  'bot_' . mt_rand(100000, 999999) . '@100yuristov.com',
                    
                );
                
                $lead->attributes = $properties;
                
                if($lead->save()) {
                    echo "Лид " . $lead->id . ' сохранен<br />';
                } else {
                    CustomFuncs::printr($lead->errors);
                }
            }
            
        }
        
        
        public function actionDispatch()
        {
            $criteria = new CDbCriteria;
            
            $criteria->addColumnCondition(array('leadStatus'=>Lead::LEAD_STATUS_DEFAULT));
            $criteria->addColumnCondition(array('question_date>'=>date('Y-m-d')));
            $criteria->with = array('town', 'town.region');
            
            // сколько лидов обрабатывать за раз
            $criteria->limit = 50;
            
            $leads = Lead::model()->findAll($criteria);
            
            foreach($leads as $lead) {
                echo $lead->id . " " . $lead->name . ', город: ' . $lead->town->name . ', регион:' . $lead->town->region->name;
                echo "<p>Подходящие кампании:</p>";
                echo $campaignId = Campaign::getCampaignsForLead($lead->id);
                
                if(!$campaignId) {
                    // если для лида нет ни одной кампании, идем к следующему лиду
                    continue;
                }
                
                if($lead->sendToCampaign($campaignId)) {
                    echo "Лид отправлен в кампанию";
                } else {
                    echo "С этим лидом что-то пошло не так";
                }
                echo "<hr />";
            }
            
            
        }

        
        /**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Lead the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Lead::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Lead $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='lead-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
