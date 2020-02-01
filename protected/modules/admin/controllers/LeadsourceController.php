<?php

class LeadsourceController extends Controller
{
    public $layout = '//admin/main';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
                //'postOnly + delete', // we only allow deletion via POST request
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
                'actions' => array('index', 'view', 'create', 'update', 'delete'),
                'users' => array('@'),
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_ROOT . ')',
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);
        
        // загружаем статистику по лидам из заданного источника
        $year = (isset($_GET['year']))?(int)$_GET['year'] : date('Y');
        $month = (isset($_GET['month']))?(int)$_GET['month'] : date('m');
        
        // массив лет, за которые есть лиды из данного источника
        $yearsArray = array();
        $yearsRows = Yii::app()->db->createCommand()
                ->select('DISTINCT(YEAR(question_date))')
                ->from('{{lead}} l')
                ->where('l.sourceId=:sourceId', array(
                    ':sourceId' => $model->id,
                ))
                ->queryColumn();
        foreach ($yearsRows as $row) {
            $yearsArray[$row] = $row;
        }
        //CustomFuncs::printr($yearsArray);
        
        // лиды из данного источника за заданный месяц
        $leadsStats = array();
        $leadsRows = Yii::app()->db->createCommand()
                ->select('l.id, l.buyPrice, l.price, l.leadStatus, t.name townName')
                ->from('{{lead}} l')
                ->leftJoin('{{town}} t', 'l.townId=t.id')
                ->where('l.sourceId=:sourceId AND YEAR(l.question_date)=:year AND MONTH(l.question_date)=:month', array(
                    ':sourceId' => $model->id,
                    ':year'     => $year,
                    ':month'    => $month,
                ))
                ->queryAll();
        
        foreach ($leadsRows as $row) {
            $leadsStats[$row['townName']]['total']++;
            $leadsStats[$row['townName']]['expences']+=$row['buyPrice'];
            
            if ($row['leadStatus'] == Lead::LEAD_STATUS_BRAK) {
                $leadsStats[$row['townName']]['brak']++;
            } else {
                $leadsStats[$row['townName']]['sold']++;
                $leadsStats[$row['townName']]['revenue']+=$row['price'];
            }
        }
        
        //CustomFuncs::printr($leadsStats);
        
        $this->render('view', array(
            'model'         =>  $model,
            'year'          =>  $year,
            'month'         =>  $month,
            'yearsArray'    =>  $yearsArray,
            'leadsStats'    =>  $leadsStats,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Leadsource;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Leadsource'])) {
            $model->attributes = $_POST['Leadsource'];
            if (Yii::app()->user->role == User::ROLE_MANAGER) {
                $model->officeId = Yii::app()->user->officeId;
            }
            
            $model->generateAppId();
            $model->generateSecretKey();
            
            if ($model->save()) {
                $this->redirect(array('index', 'officeId' => $model->officeId));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Leadsource'])) {
            $model->attributes = $_POST['Leadsource'];
            
            if (!$model->appId) {
                $model->generateAppId();
            }
            if (!$model->secretKey) {
                $model->generateSecretKey();
            }

            if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array(
            'model' => $model,
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

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $criteria = new CDbCriteria;
        
        $criteria->with = 'user';

        // добавим условие выборки контактов по офису
        if (isset($_GET['officeId'])) {
            $officeId = (int) $_GET['officeId'];
        } else {
            $officeId = 0;
        }
        if (Yii::app()->user->role != User::ROLE_ROOT) {
            $officeId = Yii::app()->user->officeId;
        }


        $dataProvider = new CActiveDataProvider(
            'Leadsource',
            array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ))
        );
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Leadsource('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Leadsource'])) {
            $model->attributes = $_GET['Leadsource'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Leadsource the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Leadsource::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Leadsource $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'leadsource-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
