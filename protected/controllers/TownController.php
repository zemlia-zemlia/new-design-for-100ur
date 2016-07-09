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
				'actions'=>array('index', 'view', 'alias','ajaxGetList'),
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
            
            //SELECT t.name, t.id, COUNT(*) counter FROM `crm_town` t LEFT JOIN `crm_question` q ON q.townId = t.id GROUP BY t.id HAVING counter>3 ORDER BY counter DESC
            if(!$townsRows = Yii::app()->cache->get('townsCloud')) {    
                $townsRows = Yii::app()->db->createCommand()
                        ->select("t.id, t.name, t.alias, COUNT(*) counter")
                        ->from("{{town}} t")
                        ->leftJoin("{{question}} q", "t.id = q.townId")
                        ->where('q.status=:status', array(':status'=>  Question::STATUS_PUBLISHED))
                        ->having("counter>1")
                        ->group("t.id")
                        ->queryAll();
                Yii::app()->cache->set('townsCloud', $townsRows, 3600);
            }
            
            $townsArray = array();
            $counterMax = 0; // максимальное количество вопросов в городе
            $counterMin = 0; // минимальное количество вопросов в городе
            
            // найдем минимальное и максимальное количество вопросов
            foreach($townsRows as $row) {
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
                        
            foreach($townsRows as $row) {
                $townsArray[$row['id']]['name'] = $row['name'];
                $townsArray[$row['id']]['alias'] = $row['alias'];
                $townsArray[$row['id']]['counter'] = $row['counter'];
            }
            
            //CustomFuncs::printr($townsArray); exit;
            
            $this->render('index',array(
			'townsArray'        =>  $townsArray,
                        'counterMin'        =>  $counterMin,
                        'counterMax'        =>  $counterMax,
		));
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
            
            $criteria = new CDbCriteria;
            $criteria->order = 't.id desc';
            $criteria->addColumnCondition(array('t.status' =>  Question::STATUS_PUBLISHED));
            $criteria->addColumnCondition(array('t.townId' =>  (int)$model->id));
            $criteria->with = array('categories', 'town', 'answersCount');
                
            $dataProvider = new CActiveDataProvider('Question', array(
                    'criteria'=>$criteria,        
                    'pagination'=>array(
                                'pageSize'=>7,
                            ),
                ));
            
            // если не нашлось вопросов в городе, найдем последние опубликованные вопросы по всем городам
            if($dataProvider->totalItemCount == 0) {
                $criteria = new CDbCriteria;
                $criteria->order = 't.publishDate desc';
                $criteria->limit = 5;
                $criteria->condition = 't.title!=""';
                $criteria->with = array('categories', 'town', 'answersCount'=>array(
                    'having'=>'`s`>0',
                ));
                $criteria->addColumnCondition(array('status'    => Question::STATUS_PUBLISHED));

                $dataProvider = new CActiveDataProvider('Question', array(
                    'criteria'      =>  $criteria,        
                    'pagination'    =>  false,
                ));
            }
			
            $questionModel = new Question();
            
            // города того же региона
            $closeTowns = $model->getCloseTowns();
            
            $this->render('view',array(
			'model'         =>  $model,
                        'dataProvider'  =>  $dataProvider,
                        'questionModel' =>  $questionModel,
                        'closeTowns'    =>  $closeTowns,
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
                  'value'   =>  CHtml::encode($town->name . ' (' . $town->ocrug . ')'),  
                  'id'      =>  $town->id,            
                );
            }
            echo CJSON::encode($arr);
        }
}
