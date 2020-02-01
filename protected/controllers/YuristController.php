<?php

class YuristController extends Controller
{
    public $layout = '//frontend/question';

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
            array('allow', // allow all users
                'actions' => array('index'),
                'users' => array('*'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        return $this->redirect(['region/country', 'countryAlias' => 'russia'], true, 301);
        
        
        $criteria = new CDbCriteria;

        $criteria->order = "karma DESC";
        $criteria->with = array("settings", "town", "town.region", "categories", "answersCount");
        $criteria->addColumnCondition(array('active100' => 1));
        $criteria->addColumnCondition(array('avatar!' => ''));
        $criteria->addCondition("role = " . User::ROLE_JURIST);

        $yuristsDataProvider = new CActiveDataProvider('User', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 40,
            ),
        ));

        $this->render('index', array(
            'yuristsDataProvider' => $yuristsDataProvider,
        ));
    }
}
