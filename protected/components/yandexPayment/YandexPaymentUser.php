<?php

class YandexPaymentUser implements YandexPaymentProcessorInterface
{
    private $userId;
    private $user;
    private $transaction;
    private $moneyTransaction;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
        $this->user = User::model()->findByPk($this->userId);
        $this->transaction = new TransactionCampaign();
        $this->moneyTransaction = new Money();
    }

    public function process()
    {
        // TODO: Implement process() method.
    }
}