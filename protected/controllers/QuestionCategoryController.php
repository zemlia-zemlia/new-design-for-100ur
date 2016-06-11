<?php

class QuestionCategoryController extends Controller
{

	public $layout='//frontend/main';

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
				'actions'=>array('index','view', 'alias'),
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
            $model = QuestionCategory::model()->with('parent','children')->findByPk($id);
            
            if(!$model) {
                throw new CHttpException(404,'Категория не найдена');
            } 
            
            // если обратились по id , делаем редирект на ЧПУ
            $this->redirect(array('questionCategory/alias','name'=>CHtml::encode($model->alias)), true, 301);
            
            // код дальше НЕ будет выполнен!
            /*
            $questionsCriteria = new CdbCriteria;
            $questionsCriteria->addColumnCondition(array('categoryId'=>$model->id));
            $questionsCriteria->addColumnCondition(array('status' =>  Question::STATUS_PUBLISHED));
            $questionsCriteria->order = 'id DESC';
            
            $questionsDataProvider = new CActiveDataProvider('Question', array(
                    'criteria'      =>  $questionsCriteria,        
                    'pagination'    =>  array(
                                'pageSize'=>20,
                            ),
                ));
            
            $questionModel = new Question();
            
            $this->render('view',array(
			'model'                 =>  $model,
                        'questionsDataProvider' =>  $questionsDataProvider,
                        'questionModel'         =>  $questionModel,
		));
             
             */
	}
        
        public function actionAlias($name)
	{
            // если в урле заглавные буквы, редиректим на вариант с маленькими
            if(preg_match("/[A-Z]/", $name)) {
                $this->redirect(array('questionCategory/alias', 'name'=>strtolower($name)), true, 301);
            }
            
            $model = QuestionCategory::model()->with('parent','children')->findByAttributes(array('alias'=>CHtml::encode($name)));
            if(!$model) {
                throw new CHttpException(404,'Категория не найдена');
            }
            $questionsCriteria = new CdbCriteria;
            $questionsCriteria->addColumnCondition(array('t.status' =>  Question::STATUS_PUBLISHED));
            $questionsCriteria->order = 't.publishDate DESC';
            $questionsCriteria->with = array(
                        'categories'  =>  array(
                            'condition' =>  'categories.id = ' . $model->id,
                ),
                );
            
            $questions = Question::model()->findAll($questionsCriteria);
            
            if(sizeof($questions) == 0) {
                // если в данной категории не найдено ни одного вопроса, найдем последние 
                // вопросы с ответами, независимо от категории
                $questionsCriteria = new CDbCriteria;
                $questionsCriteria->order = 't.publishDate DESC';
                $questionsCriteria->limit = 5;
            
                $questions = Question::model()->findAll($questionsCriteria);    
            }
            //CustomFuncs::printr($questions);
            
            $questionsDataProvider = new CArrayDataProvider($questions, array(
                    'pagination'    =>  array(
                            'pageSize'=>20,
                        ),
                ));
            
            $newQuestionModel = new Question();
            
            $this->render('view',array(
			'model'                 =>  $model,
                        'questionsDataProvider' =>  $questionsDataProvider,
                        'newQuestionModel'      =>  $newQuestionModel,
		));
	}
        
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider(QuestionCategory::model()->cache(120, NULL, 3), array(
                    'criteria'      =>  array(
                        'order'     =>  't.name',
                        'with'      =>  'children',
                        'condition' =>  't.parentId=0',
                    ),
                    'pagination'    =>  array(
                                'pageSize'=>100,
                            ),
                ));
		$this->render('index',array(
			'dataProvider'  =>  $dataProvider,
		));
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return QuestionCategory the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=QuestionCategory::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param QuestionCategory $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='question-category-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
