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
             
	}
        
        /**
         * Показ категории вопроса по ее псевдониму
         * 
         * @param strung $name псевдоним категории
         * @throws CHttpException
         */
        public function actionAlias($name)
	{
            // какое количество соседних категорий до и после выводить у текущей категории
            $neighboursLimit = 6;
            
            
            // если в урле заглавные буквы, редиректим на вариант с маленькими
            if(preg_match("/[A-Z]/", $name)) {
                $this->redirect(array('questionCategory/alias', 'name'=>strtolower($name)), true, 301);
            }
            
//            $model = QuestionCategory::model()->with('parent','children')->findByAttributes(array('alias'=>CHtml::encode($name)));
            $model = QuestionCategory::model()->findByAttributes(array('alias'=>CHtml::encode($name)));
            
            if(!$model) {
                throw new CHttpException(404,'Категория не найдена');
            }
            
            if($model->parentId != 0) {
                // это дочерняя категория. найдем родительскую категорию
                $parentCategory = Yii::app()->db->cache(300)->createCommand()
                    ->select('id, name, alias')
                    ->from('{{questionCategory}}')
                    ->where('id=:parentId', array(':parentId'=>$model->parentId))
                    ->limit(1)
                    ->queryRow();
                
                $neighbourCategoriesRows = Yii::app()->db->cache(300)->createCommand()
                    ->select('id, name, alias')
                    ->from('{{questionCategory}}')
                    ->where('parentId=:parentId', array(':parentId'=>$model->parentId))
                    ->order('name')
                    ->queryAll();
                //CustomFuncs::printr($neighbourCategoriesRows);
                /* получили массив категорий того же родителя и того же уровня
                 * найдем в нем текущую категорию и по 6 категорий впереди и сзади
                 */
                $currentCategoryPosition = 0;
                foreach($neighbourCategoriesRows as $index=>$neigbour) {
                    if($neigbour['id'] == $model->id) {
                        $currentCategoryPosition = $index;
                        //echo $currentCategoryPosition . '<br />';
                        break;
                    }
                }
                $previousNeighbours = array();
                $nextNeighbours = array();
                $neighbours = array();
                
                // найдем впередистоящих соседей
                for($i=1;$i<=$neighboursLimit;$i++) {
                    $prevPosition = $currentCategoryPosition - $i;
                    if($prevPosition < 0) {
                        $prevPosition += sizeof($neighbourCategoriesRows);
                    } 
                    // для маленького числа соседей: защита от ссылки на ту же категорию
                    if($prevPosition == $currentCategoryPosition) {
                        break;
                    }
                    //echo $prevPosition . ' ';
                    $previousNeighbours[$prevPosition] = $neighbourCategoriesRows[$prevPosition];
                }
                
                // найдем далеестоящих соседей
                for($i=1;$i<=$neighboursLimit;$i++) {
                    $nextPosition = $currentCategoryPosition + $i;
                    if($nextPosition >= sizeof($neighbourCategoriesRows)) {
                        $nextPosition -= sizeof($neighbourCategoriesRows);
                    } 
                    
                    // для маленького числа соседей: защита от ссылки на ту же категорию
                    if($nextPosition == $currentCategoryPosition) {
                        break;
                    }
                    //echo $nextPosition . ' ';
                    $nextNeighbours[$nextPosition] = $neighbourCategoriesRows[$nextPosition];
                }
                
                $neighbours = $previousNeighbours + $nextNeighbours;
                
                //CustomFuncs::printr($neighbours);
                
                
            } else {
                // это категория верхнего уровня. надем категории, дочерние текущей
                $childrenCategories = Yii::app()->db->cache(300)->createCommand()
                    ->select('id, name, alias')
                    ->from('{{questionCategory}}')
                    ->where('parentId=:parentId', array(':parentId'=>$model->id))
                    ->order('name')
                    ->limit(9)
                    ->queryAll();
            }
            
            
            
                       
            
            $questions = $this->findQuestions($model);
            
            // если в категории не нашлось вопросов
            if(sizeof($questions) == 0) {
                $questions = $this->findQuestions($model, false);
            }
            
            $newQuestionModel = new Question();
            
            $this->render('view',array(
			'model'                 =>  $model,
                        'questions'             =>  $questions,
                        'newQuestionModel'      =>  $newQuestionModel,
                        'childrenCategories'    =>  $childrenCategories,
                        'parentCategory'        =>  $parentCategory,
                        'neighbours'            =>  $neighbours,
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
        
        /* возвращает массив вопросов
         * $thisCategory = true - вопросы данной категории
         * $thisCategory = false - новые вопросы без привязки к категории
         */
        protected function findQuestions($category, $thisCategory = true)
        {
            $questionsCommand = Yii::app()->db->createCommand()
                    ->select('q.id id, q.publishDate date, q.title title, a.id answerId')
                    ->from('{{question}} q')
                    ->leftJoin('{{answer}} a', 'q.id=a.questionId')
                    ->where('q.status=:status', array(':status'=>  Question::STATUS_PUBLISHED))
                    ->limit(20)
                    ->order('q.publishDate DESC');
            
            
            
            if($thisCategory === true) {
                $questionsCommand = $questionsCommand->join("{{question2category}} q2c", "q2c.qId=q.id AND q2c.cId=:catId", array(':catId'=>$category->id));
            }

            
            $questions = $questionsCommand->queryAll();
           
            $questionsArray = array();
            
            foreach($questions as $question) {
                $questionsArray[$question['id']]['id'] = $question['id'];
                $questionsArray[$question['id']]['date'] = $question['date'];
                $questionsArray[$question['id']]['title'] = $question['title'];
                if(!is_null($question['answerId'])) {
                    $questionsArray[$question['id']]['counter']++;
                }
            }
            
            return $questionsArray;
        }
}
