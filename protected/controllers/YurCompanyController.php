<?php

class YurCompanyController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//frontend/main';

        
        /*
         *  Адресация:
         * /company
         * /company/town/moskva - юр фирмы из Москвы
         * /firm/5564 - страница юрфирмы
         */
        
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view', 'town'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
        public function actionTown($alias)
        {
            $town = Town::model()->cache(60)->findByAttributes(array('alias'=>CHtml::encode($alias)));
            
            if(!$town) {
                throw new CHttpException(404, 'Город не найден');
            }
            
            $companyCriteria = new CDbCriteria;
            $companyCriteria->addColumnCondition(array('t.townId' =>  (int)$town->id));
            $companyCriteria->order = 't.id desc';
            
            $dataProvider = new CActiveDataProvider('YurCompany', array(
                'criteria'      =>  $companyCriteria,        
                'pagination'    =>  array(
                            'pageSize'  =>  30,
                        ),
            ));
            
            $this->render('town',array(
                'town'          =>  $town,
                'dataProvider'  =>  $dataProvider,
            ));
        }

        /**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
            $company = YurCompany::model()->findByPk($id);
                        
            if(!$company) {
                throw new CHttpException(404,'Компания не найдена');
            }
            
            $comment = new Comment; // модель для нового комментария

            if(isset($_POST['Comment'])) {
                $comment->attributes = $_POST['Comment'];
                $comment->authorId = (Yii::app()->user->id)?Yii::app()->user->id:0;
                $comment->type = Comment::TYPE_COMPANY;
                $comment->objectId = $company->id;
                $comment->status = Comment::STATUS_NEW;
                
                if(isset($comment->parentId) && $comment->parentId >0) {
                    // является потомком другого комментария, сохраним его как дочерний комментарий
                    $rootComment=Comment::model()->findByPk($comment->parentId);
                    $comment->appendTo($rootComment);
                }
                /*CustomFuncs::printr($_POST);
                CustomFuncs::printr($rootComment->attributes);
                CustomFuncs::printr($comment->attributes);exit;*/
                
                if($comment->saveNode()) {
                    $this->redirect(array('yurCompany/view', 'id'=>$company->id, 'commentSaved'=>1));
                }
            }
            
            $commentSaved = (isset($_GET['commentSaved']))?true:false;
            
            $this->render('view', array(
                'company'       =>  $company,
                'comment'       =>  $comment,
                'commentSaved'  =>  $commentSaved,
            ));
	}


	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
            $companiesRows = Yii::app()->db->cache(3600)->createCommand()
                    ->select("t.name, t.alias, t.name, COUNT(*) counter")
                    ->from("{{yurCompany}} c")
                    ->leftJoin("{{town}} t", "t.id=c.townId")
                    ->group("c.townId")
                    ->order("counter DESC")
                    ->queryAll();
            //CustomFuncs::printr($companiesRows);
            $this->render('index',array(
                'towns' => $companiesRows,
            ));
            
	}



	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return YurCompany the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=YurCompany::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param YurCompany $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='yur-company-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
