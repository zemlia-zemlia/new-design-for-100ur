<?php

class QuestionCategoryController extends Controller
{

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
                        array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','view','create','update','admin','delete','translit'),
				'users'=>array('@'),
                                'expression'=>'Yii::app()->user->checkAccess(' . User::ROLE_ROOT . ')',
			),
                        array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','view','create','update', 'ajaxGetList', 'directions'),
				'users'=>array('@'),
                                'expression'=>'Yii::app()->user->checkAccess(' . User::ROLE_EDITOR . ')',
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
            $questionsCriteria->with = array(
                        'categories'  =>  array(
                            'condition' =>  'categories.id = ' . $model->id,
                ),
                );
            $questionsCriteria->order = 't.id DESC';
            
            $questions = Question::model()->findAll($questionsCriteria);
            //CustomFuncs::printr($questions);
            
            $questionsDataProvider = new CArrayDataProvider($questions, array(
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new QuestionCategory;
                
                if(isset($_GET['parentId']) && $_GET['parentId']) {
                    $model->parentId = (int)$_GET['parentId'];
                }

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['QuestionCategory']))
		{
			$model->attributes=$_POST['QuestionCategory'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

                // для работы визуального редактора подключим необходимую версию JQuery
                $scriptMap = Yii::app()->clientScript->scriptMap;
                $scriptMap['jquery.js'] = '/js/jquery-1.8.3.min.js'; 
                $scriptMap['jquery.min.js'] = '/js/jquery-1.8.3.min.js'; 
                Yii::app()->clientScript->scriptMap = $scriptMap;
            
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

		if(isset($_POST['QuestionCategory']))
		{
			$model->attributes=$_POST['QuestionCategory'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
                
                // для работы визуального редактора подключим необходимую версию JQuery
                $scriptMap = Yii::app()->clientScript->scriptMap;
                $scriptMap['jquery.js'] = '/js/jquery-1.8.3.min.js'; 
                $scriptMap['jquery.min.js'] = '/js/jquery-1.8.3.min.js'; 
                Yii::app()->clientScript->scriptMap = $scriptMap;
                
		$this->render('update',array(
			'model'         =>  $model,

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

		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{

//            $dataProvider=new CActiveDataProvider(QuestionCategory::model()->cache(120, NULL, 3), array(
//                'criteria'      =>  array(
//                    'order'     =>  't.name',
//                    'with'      =>  array('children'),
//                    'condition' =>  't.parentId=0',
//                ),
//                'pagination'    =>  array(
//                            'pageSize'=>400,
//                        ),
//            ));
            /*
             * Извлекаем список категорий с иерархией
             * SELECT c.id, c.name, LENGTH(c.description1),  LENGTH(c.description2), LENGTH(c.seoTitle), LENGTH(c.seoDescription), LENGTH(c.seoKeywords), LENGTH(c.seoH1), c.isDirection, child.id, child.name, LENGTH(child.description1),  LENGTH(child.description2), LENGTH(child.seoTitle), LENGTH(child.seoDescription), LENGTH(child.seoKeywords), LENGTH(child.seoH1), child.isDirection
                FROM `100_questionCategory` c
                LEFT JOIN `100_questionCategory` child ON child.parentId = c.id
                ORDER BY c.name
                LIMIT 100
             */
            
            $categoriesArray = array();
            
            // проверим, не сохранен ли в кеше массив со структурой категорий
            if(Yii::app()->cache->get('categories_list')!== false) {
                $categoriesArray = Yii::app()->cache->get('categories_list');
            } else {
                // если не сохранен, вытащим его из базы    
                $categoriesRows = Yii::app()->db->createCommand()
                        ->select("c.id c_id, "
                                . "c.name c_name, "
                                . "LENGTH(c.description1) c_description1,  "
                                . "LENGTH(c.description2) c_description2, "
                                . "LENGTH(c.seoTitle) c_seoTitle, "
                                . "LENGTH(c.seoDescription) c_seoDescription, "
                                . "LENGTH(c.seoKeywords) c_seoKeywords, "
                                . "LENGTH(c.seoH1) c_seoH1, "
                                . "c.isDirection c_isDirection, "
                                . "child.id child_id, "
                                . "child.name child_name, "
                                . "LENGTH(child.description1) child_description1,  "
                                . "LENGTH(child.description2) child_description2, "
                                . "LENGTH(child.seoTitle) child_seoTitle, "
                                . "LENGTH(child.seoDescription) child_seoDescription, "
                                . "LENGTH(child.seoKeywords) child_seoKeywords, "
                                . "LENGTH(child.seoH1) child_seoH1, "
                                . "child.isDirection child_isDirection")
                        ->from("{{questionCategory}} c")
                        ->leftJoin("{{questionCategory}} child", "child.parentId = c.id")
                        ->where('c.parentId=0')
                        ->order("c.name")
                        ->queryAll();

                foreach($categoriesRows as $row) {
                    $categoriesArray[$row['c_id']]['name'] = $row['c_name'];
                    $categoriesArray[$row['c_id']]['description1'] = $row['c_description1'];
                    $categoriesArray[$row['c_id']]['description2'] = $row['c_description2'];
                    $categoriesArray[$row['c_id']]['seoTitle'] = $row['c_seoTitle'];
                    $categoriesArray[$row['c_id']]['seoDescription'] = $row['c_seoDescription'];
                    $categoriesArray[$row['c_id']]['seoKeywords'] = $row['c_seoKeywords'];
                    $categoriesArray[$row['c_id']]['seoH1'] = $row['c_seoH1'];
                    $categoriesArray[$row['c_id']]['isDirection'] = $row['c_isDirection'];
                    if($row['child_id']){
                        $categoriesArray[$row['c_id']]['children'][$row['child_id']]['name'] = $row['child_name'];
                        $categoriesArray[$row['c_id']]['children'][$row['child_id']]['description1'] = $row['child_description1'];
                        $categoriesArray[$row['c_id']]['children'][$row['child_id']]['description2'] = $row['child_description2'];
                        $categoriesArray[$row['c_id']]['children'][$row['child_id']]['seoTitle'] = $row['child_seoTitle'];
                        $categoriesArray[$row['c_id']]['children'][$row['child_id']]['seoDescription'] = $row['child_seoDescription'];
                        $categoriesArray[$row['c_id']]['children'][$row['child_id']]['seoKeywords'] = $row['child_seoKeywords'];
                        $categoriesArray[$row['c_id']]['children'][$row['child_id']]['seoH1'] = $row['child_seoH1'];
                        $categoriesArray[$row['c_id']]['children'][$row['child_id']]['isDirection'] = $row['child_isDirection'];

                    }
                }
                
                Yii::app()->cache->set('categories_list', $categoriesArray, 600);
            }
            
//            CustomFuncs::printr($categoriesArray);
//            exit;
            
            $this->render('index',array(
                    'categoriesArray'  =>  $categoriesArray,
            ));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new QuestionCategory('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['QuestionCategory']))
			$model->attributes=$_GET['QuestionCategory'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
        
        public function actionTranslit()
        {
            $categories = QuestionCategory::model()->findAll();
            foreach($categories as $cat) {
                if($cat->alias == '') {
                    $cat->alias = CustomFuncs::translit($cat->name);
                    $cat->save();
                }
            }
            
            $towns = Town::model()->findAllByAttributes(array('alias'=>''));
            foreach($towns as $town) {
                if($town->alias == '') {
                    $town->alias = CustomFuncs::translit($town->name) . "-" . $town->id;
                    echo $town->name . " - " . $town->alias . "<br />";
                    if(!$town->save()){
                        CustomFuncs::printr($town->errors);
                    }
                }
            }
            
        }
        
        public function actionAjaxGetList()
        {
            $term=addslashes(CHtml::encode($_GET['term']));

            $arr = array();

            $condition = "name LIKE '%".$term."%'";
            $params = Array('limit'=>5);

            $allCats = QuestionCategory::model()->cache(10000)->findAllByAttributes(array(),$condition,$params);

            foreach($allCats as $cat)
            {
                $arr[] = array(
                  'value'   =>  CHtml::encode($cat->name),  
                  'id'      =>  $cat->id,            
                );
            }
            echo CJSON::encode($arr);
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
        
        
        // выводит список категорий-направлений с их иерархией
        public function actionDirections()
        {
            $directions = QuestionCategory::getDirections(true, true);
            
//            CustomFuncs::printr($directions);
            echo "<ul>";
            foreach($directions as $catId=>$cat) {
                echo "<li>" . $cat['name'] . '</li>';
                if($cat['children']) {
                    echo '<ul>';
                    foreach($cat['children'] as $childId=>$child) {
                        echo "<li>" . $child['name'] . '</li>';
                    }
                    echo '</ul>';
                }
            }
            echo "</ul>";
        }
}
