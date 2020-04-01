<?php

use App\models\Order;
use App\modules\admin\controllers\AbstractAdminController;

class OrderController extends AbstractAdminController
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
            ['allow', // allow all users to perform 'index' and 'view' actions
                'actions' => ['index', 'view'],
                'users' => ['*'],
            ],
            ['allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['update', 'delete'],
                'expression' => 'Yii::app()->user->checkAccess(App\models\User::ROLE_ROOT)',
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
        $this->render('view', [
            'order' => $this->loadModel($id),
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Order();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['App_models_Order'])) {
            $model->attributes = $_POST['App_models_Order'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $this->render('create', [
            'model' => $model,
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

        if (isset($_POST['App_models_Order'])) {
            $model->attributes = $_POST['App_models_Order'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $this->render('update', [
            'model' => $model,
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
        $ordersCriteria = new CDbCriteria();

        $ordersCriteria->order = 't.id DESC';
        $ordersCriteria->with = ['author', 'jurist', 'responsesCount'];

        $ordersDataProvider = new CActiveDataProvider(Order::class, [
            'criteria' => $ordersCriteria,
        ]);

        // извлечем неархивные заказы по статусам
        $ordersByStatus = [];
        $orderStatsRows = Yii::app()->db->createCommand()
                ->select('id, status')
                ->from('{{order}}')
                ->where('status!=:stat', [':stat' => Order::STATUS_ARCHIVE])
                ->queryAll();
        foreach ($orderStatsRows as $row) {
            $ordersByStatus[$row['status']][] = $row['id'];
        }

        $this->render('index', [
            'ordersDataProvider' => $ordersDataProvider,
            'ordersByStatus' => $ordersByStatus,
        ]);
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Order('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['App_models_Order'])) {
            $model->attributes = $_GET['App_models_Order'];
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
     * @return Order the loaded model
     *
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Order::model()->findByPk($id);
        if (null === $model) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     *
     * @param Order $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && 'order-form' === $_POST['ajax']) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
