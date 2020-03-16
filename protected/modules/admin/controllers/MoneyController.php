<?php

use App\helpers\DateHelper;
use App\models\Money;
use App\models\MoneyMove;
use App\models\User;

class MoneyController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     *             using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//admin/main';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
                'accessControl', // perform access control for CRUD operations
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
                        'actions' => ['index', 'view', 'report', 'create', 'addTransaction', 'update', 'delete'],
                        'expression' => 'Yii::app()->user->checkAccess(' . User::ROLE_ROOT . ')',
                ],
                ['deny',  // deny all users
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
                    'model' => $this->loadModel($id),
            ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Money();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['App\models\Money'])) {
            $model->attributes = $_POST['App\models\Money'];
            $model->datetime = DateHelper::invertDate($model->datetime);
            $model->value *= 100;
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            } else {
                $model->datetime = DateHelper::invertDate($model->datetime);
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

        if (isset($_POST['App\models\Money'])) {
            $model->attributes = $_POST['App\models\Money'];
            $model->datetime = DateHelper::invertDate($model->datetime);
            $model->value *= 100;

            if (Money::DIRECTION_INTERNAL == $model->direction) {
                $model->isInternal = 1;
            } else {
                $model->isInternal = 0;
            }

            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }
        $model->datetime = DateHelper::invertDate($model->datetime);

        $this->render('update', [
                    'model' => $model,
            ]);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['index']);
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        // рассчитаем баланс каждого счета исходя из транзакций по нему
        $balances = [];

        $balanceRows = Yii::app()->db->createCommand()
                    ->select('type, value, accountId')
                    ->from('{{money}}')
                    ->queryAll();
        foreach ($balanceRows as $row) {
            if (Money::TYPE_INCOME == $row['type']) {
                $balances[$row['accountId']] += $row['value'];
            } else {
                $balances[$row['accountId']] -= $row['value'];
            }
        }

        $accounts = Money::getAccountsArray();

        // модель для формы поиска
        $searchModel = new Money();

        // если использовался поиск, найдем только нужные транзакции
        if (isset($_GET['App\models\Money'])) {
            $searchModel->attributes = $_GET['App\models\Money'];
            $dataProvider = $searchModel->search();
        } else {
            $dataProvider = new CActiveDataProvider('App\models\Money', ['criteria' => [
                    'order' => 'datetime DESC, id DESC',
                    ],
                    'pagination' => [
                        'pageSize' => 20,
                    ],
                ]);
        }

        $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'balances' => $balances,
                    'accounts' => $accounts,
                    'searchModel' => $searchModel,
            ]);
    }

    /**
     *   Финансовый отчет за период.
     */
    public function actionReport()
    {
        $searchModel = new Money();

        $searchModel->setScenario('search');

        if (isset($_GET['App\models\Money'])) {
            // если используется форма поиска по контактам
            $searchModel->attributes = $_GET['App\models\Money'];
        } else {
            // если не задан диапазон дат, построим отчет за последние 30 дней
            $searchModel->date1 = date('d-m-Y', time() - 30 * 86400);
            $searchModel->date2 = date('d-m-Y');
        }

        // сырой набор записей о транзакциях
        $reportDataSet = $searchModel->getReportSet();

        // набор, отфильтрованный по типам и статьям доходов/расходов
        $reportDataSetFiltered = Money::filterReportSet($reportDataSet);

        $this->render('report', [
                'searchModel' => $searchModel,
                'reportDataSetFiltered' => $reportDataSetFiltered,
            ]);
    }

    /**
     * Добавление нового перевода между счетами
     * Переводы между счетами - это 2 транзакции, которые не учитываются в доходах
     * и расходах. Обозначаются особым флагом.
     */
    public function actionAddTransaction()
    {
        $model = new MoneyMove();
        $moneyRecord1 = new Money();
        $moneyRecord2 = new Money();

        if (isset($_POST['App\models\MoneyMove'])) {
            $model->attributes = $_POST['App\models\MoneyMove'];

            // сначала проверим правильность заполнения формы
            if ($model->validate()) {
                $model->datetime = DateHelper::invertDate($model->datetime);

                // создаем 2 транзакции (для перевода между счетами - снятие и пополнение)

                $model->sum *= 100;

                $moneyRecord1->isInternal = 1;
                $moneyRecord2->isInternal = 1;

                $moneyRecord1->value = $moneyRecord2->value = $model->sum;
                $moneyRecord1->datetime = $moneyRecord2->datetime = $model->datetime;
                $moneyRecord1->accountId = $model->fromAccount;
                $moneyRecord2->accountId = $model->toAccount;
                $moneyRecord1->comment = $moneyRecord2->comment = $model->comment;
                $moneyRecord1->type = Money::TYPE_EXPENCE;
                $moneyRecord2->type = Money::TYPE_INCOME;
                $moneyRecord2->direction = $moneyRecord1->direction = Money::DIRECTION_INTERNAL; // код внутренних транзакций

                if ($moneyRecord1->save() && $moneyRecord2->save()) {
                    $this->redirect(['index']);
                } else {
                    $model->datetime = DateHelper::invertDate($model->datetime);
                    $model->sum /= 100;
                }
            }
        }

        $this->render('addTransaction', [
                'model' => $model,
                'moneyRecord1' => $moneyRecord1,
                'moneyRecord2' => $moneyRecord2,
            ]);
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Money('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['App\models\Money'])) {
            $model->attributes = $_GET['App\models\Money'];
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
     * @return Money the loaded model
     *
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Money::model()->findByPk($id);
        if (null === $model) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     *
     * @param Money $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && 'money-form' === $_POST['ajax']) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
