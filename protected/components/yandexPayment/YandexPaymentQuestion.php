<?php


class YandexPaymentQuestion implements YandexPaymentProcessorInterface
{
    private $question;
    private $moneyTransaction;
    private $request;

    public function __construct(int $questioId, YaPayConfirmRequest $request)
    {
        $this->question = Question::model()->findByPk($questioId);
        $this->moneyTransaction = new Money();
        $this->request = $request;
    }

    /**
     * Обработка платежа
     * @throws CHttpException
     */
    public function process(): bool
    {
        if (is_null($this->question)) {
            return false;
        }

        $amount = $this->request->amount * 100;
        $amountBeforeCommission = $this->request->withdraw_amount;

        $this->question->payed = 1;
        $this->question->price = $amountBeforeCommission;

        $moneyTransaction = new Money();
        $moneyTransaction->accountId = 0; // Яндекс деньги
        $moneyTransaction->value = $amount;
        $moneyTransaction->type = Money::TYPE_INCOME;
        $moneyTransaction->direction = 504; // VIP вопросы
        $moneyTransaction->datetime = date('Y-m-d');
        $moneyTransaction->comment = 'Оплата вопроса ' . $this->question->id;

        $saveTransaction = $moneyTransaction->dbConnection->beginTransaction();
        try {
            if ($this->question->save() && $moneyTransaction->save()) {
                $saveTransaction->commit();
                Yii::log('Вопрос сохранен, id: ' . $this->question->id, 'info', 'system.web');
                Yii::log('Пришло бабло за вопрос ' . $this->question->id . ' (' . MoneyFormat::rubles($amount) . ' руб.)', 'info', 'system.web');
                LoggerFactory::getLogger('db')->log('Оплата вопроса #' . $this->question->id . ') на ' . MoneyFormat::rubles($amount) . ' руб.', 'Question', $this->question->id);
                return true;
            } else {
                $saveTransaction->rollback();
                Yii::log('Ошибки: ' . print_r($this->question->errors, true) . ' ' . print_r($moneyTransaction->errors, true), 'error', 'system.web');
                return false;
            }
        } catch (Exception $e) {
            $saveTransaction->rollback();
            Yii::log('Ошибка при оплате вопроса ' . $this->question->id . ' (' . MoneyFormat::rubles($amount) . ' руб.)', 'error', 'system.web');

            throw new CHttpException(500, 'Не удалось оплатить вопрос');
        }
    }
}