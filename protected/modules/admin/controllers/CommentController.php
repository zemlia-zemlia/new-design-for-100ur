<?php

class CommentController extends Controller
{
    public $layout='//admin/main';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + toSpam', // we only allow deletion via POST request
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
                        'actions'=>array('index','view', 'create','update','admin','delete', 'publish', 'setPubTime', 'toSpam'),
                        'users'=>array('@'),
                        'expression'=>'Yii::app()->user->checkAccess(' . User::ROLE_ROOT . ')',
                ),
                array('deny',  // deny all users
                        'users'=>array('*'),
                ),
            );
    }

    public function actionView($id)
    {
        $model = Comment::model()->findByPk($id);

        $this->render('view', array(
                    'model' =>  $model,
            ));
    }
        
    public function actionIndex()
    {
        $criteria = new CDbCriteria;
        $criteria->order = 't.id DESC';
        $criteria->with = 'author';
        $status = null;
                
                
        if (isset($_GET['status'])) {
            $status = (int)$_GET['status'];
            $criteria->addColumnCondition(array('t.status'=>$status));
        }
                
                
        if (isset($_GET['type'])) {
            $type = (int)$_GET['type'];
            $criteria->addColumnCondition(array('t.type'=>$type));
        } else {
            $criteria->addCondition('t.type IN('.Comment::TYPE_COMPANY.', '.Comment::TYPE_ANSWER.')');
        }
                
        $dataProvider = new CActiveDataProvider('Comment', array(
                    'criteria'=>$criteria,
                    'pagination'=>array(
                                'pageSize'=>20,
                            ),
                ));
                
        $this->render('index', array(
            'dataProvider'  =>  $dataProvider,
                        'status'        =>  $status,
                        'type'          =>  $type,
        ));
    }
        
    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);
        $oldStatus = $model->status;

        if (isset($_POST['Comment'])) {
            $model->attributes = $_POST['Comment'];
                    
            if ($model->saveNode()) {
                $this->redirect(array('view','id'=>$model->id, 'question_updated'=>'yes'));
            }
        }

                
        $this->render('update', array(
            'model' =>  $model,
        ));
    }
        
        
    public function actionPublish()
    {
        if (isset($_POST['id'])) {
            $id = (int)$_POST['id'];
        }
            
        $model = $this->loadModel($id);
        $model->status = Comment::STATUS_CHECKED;
        if ($model->saveNode()) {
            echo CJSON::encode(array('id'=>$id, 'status'=>1));
        } else {
            //print_r($model->errors);
            echo CJSON::encode(array('status'=>0));
        }
    }
        
    public function actionToSpam()
    {
        if (isset($_POST['id'])) {
            $id = (int)$_POST['id'];
        }
        $model = $this->loadModel($id);
        $model->status = Comment::STATUS_SPAM;
        if ($model->saveNode()) {
            echo CJSON::encode(array('id'=>$id, 'status'=>1));
        } else {
            //print_r($model->errors);
            echo CJSON::encode(array('status'=>0));
        }
    }
        
    public function actionDelete($id)
    {
        $this->loadModel($id)->deleteNode();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
    }
        
        
    public function loadModel($id)
    {
        $model=Comment::model()->findByPk($id);
        if ($model===null) {
            throw new CHttpException(404, 'Отзыв не найден');
        }
        return $model;
    }
}
