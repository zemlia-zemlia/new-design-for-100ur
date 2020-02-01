<?php

class RegionController extends Controller
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//frontend/question';

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
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'country', 'redirect'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
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
    public function actionView()
    {
        $this->layout = "/frontend/catalog";

        if (!isset($_GET['regionAlias'])) {
            throw new CHttpException(404, 'Регион не найден');
        }

        $model = Region::model()->findByAttributes(array('alias' => CHtml::encode($_GET['regionAlias'])));

        if (!$model) {
            throw new CHttpException(404, 'Регион не найден');
        }

        // найдем id городов текущего региона
        $towns = Yii::app()->db->createCommand()
            ->select('id')
            ->from('{{town}}')
            ->where('regionId=:regionId', [':regionId' => $model->id])
            ->queryColumn();

        $criteria = new CDbCriteria;

        $criteria->order = "rating DESC, karma DESC";
        $criteria->with = array("settings", "town", "town.region", "categories", "answersCount");
        $criteria->addColumnCondition(array('active100' => 1));
        $criteria->addColumnCondition(array('avatar!' => ''));
        $criteria->addInCondition('t.townId', $towns);
        $criteria->addCondition("role = " . User::ROLE_JURIST);

        $yuristsDataProvider = new CActiveDataProvider('User', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));

        $this->render('view', array(
            'model' => $model,
            'yuristsDataProvider' => $yuristsDataProvider,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $regionsRows = Yii::app()->db->cache(3600)->createCommand()
            ->select("r.id, r.name regionName, r.alias regionAlias, c.id countryId, c.name countryName, c.alias countryAlias")
            ->from("{{region}} r")
            ->leftJoin("{{country}} c", "c.id = r.countryId")
            ->order("c.id asc, r.name")
            ->queryAll();

        $regionsArray = array();

        foreach ($regionsRows as $region) {
            $regionsArray[$region['countryAlias']][] = $region;
        }

//            CustomFuncs::printr($regionsArray);

        $this->render('index', array(
            'regions' => $regionsArray,
        ));
    }

    public function actionCountry($countryAlias)
    {
        $this->layout = "/frontend/catalog";

        $country = Country::model()->findByAttributes(array('alias' => $countryAlias));

        if (!($country instanceof Country)) {
            throw new CHttpException(404, 'Страна не найдена');
        }

        $regionsRows = Yii::app()->db->cache(0)->createCommand()
            ->select("r.id, r.name regionName, r.alias regionAlias, c.id countryId, c.name countryName, c.alias countryAlias")
            ->from("{{region}} r")
            ->leftJoin("{{country}} c", "c.id = r.countryId")
            ->where('c.alias = :alias', array(':alias' => $countryAlias))
            ->order("c.id asc, r.name")
            ->queryAll();


        $criteria = new CDbCriteria;

        $criteria->order = "rating DESC, karma DESC";
        $criteria->with = array("settings", "town", "town.region", "categories", "answersCount");
        $criteria->addColumnCondition(array('active100' => 1));
        $criteria->addColumnCondition(array('avatar!' => ''));
        $criteria->addCondition("role = " . User::ROLE_JURIST);
        $criteria->limit = 20;

        $yuristsDataProvider = new CActiveDataProvider('User', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 12,
            ),
        ));

        $this->render('country', array(
            'regions' => $regionsRows,
            'country' => $country,
            'yuristsDataProvider' => $yuristsDataProvider,
        ));
    }

    /**
     * Редирект со старых адресов типа region/russia/* на /yurist/russia/*
     */
    public function actionRedirect()
    {
        $countryAlias = Yii::app()->request->getParam('countryAlias');
        $regionAlias = Yii::app()->request->getParam('regionAlias');
        $townName = Yii::app()->request->getParam('name');

        if ($countryAlias && $regionAlias && $townName) {
            $this->redirect([
                '/town/alias',
                'name' => $townName,
                'countryAlias' => $countryAlias,
                'regionAlias' => $regionAlias
            ], true, 301);
        }

        if ($countryAlias && $regionAlias) {
            $this->redirect([
                '/region/view',
                'countryAlias' => $countryAlias,
                'regionAlias' => $regionAlias
            ], true, 301);
        }

        if ($countryAlias) {
            $this->redirect([
                '/region/country',
                'countryAlias' => $countryAlias,
            ], true, 301);
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Region('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Region'])) {
            $model->attributes = $_GET['Region'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Region the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Region::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Region $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'region-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
