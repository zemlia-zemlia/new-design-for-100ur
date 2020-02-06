<?php

class DocsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
    public $layout='//admin/main';
    public $enableCsrfValidation = false;
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
//			'accessControl', // perform access control for CRUD operations
//			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
//	public function accessRules()
//	{
//		return array(
//			array('allow',  // allow all users to perform 'index' and 'view' actions
//				'actions'=>array('index','view'),
//				'users'=>array('*'),
//			),
//			array('allow', // allow authenticated user to perform 'create' and 'update' actions
//				'actions'=>array('create','update'),
//				'users'=>array('@'),
//			),
//			array('allow', // allow admin user to perform 'admin' and 'delete' actions
//				'actions'=>array('admin','delete'),
//				'users'=>array('admin'),
//			),
//			array('deny',  // deny all users
//				'users'=>array('*'),
//			),
//		);
//	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

    public function actionCategory($id)
    {
        $category = FileCategory::model()->findByPk($id);
        $dataProvider=new CActiveDataProvider('Docs');
//        $dataProvider->criteria = ''
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
            'category'=>$category,
        ));
    }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
//	public function actionCreate()
//	{
//		$model=new Docs;
//
//		// Uncomment the following line if AJAX validation is needed
//		// $this->performAjaxValidation($model);
//
//		if(isset($_POST['Docs']))
//		{
//			$model->attributes=$_POST['Docs'];
//			if($model->save())
//				$this->redirect(array('view','id'=>$model->id));
//		}
//
//		$this->render('create',array(
//			'model'=>$model,
//		));
//	}

    public function actionCreate($id){
        $model=new Docs;
        $model->type = 1;
        if(isset($_POST['Docs'])){
            $model->attributes=$_POST['Docs'];
            $model->filename=CUploadedFile::getInstance($model,'filename');

                $name = $model->generateName();
                $path = Yii::getPathOfAlias('webroot') . '/upload/files/' . $name;
                $model->filename->saveAs($path);

            $model->filename = $name;

            $model->save();
            $category = new File2Category();
            $category->file_id = $model->id;
            $category->category_id = $id;

            $category->save();
//            var_dump($category->getErrors());die;

            Yii::app()->user->setFlash('success', "Файл загружен");
            return $this->redirect('/docs/index');


        }
        $this->render('create', array('model'=>$model));
    }

    public function actionDownload($id){
        $model = $this->loadModel($id);
        return $this->redirect($model->getDownloadLink());
    }





	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$filename = $model->filename;


		if(isset($_POST['Docs']))


        {
            $model->attributes=$_POST['Docs'];
            $model->filename=CUploadedFile::getInstance($model,'filename');
//            var_dump($model);die;
            if ($model->filename) {
                $name = $model->generateName();
                $path = Yii::getPathOfAlias('webroot') . '/upload/files/' . $name;
                $model->filename->saveAs($path);

                $model->filename = $name;
                unlink(Yii::getPathOfAlias('webroot') . '/upload/files/' . $filename);
            }
            else {
                $model->filename = $filename;
            }

            $model->save();
//            var_dump($model->getErrors());die;



                Yii::app()->user->setFlash('success', "Файл изменен");
                return $this->redirect('/docs/index');
				$this->redirect('index');

		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
        File2Category::model()->find('file_id = ' . $id)->delete();

		$this->loadModel($id)->delete();


        Yii::app()->user->setFlash('success', "Файл удален");
        return $this->redirect('/docs/index');

//		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
//		if(!isset($_GET['ajax']))
//			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex($id = 0)
	{

		if ($id != 0)
		$category = FileCategory::model()->findByPk($id);
		else $category = null;
		if (!$category)
		    $categories  = FileCategory::model()->roots()->findAll();
		else
		    $categories  = $category->children()->findAll();



		$this->render('index',array(

            'category' => $category,
            'categories' => $categories
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Docs('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Docs']))
			$model->attributes=$_GET['Docs'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Docs the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Docs::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Docs $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='docs-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	public function beforeAction($action){
        Yii::app()->request->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }


	public function actionAttachFilesToObject(){



	    if (isset($_POST['fileIds']) && isset($_POST['objId'])){
	        $objId = $_POST['objId'];
	        foreach ($_POST['fileIds'] as $fileId){
	           $fileToObj = new File2Object();
	           $fileToObj->file_id = $fileId;
	           $fileToObj->object_id = $objId;
	           $fileToObj->object_type = 1;
	           $fileToObj->save();



            }
            $model = QuestionCategory::model()->findByPk($objId);
            $html = '';
             if (is_array($model->docs)):
            foreach ($model->docs as $doc): ?>
                $html .=    "<div><h6><?php echo CHtml::link(CHtml::encode($doc->name), '/admin/docs/download/?id=' . $doc->id, ['target' => '_blank']); ?>(<?php echo CHtml::encode($doc->downloads_count); ?>)<a href=''>удалить</a></h6></div>";
            <?php endforeach;
        endif;

            return 	$html;
        }
        return  '<p>error</p>';
    }
}
