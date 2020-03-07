<?php

namespace buyer\services;

use CException;
use DateTime;
use Lead;
use Yii;

/**
 * Класс для получения различных статистик.
 */
class StatisticsService
{
    /**
     * @var int
     */
    private $userId;

    private function getSoldLeadStatuses(): array
    {
        return [
            Lead::LEAD_STATUS_SENT,
            Lead::LEAD_STATUS_NABRAK,
            Lead::LEAD_STATUS_RETURN,
        ];
    }

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Возвращает количество лидов, проданных покупателю (с учетом статусов).
     *
     * @param DateTime|null $fromDate
     *
     * @return int
     *
     * @throws CException
     */
    public function getSoldLeadsCount(DateTime $fromDate = null): int
    {
        $queryCommand = Yii::app()->db->createCommand()
            ->select('count(*) counter')
            ->from('{{lead}} l')
            ->where('l.buyerId = :userId', [
                ':userId' => $this->userId,
            ])
            ->andWhere('leadStatus IN (' . implode(',', $this->getSoldLeadStatuses()) . ')');

        if ($fromDate) {
            $queryCommand->andWhere('l.question_date >=:fromDate', [
                ':fromDate' => $fromDate->format('Y-m-d'),
            ]);
        }

        return $queryCommand->queryScalar();
    }

    /**
     * Сумма расходов на купленные лиды в копейках.
     *
     * @param DateTime|null $fromDate
     *
     * @return int
     *
     * @throws CException
     */
    public function getTotalExpences(DateTime $fromDate = null): int
    {
        $queryCommand = Yii::app()->db->createCommand()
            ->select('sum(price) sum_price')
            ->from('{{lead}} l')
            ->where('l.buyerId = :userId', [
                ':userId' => $this->userId,
            ])
            ->andWhere('leadStatus IN (' . implode(',', $this->getSoldLeadStatuses()) . ')');

        if ($fromDate) {
            $queryCommand->andWhere('l.question_date >=:fromDate', [
                ':fromDate' => $fromDate->format('Y-m-d'),
            ]);
        }
        $expencesResult = $queryCommand->queryScalar();

        return $expencesResult ?? 0;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     *
     * @return StatisticsService
     */
    public function setUserId($userId): StatisticsService
    {
        $this->userId = $userId;

        return $this;
    }
}
