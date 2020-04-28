<?php

use App\extensions\Logger\LoggerFactory;
use App\models\Answer;
use App\models\Chat;
use App\models\Money;
use App\models\TransactionCampaign;
use App\models\YaPayConfirmRequest;

class YandexPaymentChat implements YandexPaymentProcessorInterface
{
    /** @var Chat */
    private $chat;

    private $moneyTransaction;
    private $request;

    const SERVICE_COMMISSION = 0.3; // комиссия 100 Юристов, вычитаемая из благодарностей юристам

    public function __construct(int $answerId, YaPayConfirmRequest $request)
    {
        $this->chat = Chat::model()->findByPk($answerId);
        $this->moneyTransaction = new Money();
        $this->request = $request;
    }

    /**
     * Обработка платежа.
     *
     * @throws CHttpException
     */
    public function process(): bool
    {
        if (is_null($this->chat)) {
            return false;
        }

        $amount = $this->request->amount * 100;

        // Доля юриста после вычета нашей комиссии
        $yuristBonus = $amount * (1 - self::SERVICE_COMMISSION);

        $moneyTransaction = new Money();
        $moneyTransaction->accountId = 0; // Яндекс деньги
        $moneyTransaction->value = $amount;
        $moneyTransaction->type = Money::TYPE_INCOME;
        $moneyTransaction->direction = 505; // Благодарности юристам
        $moneyTransaction->datetime = date('Y-m-d');
        $moneyTransaction->comment = 'Благодарность юристу за чат ' . CHtml::encode($this->chat->chat_id);

        /** @var User */
        $yurist = User::model()->findByPk($this->chat->lawyer_id);

        $yuristTransaction = new TransactionCampaign();
        $yuristTransaction->sum = $yuristBonus;
        $yuristTransaction->buyerId = $yurist->id;
        $yuristTransaction->description = 'Благодарность за консультацию В чате ' . $this->chat->chat_id . ' HOLD';
        $yuristTransaction->status = TransactionCampaign::STATUS_HOLD;
        $saveTransaction = $moneyTransaction->dbConnection->beginTransaction();
        try {
            if ($moneyTransaction->save() && $yuristTransaction->save()) {
                $saveTransaction->commit();
                Yii::log('ХОЛДИМ бабло благодарность юристу ' . $yurist->id . ' (' . MoneyFormat::rubles($amount) . ' руб.)', 'info', 'system.web');
                LoggerFactory::getLogger('db')->log('Благодарность юристу #' . $yurist->id . ') ' . MoneyFormat::rubles($amount) . ' руб.', 'User', $yurist->id);
                $this->chat->is_payed = 1;
                $this->chat->transaction_id = $yuristTransaction->id;
                $this->chat->save();
                return true;
            } else {
                $saveTransaction->rollback();
                Yii::log('Ошибки: ' . print_r($yurist->errors, true) . ' ' . print_r($moneyTransaction->errors, true), 'error', 'system.web');

                return false;
            }
        } catch (Exception $e) {
            $saveTransaction->rollback();
            Yii::log('Ошибка при благодарности ' . $yurist->id . ' (' . MoneyFormat::rubles($amount) . ' руб.)', 'error', 'system.web');

            throw new CHttpException(500, 'Не удалось сохранить благодарность');
        }
    }
}
