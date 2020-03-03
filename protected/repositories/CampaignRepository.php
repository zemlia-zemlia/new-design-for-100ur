<?php


class CampaignRepository
{
    /**
     * Возвращает массив активных кампаний с регионами и ценами
     * @return array
     * @throws CException
     */
    public function getActiveCampaigns()
    {
        $campaignsCommand = Yii::app()->db->createCommand()
            ->select("c.id, c.townId, t.name townName, c.regionId, r.name regionName, r.buyPrice regionPrice, t.buyPrice townPrice, r_town.buyPrice townRegionPrice, c.leadsDayLimit, c.realLimit, c.brakPercent, c.timeFrom, c.timeTo, c.price, COUNT(l.id) leadsSent, u.id userId, u.name, u.balance, u.lastTransactionTime")
            ->from("{{campaign}} c")
            ->leftJoin("{{user}} u", "u.id = c.buyerId")
            ->leftJoin("{{town}} t", "t.id = c.townId")
            ->leftJoin("{{region}} r", "r.id = c.regionId")
            ->leftJoin("{{region}} r_town", "r_town.id = t.regionId")
            ->leftJoin("{{lead}} l", "l.campaignId = c.id AND l.leadStatus!=" . Lead::LEAD_STATUS_BRAK)
            ->andWhere("c.active=:active AND c.type=:type AND u.lastTransactionTime>NOW()-INTERVAL 10 DAY", array(':active' => Campaign::ACTIVE_YES, ':type' => Campaign::TYPE_BUYERS))
            ->group("c.id")
            ->order("townPrice DESC, regionPrice DESC");

        $campaignsRows = $campaignsCommand->queryAll();

        $campaignsArray = array();
        $buyPricesByRegion = Region::calculateMinMaxBuyPriceByRegion();

        foreach ($campaignsRows as $campaign) {
            if ($campaign['townName']) {
                // у кампании задан город
                if ($campaign['townPrice']) {
                    $townPrice = $campaign['townPrice'];
                } else {
                    $townPrice = $campaign['townRegionPrice'];
                }

                if (Yii::app()->user->role == User::ROLE_PARTNER && Yii::app()->user->priceCoeff !== 0) {
                    $townPrice *= Yii::app()->user->priceCoeff;
                }
                $campaignsArray[$campaign['townName']] = MoneyFormat::rubles($townPrice);
            } else {
                // у кампании задан регион
                $regionMaxPrice = $buyPricesByRegion[$campaign['regionId']]['max'] ?? 0;
                $regionMinPrice = $buyPricesByRegion[$campaign['regionId']]['min'] ?? 0;

                if (Yii::app()->user->role == User::ROLE_PARTNER && Yii::app()->user->priceCoeff !== 0) {
                    $regionMinPrice = $regionMinPrice * Yii::app()->user->priceCoeff;
                    $regionMaxPrice = $regionMaxPrice * Yii::app()->user->priceCoeff;
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