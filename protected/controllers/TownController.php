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
				'actions'=>array('index', 'view', 'alias', 'aliasOld', 'ajaxGetList'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
        // список городов
        public function actionIndex()
	{           
            throw new CHttpException(404,'Этой страницы больше не существует...');
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
            // если обратились по id города, делаем редирект на ЧПУ
            $this->redirect(array('town/alias','name'=>$town->alias), true, 301);
            
            $criteria = new CDbCriteria;
            $criteria->order = 't.id desc';
            $criteria->addColumnCondition(array('t.status' =>  Question::STATUS_PUBLISHED));
            $criteria->addColumnCondition(array('t.townId' =>  (int)$id));
            $criteria->with = array('categories', 'town', 'answersCount');
                
            $dataProvider = new CActiveDataProvider('Question', array(
                    'criteria'=>$criteria,        
                    'pagination'=>array(
                                'pageSize'=>7,
                            ),
                ));
            
            $questionModel = new Question();
            
            $this->render('view',array(
			'model'         =>  $town,
                        'dataProvider'  =>  $dataProvider,
                        'questionModel' =>  $questionModel,
		));
	}
        
        
        // displays town by alias
        public function actionAlias($name)
	{           
            $model = Town::model()->cache(60)->findByAttributes(array('alias'=>CHtml::encode($name)));
            if(empty($model)) {
                throw new CHttpException(404,'Город не найден');
            }
            
            // при попытке обратиться по адресу типа town/alias/xxxx, переадресуем на адрес со страной и регионом
            if(!isset($_GET['regionAlias'])) {
                $this->redirect(array(
                    'town/alias', 
                    'name'          =>  $model->alias,
                    'countryAlias'  =>  $model->country->alias,
                    'regionAlias'   =>  $model->region->alias,
                    ), 301);
            }
            
            $questions = Yii::app()->db->cache(600)->createCommand()
                    ->select('q.id id, q.publishDate date, q.title title, COUNT(*) counter')
                    ->from('{{question}} q')
                    ->leftJoin('{{answer}} a', 'q.id=a.questionId')
                    ->group('q.id')
                    ->where('(q.status=:status1 OR q.status=:status2) AND a.id IS NOT NULL AND q.townId=:townId', array(':status1'=>  Question::STATUS_PUBLISHED, ':status2'=>  Question::STATUS_CHECK, ':townId'=>$model->id))
                    ->limit(30)
                    ->order('q.publishDate DESC')
                    ->queryAll();
            
			
            $questionModel = new Question();
            
            // города того же региона
            //$closeTowns = $model->getCloseTowns
            $regionId = $model->regionId;
            $regionCriteria = new CDbCriteria;
            $regionCriteria->with = array("region", "country");
            $regionCriteria->addColumnCondition(array('regionId'=>$regionId));
            $regionCriteria->order = "t.name asc";
            
            $closeTowns = Town::model()->findAll($regionCriteria);
            
            $allDirections = QuestionCategory::getDirections(true);
            
            $this->render('view',array(
			'model'         =>  $model,
                        'questions'     =>  $questions,
                        'questionModel' =>  $questionModel,
                        'closeTowns'    =>  $closeTowns,
                        'allDirections' =>  $allDirections,
		));
        }
        
        /*
         * метод для обработки старых адресов вида konsultaciya-yurista-voronezh
         * и редиректа на новые адреса городов
         */
        public function actionAliasOld($name)
        {
            $town = Town::model()->findByAttributes(array('alias'=>$name));
            if(!$town) {
                throw new CHttpException(404,'Страница города не найдена');
            }
            
            if(!($town->region && $town->country)) {
                throw new CHttpException(404,'Страница города не найдена, страна и регион не определены');
            }
            
            $this->redirect(array(
                    'town/alias', 
                    'name'          =>  $town->alias,
                    'countryAlias'  =>  $town->country->alias,
                    'regionAlias'   =>  $town->region->alias,
                    ), true, 301);
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
        
        
        public function actionAjaxGetList()
        {
            $term=addslashes(CHtml::encode($_GET['term']));

            $arr = array();

            $condition = "name LIKE '%".$term."%'";
            $params = Array('limit'=>5);

            $allTowns = Town::model()->cache(10000)->findAllByAttributes(array(),$condition,$params);

            foreach($allTowns as $town)
            {
                $arr[] = array(
                  'value'   =>  CHtml::encode($town->name . ' (' . $town->region->name . ')'),  
                  'id'      =>  $town->id,            
                );
            }
            echo CJSON::encode($arr);
        }
}
