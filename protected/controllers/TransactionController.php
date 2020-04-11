<?php

use App\extensions\Logger\LoggerFactory;
use App\models\PartnerTransaction;
use App\models\TransactionCampaign;
use App\models\User;
use App\models\UserActivity;

/**
 * Страницы раздела транзакций пользователя.
 */
class TransactionController extends Controller
{
    public $layout = '//frontend/question';

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
            ['allow', // allow all users to perform 'index' and 'view' actions
                'actions' => ['index', 'createSuccess'],
                'users' => ['@'],
            ],
            ['deny', // deny all users
                'users' => ['*'],
            ],
        ];
    }

    /**
     * Список транзакций.
     */
    public function actionIndex()
    {

        if (Yii::app()->user->role == User::ROLE_PARTNER) {
            // У вебмастера транзакции отображаются в его модуле
            return $this->redirect('/webmaster/transaction');
        }

        $criteria = new CDbCriteria();

        if (User::ROLE_JURIST == Yii::app()->user->role) {
            $newTransaction = new TransactionCampaign();
            $newTransaction->description = (!Yii::app()->user->isGuest) ? Yii::app()->user->phone : '';
            $criteria->addColumnCondition(['buyerId' => Yii::app()->user->id, 'status' => TransactionCampaign::STATUS_COMPLETE]);
            $transactionModelClass = TransactionCampaign::class;
            $newTransaction->sum = $newTransaction->sum / 100;
        } else {
            $criteria->addColumnCondition(['buyerId' => Yii::app()->user->id, 'status' => TransactionCampaign::STATUS_COMPLETE]);
            $transactionModelClass = TransactionCampaign::class;
        }

        if (isset($newTransaction)) {
            $newTransaction->setScenario('pull');
            $newTransaction->comment = (!Yii::app()->user->isGuest) ? Yii::app()->user->phone : '';
        }

        $criteria->order = 'id DESC';

        $dataProvider = new CActiveDataProvider($transactionModelClass, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $currentUser = User::model()->findByPk(Yii::app()->user->id);

        $requestsDataProvider = null;

        if (User::ROLE_JURIST == $currentUser->role) {
            $requestsCriteria = new CDbCriteria();
            $requestsCriteria->addColumnCondition(['buyerId' => Yii::app()->user->id, 'sum<' => 0, 'status' => TransactionCampaign::STATUS_PENDING]);
            $requestsCriteria->order = 'id DESC';

            $requestsDataProvider = new CActiveDataProvider(TransactionCampaign::class, [
                'criteria' => $requestsCriteria,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        }


        $balance = $currentUser->calculateWebmasterBalance();
        Yii::app()->cache->set('webmaster_' . Yii::app()->user->id . '_balance', $balance, 60);

        $hold = $currentUser->calculateWebmasterHold();

        if (isset($_POST['App_models_TransactionCampaign'])) {
            $newTransaction = new TransactionCampaign();
            $newTransaction->attributes = $_POST['App_models_TransactionCampaign'];
            $newTransaction->buyerId = Yii::app()->user->id;
            $newTransaction->sum = $newTransaction->sum * 100;
            $newTransaction->status = TransactionCampaign::STATUS_PENDING;
            $newTransaction->type = TransactionCampaign::TYPE_JURIST_MONEYOUT;

            $newTransaction->validate();

            if (abs($newTransaction->sum) > (Yii::app()->user->balance)) {
                $newTransaction->addError('sum', 'Недостаточно средств');
            }

            if (abs($newTransaction->sum) < TransactionCampaign::MIN_WITHDRAW) {
                $newTransaction->addError('sum', 'Минимальная сумма для вывода - ' . TransactionCampaign::MIN_WITHDRAW / 100 . ' руб.');
            }

            // Проверяем, нет ли у текущего пользователя заявок на рассмотрении
            $pendingTransactions = Yii::app()->db->createCommand()
                ->select('id')
                ->from('{{transactionCampaign}}')
                ->where('status=:status AND buyerId=:buyerId', [
                    ':status' => TransactionCampaign::STATUS_PENDING,
                    ':buyerId' => Yii::app()->user->id,
                ])
                ->limit(1)
                ->queryAll();
            if (sizeof($pendingTransactions)) {
                $newTransaction->addError('description', 'Невозможно создать заявку на вывод средств, т.к. у Вас уже есть активная заявка. Пожалуйста, дождитесь ее рассмотрения');
            }

            if (!$newTransaction->hasErrors()) {
                $newTransaction->sum = 0 - abs($newTransaction->sum);
                if ($newTransaction->save()) {
                    $newTransaction->description = 'вывод средств с баланса на ' . $newTransaction->description;
                    $newTransaction->save();
                    Yii::app()->user->setFlash('success', 'Заявка создана и отправлена на рассмотрение модератору');
                    LoggerFactory::getLogger('db')->log('Пользователь #' . Yii::app()->user->id . ' (' . Yii::app()->user->getShortName() . ') запросил вывод средств', 'User', Yii::app()->user->id);
                    $this->redirect(['/transaction/index']);
                }
            }
        }

        if (1 == $_GET['created']) {
            $justCreated = true;
        } else {
            $justCreated = false;
        }

        echo $this->render('index', [
            'dataProvider' => $dataProvider,
            'balance' => $balance,
            'hold' => $hold,
            'transaction' => $newTransaction,
            'justCreated' => $justCreated,
            'requestsDataProvider' => $requestsDataProvider,
        ]);
    }

    /**
     * Страница успешной отправки запроса на вывод средств.
     */
    public function actionCreateSuccess()
    {
        LoggerFactory::getLogger('db')->log('Пользователь #' . Yii::app()->user->id . ' пополнил баланс', 'User', Yii::app()->user->id);
        (new UserActivity())->logActivity(Yii::app()->user->getModel(), UserActivity::ACTION_TOPUP_BALANCE);

        echo $this->render('createSuccess');
    }
}
