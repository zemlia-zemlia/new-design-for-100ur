<?php

use App\models\Campaign;
use App\models\Region;
use App\models\User;

class CampaignController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     *             using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//lk/main';

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
            ['allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['index', 'view', 'create'],
                'users' => ['@'],
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_BUYER . ')',
            ],
            ['allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['update'],
                'users' => ['@'],
                'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_ROOT . ')',
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
    public function actionView($id)
    {
        $campaign = Campaign::model()->with('transactions')->findByPk($id);
        $transactionsDataProvider = new CArrayDataProvider($campaign->transactions);

        if (!(User::ROLE_ROOT == Yii::app()->user->role || (User::ROLE_BUYER == Yii::app()->user->role && $campaign->buyerId == Yii::app()->user->id))) {
            throw new CHttpException(403, 'Вы не можете просматривать данную кампанию');
        }

        $this->render('view', [
            'model' => $campaign,
            'transactionsDataProvider' => $transactionsDataProvider,
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $this->layout = '//lk/main';
        $model = new Campaign();

        $model->active = Campaign::ACTIVE_MODERATION; // статус по умолчанию - На рассмотрении

        if (isset($_POST['App\models\Campaign'])) {
            $model->attributes = $_POST['App\models\Campaign'];
            if (User::ROLE_ROOT != Yii::app()->user->role) {
                $model->buyerId = Yii::app()->user->id;
            }
            $model->price = 9000;
            $model->brakPercent = 20;

            // Проверим, не создано ли у этого пользователя других кампаний из этого города и региона
            $existingCampaignsFromSameRegionCommand = Yii::app()->db->createCommand()
                    ->select('id')
                    ->from('{{campaign}}')
                    ->where('buyerId=:userId', [':userId' => Yii::app()->user->id]);

            if ($model->townId) {
                $existingCampaignsFromSameRegionCommand->andWhere('townId=:townId', [':townId' => $model->townId]);
            }

            if ($model->regionId) {
                $existingCampaignsFromSameRegionCommand->andWhere('regionId=:regionId', [':regionId' => $model->regionId]);
            }

            $existingCampaignsFromSameRegion = $existingCampaignsFromSameRegionCommand->queryAll();

            if (sizeof($existingCampaignsFromSameRegion)) {
                $model->addError('townId', 'Вы уже создали кампанию в этом городе/регионе');
            }

            if (!$model->errors && $model->save()) {
                $this->redirect(['/buyer/buyer/campaign', 'id' => $model->id]);
            }
        }

        $regions = ['0' => 'Не выбран'] + Region::getAllRegions();

        $this->render('create', [
            'model' => $model,
            'regions' => $regions,
        ]);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['App\models\Campaign'])) {
            $model->attributes = $_POST['App\models\Campaign'];

            if ($model->save()) {
                $this->redirect(['/buyer/buyer/campaign', 'id' => $model->id]);
            }
        }

        $regions = ['0' => 'Не выбран'] + Region::getAllRegions();

        $this->render('update', [
            'model' => $model,
            'regions' => $regions,
        ]);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     *
     * @param int $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['admin']);
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider(Campaign::class);
        $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Campaign('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['App\models\Campaign'])) {
            $model->attributes = $_GET['App\models\Campaign'];
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
     * @return Campaign the loaded model
     *
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Campaign::model()->findByPk($id);
        if (null === $model) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     *
     * @param Campaign $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && 'campaign-form' === $_POST['ajax']) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
