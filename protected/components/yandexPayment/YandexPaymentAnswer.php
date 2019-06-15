<?php


class YandexPaymentAnswer implements YandexPaymentProcessorInterface
{
    private $answer;
    private $moneyTransaction;
    private $request;

    const SERVICE_COMMISSION = 0.3; // комиссия 100 Юристов, вычитаемая из благодарностей юристам

    public function __construct(int $answerId, YaPayConfirmRequest $request)
    {
        $this->answer = Answer::model()->findByPk($answerId);
        $this->moneyTransaction = new Money();
        $this->request = $request;
    }

    /**
     * Обработка платежа
     * @throws CHttpException
     */
    public function process(): bool
    {
        if (is_null($this->answer)) {
            return false;
        }

        $amount = $this->request->amount;

        // Доля юриста после вычета нашей комиссии
        $yuristBonus = $amount * (1-self::SERVICE_COMMISSION);

        $moneyTransaction = new Money();
        $moneyTransaction->accountId = 0; // Яндекс деньги
        $moneyTransaction->value = $amount;
        $moneyTransaction->type = Money::TYPE_INCOME;
        $moneyTransaction->direction = 505; // Благодарности юристам
        $moneyTransaction->datetime = date('Y-m-d');
        $moneyTransaction->comment = 'Благодарность юристу ' . CHtml::encode($this->answer->author->lastName);

        $yurist = $this->answer->author;
        $yurist->balance += $yuristBonus;

        $yuristTransaction = new TransactionCampaign();
        $yuristTransaction->sum = $yuristBonus;
        $yuristTransaction->buyerId = $yurist->id;
        $yuristTransaction->description = 'Благодарность за консультацию по вопросу ' . $this->answer->questionId;

        $saveTransaction = $moneyTransaction->dbConnection->beginTransaction();
        try {
            if ($yurist->save() && $moneyTransaction->save() && $yuristTransaction->save()) {
                $saveTransaction->commit();
                Yii::log('Пришло бабло благодарность юристу ' . $yurist->id . ' (' . $amount . ' руб.)', 'info', 'system.web');
                LoggerFactory::getLogger('db')->log('Благодарность юристу #' . $yurist->id . ') ' . $amount . ' руб.', 'User', $yurist->id);
                return true;
            } else {
                $saveTransaction->rollback();
                Yii::log('Ошибки: ' . print_r($yurist->errors, true) . ' ' . print_r($moneyTransaction->errors, true), 'error', 'system.web');
                return false;
            }
        } catch (Exception $e) {
            $saveTransaction->rollback();
            Yii::log('Ошибка при благодарности ' . $yurist->id . ' (' . $amount . ' руб.)', 'error', 'system.web');

            throw new CHttpException(500, 'Не удалось сохранить благодарность');
        }
    }
}