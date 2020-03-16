<?php

use App\models\Money;
use App\models\TransactionCampaign;
use App\models\User;
use App\models\YaPayConfirmRequest;

class YandexPaymentUser implements YandexPaymentProcessorInterface
{
    private $userId;
    private $user;
    private $transaction;
    private $moneyTransaction;
    private $request;

    public function __construct(int $userId, YaPayConfirmRequest $request)
    {
        $this->userId = $userId;
        $this->request = $request;
        $this->user = User::model()->findByPk($this->userId);
        $this->transaction = new TransactionCampaign();
        $this->moneyTransaction = new Money();
    }

    /**
     * Обработка запроса.
     *
     * @return bool
     *
     * @throws CHttpException
     */
    public function process(): bool
    {
        if (is_null($this->user)) {
            return false;
        }
        $amount = $this->request->amount * 100;
        Yii::log('Пополняем баланс пользователя: ' . $this->user->getShortName(), 'info', 'system.web');
        $this->user->balance += $amount;
        $transaction = new TransactionCampaign();
        $transaction->buyerId = $this->user->id;
        $transaction->sum = $amount;
        $transaction->description = 'Пополнение баланса пользователя';

        $moneyTransaction = new Money();
        $moneyTransaction->accountId = 0; // Яндекс деньги
        $moneyTransaction->value = $amount;
        $moneyTransaction->type = Money::TYPE_INCOME;
        $moneyTransaction->direction = 501;
        $moneyTransaction->datetime = date('Y-m-d');
        $moneyTransaction->comment = 'Пополнение баланса пользователя ' . $this->user->id;

        $saveTransaction = $transaction->dbConnection->beginTransaction();
        try {
            if ($transaction->save() && $moneyTransaction->save() && $this->user->save(false)) {
                $saveTransaction->commit();
                Yii::log('Транзакция сохранена, id: ' . $transaction->id, 'info', 'system.web');
                Yii::log('Пришло бабло от пользователя ' . $this->user->id . ' (' . MoneyFormat::rubles($amount) . ' руб.)', 'info', 'system.web');
                LoggerFactory::getLogger('db')->log('Пополнение баланса пользователя #' . $this->user->id . '(' . $this->user->getShortName() . ') на ' . MoneyFormat::rubles($amount) . ' руб.', 'User', $this->user->id);

                return true;
            } else {
                $saveTransaction->rollback();

                return false;
            }
        } catch (Exception $e) {
            $saveTransaction->rollback();
            Yii::log('Ошибка при пополнении баланса пользователя ' . $this->user->id . ' (' . $amount . ' руб.)', 'error', 'system.web');

            throw new CHttpException(500, 'Не удалось пополнить баланс пользователя');
        }
    }
}
