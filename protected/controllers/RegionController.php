<?php

class RegionController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     *             using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//frontend/question';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
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
            ['allow', // allow all users to perform 'index' and 'view' actions
                'actions' => ['index', 'view', 'country', 'redirect'],
                'users' => ['*'],
            ],
            ['allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['create', 'update'],
                'users' => ['@'],
            ],
            ['allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => ['admin', 'delete'],
                'users' => ['admin'],
            ],
            ['deny', // deny all users
                'users' => ['*'],
            ],
        ];
    }

    /**
     * Displays a particular model.
     *
     * @param int $id the ID of the model to be displayed
     */
    public function actionView()
    {
        $this->layout = '/frontend/catalog';

        if (!isset($_GET['regionAlias'])) {
            throw new CHttpException(404, 'Регион не найден');
        }

        $model = Region::model()->findByAttributes(['alias' => CHtml::encode($_GET['regionAlias'])]);

        if (!$model) {
            throw new CHttpException(404, 'Регион не найден');
        }

        // найдем id городов текущего региона
        $towns = Yii::app()->db->createCommand()
            ->select('id')
            ->from('{{town}}')
            ->where('regionId=:regionId', [':regionId' => $model->id])
            ->queryColumn();

        $criteria = new CDbCriteria();

        $criteria->order = 'rating DESC, karma DESC';
        $criteria->with = ['settings', 'town', 'town.region', 'categories', 'answersCount'];
        $criteria->addColumnCondition(['active100' => 1]);
        $criteria->addColumnCondition(['avatar!' => '']);
        $criteria->addInCondition('t.townId', $towns);
        $criteria->addCondition('role = ' . User::ROLE_JURIST);

        $yuristsDataProvider = new CActiveDataProvider('User', [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->render('view', [
            'model' => $model,
            'yuristsDataProvider' => $yuristsDataProvider,
        ]);
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $regionsRows = Yii::app()->db->cache(3600)->createCommand()
            ->select('r.id, r.name regionName, r.alias regionAlias, c.id countryId, c.name countryName, c.alias countryAlias')
            ->from('{{region}} r')
            ->leftJoin('{{country}} c', 'c.id = r.countryId')
            ->order('c.id asc, r.name')
            ->queryAll();

        $regionsArray = [];

        foreach ($regionsRows as $region) {
            $regionsArray[$region['countryAlias']][] = $region;
        }

        $this->render('index', [
            'regions' => $regionsArray,
        ]);
    }

    public function actionCountry($countryAlias)
    {
        $this->layout = '/frontend/catalog';

        $country = Country::model()->findByAttributes(['alias' => $countryAlias]);

        if (!($country instanceof Country)) {
            throw new CHttpException(404, 'Страна не найдена');
        }

        $regionsRows = Yii::app()->db->cache(0)->createCommand()
            ->select('r.id, r.name regionName, r.alias regionAlias, c.id countryId, c.name countryName, c.alias countryAlias')
            ->from('{{region}} r')
            ->leftJoin('{{country}} c', 'c.id = r.countryId')
            ->where('c.alias = :alias', [':alias' => $countryAlias])
            ->order('c.id asc, r.name')
            ->queryAll();

        $criteria = new CDbCriteria();

        $criteria->order = 'rating DESC, karma DESC';
        $criteria->with = ['settings', 'town', 'town.region', 'categories', 'answersCount'];
        $criteria->addColumnCondition(['active100' => 1]);
        $criteria->addColumnCondition(['avatar!' => '']);
        $criteria->addCondition('role = ' . User::ROLE_JURIST);
        $criteria->limit = 20;

        $yuristsDataProvider = new CActiveDataProvider('User', [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);

        $this->render('country', [
            'regions' => $regionsRows,
            'country' => $country,
            'yuristsDataProvider' => $yuristsDataProvider,
        ]);
    }

    /**
     * Редирект со старых адресов типа region/russia/* на /yurist/russia/*.
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
                'regionAlias' => $regionAlias,
            ], true, 301);
        }

        if ($countryAlias && $regionAlias) {
            $this->redirect([
                '/region/view',
                'countryAlias' => $countryAlias,
                'regionAlias' => $regionAlias,
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

        $this->render('admin', [
            'model' => $model,
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param int $id the ID of the model to be loaded
     *
     * @return Region the loaded model
     *
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Region::model()->findByPk($id);
        if (null === $model) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     *
     * @param Region $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && 'region-form' === $_POST['ajax']) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
