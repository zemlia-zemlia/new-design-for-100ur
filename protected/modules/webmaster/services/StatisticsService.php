<?php

namespace webmaster\services;

use DateTime;
use Lead;
use Yii;

/**
 * Класс для получения различных статистик
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
     * @return int
     * @throws \CException
     */
    public function getAllLeadsCount(DateTime $fromDate = null): int
    {
        $queryCommand = Yii::app()->db->createCommand()
            ->select('count(*) counter')
            ->from("{{lead}} l")
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
     * @param DateTime|null $fromDate
     * @return array
     * @throws \CException
     */
    public function getLeadsStatisticsByDates(DateTime $fromDate = null): array
    {
        $rawStatistics = $this->getLeadsWithStatusesAndRegions($fromDate);

        $statsByDates = [];
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
            $totalLeadsCount[$row['lead_date']]++;

            if (in_array($row['leadStatus'], $this->getSoldLeadStatuses())) {
                $soldLeadsCount[$row['lead_date']]++;
                $totalRevenue[$row['lead_date']] += $row['buyPrice'];
            }

            if ($row['leadStatus'] == Lead::LEAD_STATUS_DEFAULT) {
                $notSoldLeadsCount[$row['lead_date']]++;
            }

            if ($row['leadStatus'] == Lead::LEAD_STATUS_BRAK) {
                $brakLeadsCount[$row['lead_date']]++;
            }

            if ($row['leadStatus'] == Lead::LEAD_STATUS_DUPLICATE) {
                $duplicateLeadsCount[$row['lead_date']]++;
            }
        }

        foreach ($brakLeadsCount as $date => $brakLeadOnDate) {
            $brakPercents[$date] = ($soldLeadsCount[$date] > 0) ? round(($brakLeadOnDate / $soldLeadsCount[$date]) * 100, 1) : 0;
        }

        foreach ($soldLeadsCount as $date => $soldLeadsOnDate) {
            $soldLeadsPercent[$date] = ($totalLeadsCount[$date] > 0) ? round(($soldLeadsOnDate / $totalLeadsCount[$date]) * 100, 1) : 0;
            $averageLeadPrice[$date] = ($soldLeadsOnDate > 0) ? round($totalRevenue[$date] / $soldLeadsOnDate) : 0;
        }

        foreach ($totalLeadsCount as $date => $totalLeadsForDate) {
            $statsByDates['dates'][$date] = [
                'totalLeads' => $totalLeadsForDate,
                'soldLeads' => $soldLeadsCount[$date] ?? 0,
                'notSoldLeads' => $notSoldLeadsCount[$date] ?? 0,
                'soldLeadsPercent' => $soldLeadsPercent[$date],
                'brakLeads' => $brakLeadsCount[$date] ?? 0,
                'duplicateLeads' => $duplicateLeadsCount[$date] ?? 0,
                'brakPercents' => $brakPercents[$date],
                'averageLeadPrice' => $averageLeadPrice[$date],
                'totalRevenue' => $totalRevenue[$date],
            ];
            $statsByDates['totalLeads'] += $totalLeadsForDate;
            $statsByDates['soldLeads'] += $soldLeadsCount[$date];
            $statsByDates['notSoldLeads'] += $notSoldLeadsCount[$date];
            $statsByDates['brakLeads'] += $brakLeadsCount[$date];
            $statsByDates['duplicateLeads'] += $duplicateLeadsCount[$date];
            $statsByDates['totalRevenue'] += $totalRevenue[$date];
        }

        $statsByDates['brakPercents'] = ($statsByDates['soldLeads'] > 0) ? round(($statsByDates['brakLeads'] / $statsByDates['soldLeads']) * 100, 1) : 0;
        $statsByDates['soldLeadsPercent'] = ($statsByDates['totalLeads'] > 0) ? round(($statsByDates['soldLeads'] / $statsByDates['totalLeads']) * 100, 1) : 0;
        $statsByDates['averageLeadPrice'] = ($statsByDates['soldLeads'] > 0) ? round($statsByDates['totalRevenue'] / $statsByDates['soldLeads']) : 0;

        return $statsByDates;
    }

    /**
     * Выборка лидов со статусами, датами и регионами
     * @param DateTime|null $fromDate
     * @return array
     * @throws \CException
     */
    private function getLeadsWithStatusesAndRegions(DateTime $fromDate = null): array
    {
        $queryCommand = Yii::app()->db->createCommand()
            ->select('l.id, l.leadStatus, l.buyPrice, DATE(l.question_date) lead_date, r.id, r.name')
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
     * @return StatisticsService
     */
    public function setUserId($userId): StatisticsService
    {
        $this->userId = $userId;
        return $this;
    }

}
