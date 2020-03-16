<?php

use App\models\User;

class YuristController extends Controller
{
    public $layout = '//frontend/question';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
                //'postOnly + delete', // we only allow deletion via POST request
        ];
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     *
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            ['allow', // allow all users
                'actions' => ['index'],
                'users' => ['*'],
            ],
            ['deny', // deny all users
                'users' => ['*'],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->redirect(['region/country', 'countryAlias' => 'russia'], true, 301);

        $criteria = new CDbCriteria();

        $criteria->order = 'karma DESC';
        $criteria->with = ['settings', 'town', 'town.region', 'categories', 'answersCount'];
        $criteria->addColumnCondition(['active100' => 1]);
        $criteria->addColumnCondition(['avatar!' => '']);
        $criteria->addCondition('role = ' . User::ROLE_JURIST);

        $yuristsDataProvider = new CActiveDataProvider('User', [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 40,
            ],
        ]);

        $this->render('index', [
            'yuristsDataProvider' => $yuristsDataProvider,
        ]);
    }
}
