<?php

namespace App\repositories;

use App\models\Campaign;
use CException;
use App\models\Lead;
use MoneyFormat;
use App\models\Region;
use App\models\User;
use Yii;

class CampaignRepository
{
    /**
     * Возвращает массив активных кампаний с регионами и ценами.
     * Активными здесь считаются те, по которым были транзакции за последние $activityIntervalDays дней
     *
     * @param User $user Пользователь, для которого посчитать цены покупки (у вебмастера может быть коэффициент цены)
     * @param int $activityIntervalDays
     * @return array
     *
     * @throws CException
     */
    public function getActiveCampaigns(User $user, $activityIntervalDays = 3): array
    {
        $campaignsCommand = Yii::app()->db->createCommand()
            ->select('c.id, c.townId, t.name townName, c.regionId, r.name regionName, r.buyPrice regionPrice, t.buyPrice townPrice, r_town.buyPrice townRegionPrice, c.leadsDayLimit, c.realLimit, c.brakPercent, c.timeFrom, c.timeTo, c.price, COUNT(l.id) leadsSent, u.id userId, u.name, u.balance, u.lastTransactionTime')
            ->from('{{campaign}} c')
            ->leftJoin('{{user}} u', 'u.id = c.buyerId')
            ->leftJoin('{{town}} t', 't.id = c.townId')
            ->leftJoin('{{region}} r', 'r.id = c.regionId')
            ->leftJoin('{{region}} r_town', 'r_town.id = t.regionId')
            ->leftJoin('{{lead}} l', 'l.campaignId = c.id AND l.leadStatus!=' . Lead::LEAD_STATUS_BRAK)
            ->andWhere('c.active=:active AND u.lastTransactionTime>NOW()-INTERVAL :days DAY', [
                ':active' => Campaign::ACTIVE_YES,
                ':days' => $activityIntervalDays,
            ])
            ->group('c.id')
            ->order('r.name ASC');

        $campaignsRows = $campaignsCommand->queryAll();

        $campaignsArray = [];
        $buyPricesByRegion = Region::calculateMinMaxBuyPriceByRegion();

        foreach ($campaignsRows as $campaign) {
            if ($campaign['townName']) {
                // у кампании задан город
                $townPrice = ($campaign['townPrice']) ? $campaign['townPrice'] : $campaign['townRegionPrice'];

                if (User::ROLE_PARTNER == $user->role && 0 !== $user->priceCoeff) {
                    $townPrice *= $user->priceCoeff;
                }
                $campaignsArray[$campaign['townName']] = MoneyFormat::rubles($townPrice);
            } else {
                // у кампании задан регион
                $regionMaxPrice = $buyPricesByRegion[$campaign['regionId']]['max'] ?? 0;
                $regionMinPrice = $buyPricesByRegion[$campaign['regionId']]['min'] ?? 0;

                if (User::ROLE_PARTNER == $user->role && 0 !== $user->priceCoeff) {
                    $regionMinPrice = intval($regionMinPrice * $user->priceCoeff);
                    $regionMaxPrice = intval($regionMaxPrice * $user->priceCoeff);
                }
                $regionMinPriceRubles = MoneyFormat::rubles($regionMinPrice);
                $regionMaxPriceRubles = MoneyFormat::rubles($regionMaxPrice);

                $campaignsArray[$campaign['regionName']] = 'от ' . $regionMinPriceRubles;

                if ($regionMaxPrice > $regionMinPrice) {
                    $campaignsArray[$campaign['regionName']] .= ' до ' . $regionMaxPriceRubles;
                }
            }
        }

        return $campaignsArray;
    }

    /**
     * Получение цен покупки регионов и их столиц, учитываем только те регионы, которые продавались в последние дни
     * @param int $activityIntervalDays
     * @return array Массив регионов с ключами [regionId, regionName, regionBuyPrice,
     * capitalId, capitalName, capitalBuyPrice, minRegionSellPrice, maxRegionSellPrice]
     * @throws CException
     */
    public function getBuyPricesForRegionsAndCapitalsCurrentlyActive($activityIntervalDays = 3): array
    {
        /*       Запрос:
        SELECT c.id, r.id regionId, r.name regionName,
        r.buyPrice regionBuyPrice, t.name capitalName, t.buyPrice capitalBuyPrice,
        MIN(c.price) minRegionSellPrice, MAX(c.price) maxRegionSellPrice
        FROM 100_campaign c
        LEFT JOIN 100_lead l ON l.campaignId = c.id AND l.leadStatus != 4
        LEFT JOIN 100_region r ON r.id = c.regionId
        LEFT JOIN 100_town t ON t.regionId=r.id AND t.isCapital=1
        WHERE c.lastLeadTime > NOW() - INTERVAL 3 DAY AND c.active=1 AND r.id IS NOT NULL
        GROUP BY r.id;
        */

        $command = Yii::app()->db->createCommand()
            ->select('c.id, r.id regionId, r.name regionName, t.id capitalId,
             r.buyPrice regionBuyPrice, t.name capitalName, t.buyPrice capitalBuyPrice, 
             MIN(c.price) minRegionSellPrice, MAX(c.price) maxRegionSellPrice')
            ->from('{{campaign}} c')
            ->leftJoin('{{lead}} l', 'l.campaignId = c.id AND l.leadStatus != ' . Lead::LEAD_STATUS_BRAK)
            ->leftJoin('{{region}} r', 'r.id = c.regionId')
            ->leftJoin('{{town}} t', 't.regionId=r.id AND t.isCapital=1')
            ->where('c.lastLeadTime > NOW() - INTERVAL :days DAY AND c.active=:active AND r.id IS NOT NULL', [
                ':days' => $activityIntervalDays,
                ':active' => Campaign::ACTIVE_YES,
            ])
            ->group('r.id')
            ->order('r.name ASC');

        return $command->queryAll();
    }

    /**
     * Возвращает массив данных о ценах продажи лидов в столицы регионов, ключи - id регионов
     * [
     *  25 => [
     *      'capitalName' => 'Москва',
     *      'minCapitalSellPrice' => 10000,
     *      'maxCapitalSellPrice' => 25000,
     *  ]
     * ]
     * @param int $activityIntervalDays
     * @return array
     * @throws CException
     */
    public function getSellPricesOfCapitalsByRegions($activityIntervalDays = 3): array
    {
        /*
        SELECT t.regionId regionId, t.name capitalName,
        MIN(c.price) minCapitalSellPrice, MAX(c.price) maxCapitalSellPrice
        FROM 100_campaign c
        LEFT JOIN 100_lead l ON l.campaignId = c.id AND l.leadStatus != 4
        LEFT JOIN 100_town t ON c.townId=t.id AND t.isCapital=1
        WHERE c.lastLeadTime > NOW() - INTERVAL 3 DAY AND c.active=1 AND t.id IS NOT NULL
        GROUP BY t.id;
        */

        $capitalRows = Yii::app()->db->createCommand()
            ->select('t.regionId regionId, t.name capitalName,
                MIN(c.price) minCapitalSellPrice, MAX(c.price) maxCapitalSellPrice')
            ->from('{{campaign}} c')
            ->leftJoin('{{lead}} l', 'l.campaignId = c.id AND l.leadStatus != 4')
            ->leftJoin('{{town}} t', 'c.townId=t.id AND t.isCapital=1')
            ->where('c.lastLeadTime > NOW() - INTERVAL :days DAY AND c.active=:active AND t.id IS NOT NULL', [
                ':days' => $activityIntervalDays,
                ':active' => Campaign::ACTIVE_YES,
            ])
            ->group('t.id')
            ->queryAll();

        $capitalsByRegions = [];

        foreach ($capitalRows as $row) {
            $capitalsByRegions[$row['regionId']]['capitalName'] = $row['capitalName'];
            $capitalsByRegions[$row['regionId']]['minCapitalSellPrice'] = $row['minCapitalSellPrice'];
            $capitalsByRegions[$row['regionId']]['maxCapitalSellPrice'] = $row['maxCapitalSellPrice'];
        }

        return $capitalsByRegions;
    }
}
