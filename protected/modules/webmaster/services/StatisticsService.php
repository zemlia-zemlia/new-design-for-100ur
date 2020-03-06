<?php

namespace webmaster\services;

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

    public function getUserSources(): array
    {
        $sources = Yii::app()->db->createCommand()
            ->select('id')
            ->from('{{leadsource}}')
            ->where('userId=:userId', [':userId' => $this->userId])
            ->queryAll();
        $sourceIds = [];
        foreach ($sources as $ids) {
            $sourceIds[] = $ids['id'];
        }

        return $sourceIds;
    }

    /**
     * @param DateTime|null $fromDate
     *
     * @return int
     *
     * @throws CException
     */
    public function getAllLeadsCount(DateTime $fromDate = null): int
    {
        $queryCommand = Yii::app()->db->createCommand()
            ->select('count(*) counter')
            ->from('{{lead}} l')
            ->leftJoin('{{leadsource}} s', 'l.sourceId = s.id')
            ->where('s.userId = :userId', [
                ':userId' => $this->userId,
            ]);

        if ($fromDate) {
            $queryCommand->andWhere('l.question_date >=:fromDate', [
                ':fromDate' => $fromDate->format('Y-m-d'),
            ]);
        }

        return $queryCommand->queryScalar();
    }

    /**
     * Статистика лидов вебмастера по датам
     *
     * @param string $groupByFieldName
     * @param DateTime|null $fromDate
     * @param string $order asc|desc
     *
     * @return array
     *
     * @throws CException
     */
    public function getLeadsStatisticsByField($groupByFieldName = 'lead_date', DateTime $fromDate = null, $order = 'asc'): array
    {
        $rawStatistics = $this->getLeadsWithStatusesAndRegions($fromDate);

        $statsByField = [];
        $totalLeadsCount = [];
        $soldLeadsCount = [];
        $soldLeadsPercent = [];
        $notSoldLeadsCount = [];
        $brakLeadsCount = [];
        $brakPercents = [];
        $duplicateLeadsCount = [];
        $totalRevenue = [];
        $averageLeadPrice = [];

        foreach ($rawStatistics as $row) {
            ++$totalLeadsCount[$row[$groupByFieldName]];

            if (in_array($row['leadStatus'], $this->getSoldLeadStatuses())) {
                ++$soldLeadsCount[$row[$groupByFieldName]];
                $totalRevenue[$row[$groupByFieldName]] += $row['buyPrice'];
            }

            if (Lead::LEAD_STATUS_DEFAULT == $row['leadStatus']) {
                ++$notSoldLeadsCount[$row[$groupByFieldName]];
            }

            if (Lead::LEAD_STATUS_BRAK == $row['leadStatus']) {
                ++$brakLeadsCount[$row[$groupByFieldName]];
            }

            if (Lead::LEAD_STATUS_DUPLICATE == $row['leadStatus']) {
                ++$duplicateLeadsCount[$row[$groupByFieldName]];
            }
        }

        foreach ($brakLeadsCount as $fieldToGroup => $brakLeadOnDate) {
            $brakPercents[$fieldToGroup] = ($soldLeadsCount[$fieldToGroup] > 0) ? round(($brakLeadOnDate / $soldLeadsCount[$fieldToGroup]) * 100, 1) : 0;
        }

        foreach ($soldLeadsCount as $fieldToGroup => $soldLeadsOnDate) {
            $soldLeadsPercent[$fieldToGroup] = ($totalLeadsCount[$fieldToGroup] > 0) ? round(($soldLeadsOnDate / $totalLeadsCount[$fieldToGroup]) * 100, 1) : 0;
            $averageLeadPrice[$fieldToGroup] = ($soldLeadsOnDate > 0) ? round($totalRevenue[$fieldToGroup] / $soldLeadsOnDate) : 0;
        }

        foreach ($totalLeadsCount as $fieldToGroup => $totalLeadsForDate) {
            $statsByField['data'][$fieldToGroup] = [
                'totalLeads' => $totalLeadsForDate,
                'soldLeads' => $soldLeadsCount[$fieldToGroup] ?? 0,
                'notSoldLeads' => $notSoldLeadsCount[$fieldToGroup] ?? 0,
                'soldLeadsPercent' => $soldLeadsPercent[$fieldToGroup] ?? 0,
                'brakLeads' => $brakLeadsCount[$fieldToGroup] ?? 0,
                'duplicateLeads' => $duplicateLeadsCount[$fieldToGroup] ?? 0,
                'brakPercents' => $brakPercents[$fieldToGroup] ?? 0,
                'averageLeadPrice' => $averageLeadPrice[$fieldToGroup],
                'totalRevenue' => $totalRevenue[$fieldToGroup],
            ];
            $statsByField['totalLeads'] += $totalLeadsForDate;
            $statsByField['soldLeads'] += $soldLeadsCount[$fieldToGroup];
            $statsByField['notSoldLeads'] += $notSoldLeadsCount[$fieldToGroup];
            $statsByField['brakLeads'] += $brakLeadsCount[$fieldToGroup];
            $statsByField['duplicateLeads'] += $duplicateLeadsCount[$fieldToGroup];
            $statsByField['totalRevenue'] += $totalRevenue[$fieldToGroup];
        }

        if (sizeof($statsByField) == 0) {
            return [];
        }

        if ('asc' == $order) {
            ksort($statsByField['data']);
        } else {
            krsort($statsByField['data']);
        }

        $statsByField['brakPercents'] = ($statsByField['soldLeads'] > 0) ? round(($statsByField['brakLeads'] / $statsByField['soldLeads']) * 100, 1) : 0;
        $statsByField['soldLeadsPercent'] = ($statsByField['totalLeads'] > 0) ? round(($statsByField['soldLeads'] / $statsByField['totalLeads']) * 100, 1) : 0;
        $statsByField['averageLeadPrice'] = ($statsByField['soldLeads'] > 0) ? round($statsByField['totalRevenue'] / $statsByField['soldLeads']) : 0;

        return $statsByField;
    }

    /**
     * Выборка лидов со статусами, датами и регионами.
     *
     * @param DateTime|null $fromDate
     *
     * @return array
     *
     * @throws CException
     */
    private function getLeadsWithStatusesAndRegions(DateTime $fromDate = null): array
    {
        $queryCommand = Yii::app()->db->createCommand()
            ->select('l.id, l.leadStatus, l.buyPrice, DATE(l.question_date) lead_date, r.id, r.name region_name')
            ->from('{{lead}} l')
            ->leftJoin('{{leadsource}} s', 'l.sourceId = s.id')
            ->leftJoin('{{town}} t', 'l.townId = t.id')
            ->leftJoin('{{region}} r', 'r.id = t.regionId')
            ->where('s.userId = :userId', [
                ':userId' => $this->userId,
            ]);

        if ($fromDate) {
            $queryCommand->andWhere('l.question_date >=:fromDate', [
                ':fromDate' => $fromDate->format('Y-m-d'),
            ]);
        }

        return $queryCommand->queryAll();
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
