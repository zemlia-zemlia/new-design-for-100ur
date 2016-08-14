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
                array('allow', // allow all users 
                        'actions'=>array('index', 'view', 'create', 'thankYou','rss', 'call', 'weCallYou', 'docsRequested', 'docs'),
                        'users'=>array('*'),
                ),
                array('allow', // allow authenticated user to perform 'search'
                        'actions'=>array('search'),
                        'users'=>array('@'),
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
            
            $answerModel = new Answer();

            if(isset($_POST['Answer'])) {
                // отправлен ответ, сохраним его
                $answerModel->attributes = $_POST['Answer'];
                $answerModel->authorId = Yii::app()->user->id;
                $answerModel->questionId = $model->id;
                
                if($answerModel->save()){
                    $this->redirect(array('/question/view', 'id'=>$model->id));
                }
                
            }
            
            
            $criteria = new CDbCriteria;
            $criteria->order = 't.id ASC';
            $criteria->addColumnCondition(array('questionId'=>$model->id));
            
            $answersDataProvider = new CActiveDataProvider('Answer', array(
                'criteria'=>$criteria,        
                'pagination'=>array(
                            'pageSize'=>20,
                        ),
            ));
            
            $similarCriteria = new CDbCriteria;
            $similarCriteria->limit=3;
            $similarCriteria->order = "RAND()";
            /*$similarCriteria->with = array(
                    'answersCount'  =>  array(
                        'having' =>  's>0',
                    ),
                );*/
            
            $similarCriteria->addColumnCondition(array(
                't.status'        =>  Question::STATUS_PUBLISHED,
                't.id!'           =>  $model->id,
            ));
            
            $similarQuestions = Question::model()->findAll($similarCriteria);
            //CustomFuncs::printr($similarQuestions); 
            
            $similarDataProvider = new CArrayDataProvider($similarQuestions, array(
                'pagination'    =>  false,
            ));
                    
            // модель для формы вопроса
            $newQuestionModel = new Question();
                        
            $this->render('view',array(
                    'model'                 =>  $model,
                    'answersDataProvider'   =>  $answersDataProvider,
                    'newQuestionModel'      =>  $newQuestionModel,
                    'similarDataProvider'   =>  $similarDataProvider,
                    'answerModel'           =>  $answerModel,
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
                $question->setScenario('create');
                
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Question']))
		{
			$question->attributes = $_POST['Question'];
                        $question->phone = preg_replace('/([^0-9])/i', '', $question->phone);
                        $question->validate();
                        
                        $lead->name = $question->authorName;
                        $lead->question = $question->questionText;
                        $lead->phone = $question->phone;
                        $lead->email = $question->email;
                        $lead->townId = $question->townId;
                        $lead->sourceId = 3; // Lidlaw
                        $lead->leadStatus = Lead::LEAD_STATUS_DEFAULT; // по умолчанию лид никуда не отправляем
			//CustomFuncs::printr($lead);exit;
                        
                        if($lead->save()) {
                                $question->status = Question::STATUS_NEW;
                                
                                // проверим, есть ли в базе пользователь с таким мейлом
                                $findUserResult = Yii::app()->db->createCommand()
                                        ->select('id')
                                        ->from("{{user}}")
                                        ->where("email=:email AND email!=''", array(":email"=>$lead->email))
                                        ->limit(1)
                                        ->queryRow();
                                if($findUserResult) {
                                    // если есть, то запишем id этого пользователя в авторы вопроса
                                    $question->authorId = $findUserResult['id'];
                                } else {
                                    // если пользователь не найден, при создании вопроса создадим пользователя
                                    $author = new User;
                                    $author->role = User::ROLE_CLIENT;
                                    $author->phone = $question->phone;
                                    $author->email = $question->email;
                                    $author->townId = $question->townId;
                                    $author->name = $question->authorName;
                                    $author->password = $author->password2 = $author->generatePassword();
                                    $author->confirm_code = md5($model->email.mt_rand(100000,999999));
                                        
                                    if($author->save()) {
                                        $question->authorId = $author->id;
                                        $author->sendConfirmation();
                                    } else {
                                        CustomFuncs::printr($author->errors);exit;
                                    }
                                }
                                
                                
                                $question->save();
				$this->redirect(array('thankYou'));
                        } else {
                            //CustomFuncs::printr($lead->errors);
                            //throw new CHttpException(400,'Что-то пошло не так. Ваш вопрос не удалось отправить.');
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
            if(isset($_GET['status'])) {
                    $status = (int)$_GET['status'];
                } else {
                    $statusCondition = 'status = ' . Question::STATUS_PUBLISHED . ' OR status=' . Question::STATUS_CHECK;
                }
            
            if(!$categoriesRows = Yii::app()->cache->get('categoriesCloud')) {    
                $categoriesRows = Yii::app()->db->createCommand()
                        ->select("c.id, c.name, c.alias, COUNT(*) counter")
                        ->from("{{question}} q")
                        ->leftJoin("{{question2category}} q2c", "q2c.qId = q.id")
                        ->leftJoin("{{questionCategory}} c", "c.id = q2c.cId")
                        ->where($statusCondition . " AND c.id IS NOT NULL")
                        ->group("c.id")
                        ->order('c.name')
                        ->queryAll();
                Yii::app()->cache->set('categoriesCloud', $categoriesRows, 3600);
            }
            
            $categoriesArray = array();
            $counterMax = 0; // максимальное количество вопросов в категории
            $counterMin = 0; // минимальное количество вопросов в категории
            
            // найдем минимальное и максимальное количество вопросов
            foreach($categoriesRows as $row) {
                if($row['counter']>$counterMax) {
                    $counterMax = $row['counter'];
                }
                
                if($counterMin == 0) {
                    $counterMin = $row['counter']; // при первом цикле присвоим минимуму значение первого счетчика
                }
                
                if($counterMin != 0 && $row['counter']<$counterMin) {
                    $counterMin = $row['counter'];
                }
            }
                        
            foreach($categoriesRows as $row) {
                $categoriesArray[$row['id']]['name'] = $row['name'];
                $categoriesArray[$row['id']]['alias'] = $row['alias'];
                $categoriesArray[$row['id']]['counter'] = $row['counter'];
            }
            
            $this->render('index',array(
			'categoriesArray'   =>  $categoriesArray,
                        'status'            =>  $status,
                        'counterMin'        =>  $counterMin,
                        'counterMax'        =>  $counterMax,
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
        
        public function actionSearch()
        {
            $this->layout = '//frontend/short';
            
            // модель для формы поиска по вопросам
            $searchModel = new QuestionSearch();
            
            // лимит на количество найденных вопросов
            $searchModel->limit = 100;
//            $searchModel->today = true;
//            $searchModel->townId = 598;
//            $searchModel->noAnswers = true;
            
            
            $searchModel->attributes = $_GET['QuestionSearch'];

            if($searchModel->townId) {
                $searchModel->townName = Town::getName($searchModel->townId);
            }
            $questions = $searchModel->search();
            $questionDataProvider = new CArrayDataProvider($questions, array(
                'pagination'    =>  array(
                    'pageSize'  =>  20,
                ),
            ));

            
            
            
            $this->render('search',array(
                    'searchModel'   =>  $searchModel,
                    'dataProvider'  =>  $questionDataProvider,
		));
        }
        
        
        public function actionCall()
        {
            $this->layout = "//frontend/short";
            
            $lead = new Lead();
            
            if(isset($_POST['Lead'])) {
                $lead->attributes = $_POST['Lead'];
                $lead->phone = preg_replace('/([^0-9])/i', '', $lead->phone);
                $lead->sourceId = 3;
                $lead->type = Lead::TYPE_CALL;
                
                if($lead->validate()) {
                    $lead->question = CHtml::encode('Нужна консультация юриста. Перезвоните мне. ' . $lead->question);
                
                    if($lead->save()) {
                        $this->redirect(array('weCallYou'));
                    }
                }      
                
            }
            
            $townsArray = Town::getTownsIdsNames();
            
            $this->render('call', array(
                'model'         =>  $lead,
                'townsArray'    =>  $townsArray,
            ));
            
        }
        
        public function actionWeCallYou()
        {
            $this->layout = "//frontend/short";
            $this->render('weCallYou');
        }
        
        
        public function actionDocs()
        {
            $this->layout = "//frontend/short";
            
            $lead = new Lead();
            
            if(isset($_POST['Lead'])) {
                $lead->attributes = $_POST['Lead'];
                $lead->phone = preg_replace('/([^0-9])/i', '', $lead->phone);
                $lead->sourceId = 3;
                $lead->type = Lead::TYPE_DOCS;
                
                if($lead->validate()) {
                    $docType = $_POST['question_hidden'];
                    $lead->question = CHtml::encode('Заявка на документ. ' . $docType . '. ' . $lead->question);

                    if($lead->save()) {
                        $this->redirect(array('docsRequested'));
                    }
                }      
                
            }
            
            $townsArray = Town::getTownsIdsNames();
            
            $this->render('docs', array(
                'model'         =>  $lead,
                'townsArray'    =>  $townsArray,
            ));
            
        }
        
        public function actionDocsRequested()
        {
            $this->layout = "//frontend/short";
            $this->render('docsRequested');
        }
}
