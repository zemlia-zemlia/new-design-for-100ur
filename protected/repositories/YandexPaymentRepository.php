<?php


namespace App\repositories;


use App\models\YandexPayment;
use Yii;

class YandexPaymentRepository
{
    /**
     * @param string $operationId
     * @return array|false
     * @throws \CException
     */
    public function findProcessedPayment(string $operationId)
    {
        return Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{yandex_payment}}')
            ->where('operation_id = :id AND status=:status', [
                ':id' => $operationId,
                ':status' => YandexPayment::STATUS_PROCESSED,
            ])
            ->limit(1)
            ->queryRow();
    }

}
