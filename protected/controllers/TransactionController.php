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
        $transaction = new PartnerTransaction();
        $transaction->setScenario('pull');
        $transaction->comment = (!Yii::app()->user->isGuest) ? Yii::app()->user->phone : '';

        $criteria = new CDbCriteria();
        if (User::ROLE_JURIST == Yii::app()->user->role) {
            $transaction = new TransactionCampaign();
            $transaction->description = (!Yii::app()->user->isGuest) ? Yii::app()->user->phone : '';
            $criteria->addColumnCondition(['buyerId' => Yii::app()->user->id, 'status' => TransactionCampaign::STATUS_COMPLETE]);
            $transactionModelClass = TransactionCampaign::class;
            $transaction->sum = $transaction->sum / 100;
        } else {
            if (User::ROLE_PARTNER == Yii::app()->user->role) {
                $criteria->addColumnCondition(['partnerId' => Yii::app()->user->id, 'status' => PartnerTransaction::STATUS_COMPLETE]);
                $transactionModelClass = PartnerTransaction::class;
            } else {
                $criteria->addColumnCondition(['buyerId' => Yii::app()->user->id, 'status' => TransactionCampaign::STATUS_COMPLETE]);
                $transactionModelClass = TransactionCampaign::class;
            }
        }

        $criteria->order = 'id DESC';

        $dataProvider = new CActiveDataProvider($transactionModelClass, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $requestsCriteria = new CDbCriteria();
        $requestsCriteria->addColumnCondition(['partnerId' => Yii::app()->user->id, 'sum<' => 0]);
        $requestsCriteria->order = 'id DESC';

        $requestsDataProvider = new CActiveDataProvider(PartnerTransaction::class, [
            'criteria' => $requestsCriteria,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $currentUser = User::model()->findByPk(Yii::app()->user->id);

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

        // если это вебмастер, кешируем баланс, рассчитанный из транзакций вебмастера
        if ($cachedBalance = Yii::app()->cache->get('webmaster_' . Yii::app()->user->id . '_balance')) {
            $balance = $cachedBalance;
        } else {
            $balance = $currentUser->calculateWebmasterBalance();
            Yii::app()->cache->set('webmaster_' . Yii::app()->user->id . '_balance', $balance, 60);
        }
        $hold = $currentUser->calculateWebmasterHold();

        if (isset($_POST['App\models\PartnerTransaction'])) {
            $transaction->attributes = $_POST['App\models\PartnerTransaction'];
            $transaction->partnerId = Yii::app()->user->id;
            $transaction->status = PartnerTransaction::STATUS_PENDING;

            $transaction->validate();

            if (abs($transaction->sum) > ($balance)) {
                $transaction->addError('sum', 'Недостаточно средств');
            }

            if (abs($transaction->sum) < PartnerTransaction::MIN_WITHDRAW_REFERAL) {
                $transaction->addError('sum', 'Минимальная сумма для вывода - ' . PartnerTransaction::MIN_WITHDRAW_REFERAL . ' руб.');
            }

            // Проверяем, нет ли у текущего пользователя заявок на рассмотрении
            $pendingTransactions = Yii::app()->db->createCommand()
                    ->select('id')
                    ->from('{{partnerTransaction}}')
                    ->where('status=:status AND partnerId=:partnerId', [
                        ':status' => PartnerTransaction::STATUS_PENDING,
                        ':partnerId' => Yii::app()->user->id,
                    ])
                    ->limit(1)
                    ->queryAll();
            if (sizeof($pendingTransactions)) {
                $transaction->addError('comment', 'Невозможно создать заявку на вывод средств, т.к. у Вас уже есть активная заявка. Пожалуйста, дождитесь ее рассмотрения');
            }

            if (!$transaction->hasErrors()) {
                $transaction->sum = 0 - abs($transaction->sum);
                if ($transaction->save()) {
                    Yii::app()->user->setFlash('success', 'Заявка создана и отправлена на рассмотрение модератору');
                    $this->redirect(['/transaction/index']);
                }
            }
        }
        if (isset($_POST['App\models\TransactionCampaign'])) {
            $transaction->attributes = $_POST['App\models\TransactionCampaign'];
            $transaction->buyerId = Yii::app()->user->id;
            $transaction->sum = $transaction->sum * 100;
            $transaction->status = TransactionCampaign::STATUS_PENDING;
            $transaction->description = $transaction->description;
            $transaction->type = TransactionCampaign::TYPE_JURIST_MONEYOUT;

            $transaction->validate();

            if (abs($transaction->sum) > (Yii::app()->user->balance)) {
                $transaction->addError('sum', 'Недостаточно средств');
            }

            if (abs($transaction->sum) < TransactionCampaign::MIN_WITHDRAW) {
                $transaction->addError('sum', 'Минимальная сумма для вывода - ' . TransactionCampaign::MIN_WITHDRAW / 100 . ' руб.');
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
                $transaction->addError('description', 'Невозможно создать заявку на вывод средств, т.к. у Вас уже есть активная заявка. Пожалуйста, дождитесь ее рассмотрения');
            }

            if (!$transaction->hasErrors()) {
                $transaction->sum = 0 - abs($transaction->sum);
                if ($transaction->save()) {
                    $transaction->description = 'вывод средств с баланса на ' . $transaction->description;
                    $transaction->save();
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
            'transaction' => $transaction,
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
