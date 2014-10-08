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
				'actions'=>array('index','view'),
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
            
            $this->render('view',array(
			'model'                 =>  $model,
                        'questionsDataProvider' =>  $questionsDataProvider,
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
