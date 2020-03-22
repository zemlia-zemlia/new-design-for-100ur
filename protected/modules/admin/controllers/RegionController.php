<?php

use App\models\Region;
use App\models\User;
use App\modules\admin\controllers\AbstractAdminController;

class RegionController extends AbstractAdminController
{

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
            ['allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => ['index', 'view', 'create', 'update', 'admin', 'delete', 'setPrice'],
                'users' => ['@'],
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_ROOT . ')',
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
        if (!isset($_GET['regionAlias'])) {
            throw new CHttpException(404, 'Регион не найден');
        }

        $model = Region::model()->findByAttributes(['alias' => CHtml::encode($_GET['regionAlias'])]);

        if (!$model) {
            throw new CHttpException(404, 'Регион не найден');
        }

        $townsArray = Yii::app()->db->cache(0)->createCommand()
                ->order('t.size DESC, t.name ASC')
                ->select('t.id, t.name, t.isCapital, t.size, t.lat, t.lng, LENGTH(t.description1) hasDesc1, LENGTH(t.description2) hasDesc2, LENGTH(t.seoTitle) hasSeoTitle, LENGTH(t.seoDescription) hasSeoDescription, LENGTH(t.seoKeywords) hasSeoKeywords, t.buyPrice')
                ->from('{{town}} t')
                ->group('t.id')
                ->where('t.regionId = :regionId', [':regionId' => $model->id])
                ->queryAll();

        $this->render('view', [
            'model' => $model,
            'townsArray' => $townsArray,
        ]);
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $regionsRows = Yii::app()->db->cache(30)->createCommand()
                ->select('r.id, r.name regionName, r.alias regionAlias, c.id countryId, c.name countryName, c.alias countryAlias, r.buyPrice')
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

    /**
     * Устанавливает базовую цену покупки лида для региона.
     */
    public function actionSetPrice()
    {
        $price = (int) Yii::app()->request->getPost('price');
        $regionId = Yii::app()->request->getPost('id');

        $model = Region::model()->findByPk($regionId);
        if (!$model) {
            throw new CHttpException('Регион не найден', 404);
        }

        if ($price < 0) {
            throw new CHttpException('Цена не может быть меньше нуля', 400);
        }

        $changePriceResult = Yii::app()->db->createCommand()
                ->update('{{region}}', ['buyPrice' => $price], 'id=:id', [':id' => $model->id]);

        if ($changePriceResult > 0) {
            echo json_encode(['code' => 1, 'regionId' => $model->id]);
        } else {
            echo json_encode(['code' => 0, 'regionId' => $model->id]);
        }
    }
}
