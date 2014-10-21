<?php

class TownController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('view'),
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
            $town = Town::model()->findByPk($id);
            if(!$town) {
                throw new CHttpException(404,'Город не найден');
            }
            
            $criteria = new CDbCriteria;
            $criteria->order = 't.id desc';
            $criteria->addColumnCondition(array('t.status' =>  Question::STATUS_PUBLISHED));
            $criteria->addColumnCondition(array('t.townId' =>  (int)$id));
            $criteria->with = array('category', 'town', 'answersCount');
                
            $dataProvider = new CActiveDataProvider('Question', array(
                    'criteria'=>$criteria,        
                    'pagination'=>array(
                                'pageSize'=>20,
                            ),
                ));
            
            $questionModel = new Question();
            
            $this->render('view',array(
			'model'         =>  $town,
                        'dataProvider'  =>  $dataProvider,
                        'questionModel' =>  $questionModel,
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
