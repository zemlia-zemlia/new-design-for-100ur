<?php

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
    public function getActiveCampaigns(User $user, $activityIntervalDays = 3):array
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
            ->order('townPrice DESC, regionPrice DESC');

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
}
