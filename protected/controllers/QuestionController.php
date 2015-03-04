<?php

class QuestionController extends Controller
{

	public $layout='//frontend/main';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
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
                        'actions'=>array('index', 'view', 'create', 'thankYou','rss'),
                        'users'=>array('*'),
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
            $model = Question::model()->findByPk($id);
            if(!$model) {
                throw new CHttpException(404,'Вопрос не найден');
            }
            $criteria = new CDbCriteria;
            $criteria->order = 't.id DESC';
            $criteria->addColumnCondition(array('questionId'=>$model->id));
            
            $answersDataProvider = new CActiveDataProvider('Answer', array(
                'criteria'=>$criteria,        
                'pagination'=>array(
                            'pageSize'=>20,
                        ),
            ));
            
            // похожие вопросы, из той же категории, максимум 3
            $similarCriteria = new CDbCriteria;
            $similarCriteria->limit=3;
            //$similarCriteria->order = "RAND()";
            $similarCriteria->with = array(
                'answersCount'  =>  array(
                    'having' =>  's>0',
                ),
                );
            
            $similarCriteria->addColumnCondition(array(
                'categoryId'    =>  (int)$model->categoryId,
                'status'        =>  Question::STATUS_PUBLISHED,
                'id!'           =>  $model->id,
            ));
            
            $similarQuestions = Question::model()->cache(3600)->findAll($similarCriteria);
                    
            // модель для формы вопроса
            $questionModel = new Question();
            
            $this->render('view',array(
                    'model'                 =>  $model,
                    'answersDataProvider'   =>  $answersDataProvider,
                    'questionModel'         =>  $questionModel,
                    'similarQuestions'      =>  $similarQuestions,
            ));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$lead = new Lead();
                $question = new Question();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Question']))
		{
			$question->attributes = $_POST['Question'];
                        
                        $lead->name = $question->authorName;
                        $lead->question = $question->questionText;
                        $lead->phone = $question->phone;
                        $lead->townId = $question->townId;
                        $lead->sourceId = 3; // Lidlaw
                        $lead->leadStatus = Lead::LEAD_STATUS_DEFAULT; // по умолчанию лид никуда не отправляем
			
                        if($lead->save()) {
                                $lead->sendByEmail();
				$this->redirect(array('thankYou'));
                        }
		}

                // $allCategories - массив, ключи которого - id категорий, значения - названия
                $allCategories = QuestionCategory::getCategoriesIdsNames();
                if(isset($_GET['categoryId'])){
                    $categoryId = (int)$_GET['categoryId'];
                } else {
                    $categoryId = null;
                }
                
                $townsArray = Town::getTownsIdsNames();
                
		$this->render('create',array(
			'model'         =>  $question,
                        'allCategories' =>  $allCategories,
                        'categoryId'    =>  $categoryId,
                        'townsArray'    =>  $townsArray,
		));
	}

	public function actionThankYou()
        {
            $this->render('thankYou');
        }

        /**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		
                $criteria = new CDbCriteria;
                $criteria->order = 't.id desc';
                $criteria->addColumnCondition(array('t.status' =>  Question::STATUS_PUBLISHED));
                $criteria->with = array('category', 'town', 'answersCount');
                
                if(isset($_GET['status'])) {
                    $status = (int)$_GET['status'];
                    $criteria->addColumnCondition(array('status'=>$status));
                } else {
                    $status = null;
                }
                
                $dataProvider = new CActiveDataProvider('Question', array(
                    'criteria'=>$criteria,        
                    'pagination'=>array(
                                'pageSize'=>20,
                            ),
                ));
		$this->render('index',array(
			'dataProvider'  =>  $dataProvider,
                        'status'        =>  $status,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Question('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Question']))
			$model->attributes=$_GET['Question'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Question the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Question::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Question $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='question-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        // generates RSS 2.0 feed with active trips
        public function actionRss()
        {
            $criteria = new CDbCriteria;
            $criteria->addColumnCondition(array('t.status' =>  Question::STATUS_PUBLISHED));
            $criteria->order = "t.id DESC";
            $criteria->with = array('answersCount');
            $questions = Question::model()->cache(600)->findAll($criteria);
                        
            Yii::import('ext.feed.*');
            // RSS 2.0 is the default type
            $feed = new EFeed();

            $feed->title= Yii::app()->name;
            $feed->description = 'Вопросы квалифицированным юристам';


            $feed->addChannelTag('language', 'ru-ru');
            $feed->addChannelTag('pubDate', date(DATE_RSS, time()));
            $feed->addChannelTag('link', 'http://www.100yuristov.com/question/rss' );

            // * self reference
            //$feed->addChannelTag('atom:link','http://www.100yuristov.com/question/rss');

            foreach($questions as $question)
            {
                $item = $feed->createNewItem();

                
                if($question->answersCount) {
                    $item->title = CHtml::encode($question->title) . ' (' . $question->answersCount . ' ' . CustomFuncs::numForms($question->answersCount, 'ответ', "ответа", "ответов") . ")";
                } else {
                    $item->title = CHtml::encode($question->title);
                }
                
                $item->link = "http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('question/view',array('id'=>$question->id));
                $item->date = time();

                $item->description = CHtml::encode($question->questionText);

                $feed->addItem($item);
            }
            $feed->generateFeed();
            Yii::app()->end();
        }
}
