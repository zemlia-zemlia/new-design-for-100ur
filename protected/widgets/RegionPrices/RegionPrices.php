<?php

// виджет для вывода регионов и цен покупки лидов по ним

class RegionPrices extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $cacheTime = 600; // время кеширования по умолчанию

    public function run()
    {
        $campaignsCommand = Yii::app()->db->createCommand()
                ->select("c.id, c.townId, t.name townName, c.regionId, r.name regionName, r.buyPrice regionPrice, t.buyPrice townPrice, r_town.buyPrice townRegionPrice, c.leadsDayLimit, c.realLimit, c.brakPercent, c.timeFrom, c.timeTo, c.price, COUNT(l.id) leadsSent, u.id userId, u.name, u.balance, u.lastTransactionTime")
                ->from("{{campaign}} c")
                ->leftJoin("{{user}} u", "u.id = c.buyerId")
                ->leftJoin("{{town}} t", "t.id = c.townId")
                ->leftJoin("{{region}} r", "r.id = c.regionId")
                ->leftJoin("{{region}} r_town", "r_town.id = t.regionId")
                ->leftJoin("{{lead}} l", "l.campaignId = c.id AND l.leadStatus!=" . Lead::LEAD_STATUS_BRAK)
                ->andWhere("c.active=:active AND u.lastTransactionTime>NOW()-INTERVAL 10 DAY", array(':active' => Campaign::ACTIVE_YES))
                ->group("c.id")
                ->order("townPrice DESC, regionPrice DESC");
        
        $campaignsRows = $campaignsCommand->queryAll();
        
        //CustomFuncs::printr($campaignsRows);
        
        $campaignsArray = array();
        
        foreach($campaignsRows as $campaign) {
            if($campaign['townName']) {
                // у кампании задан город
                if($campaign['townPrice']) {
                    $campaignsArray[$campaign['townName']] = $campaign['townPrice'];
                } else {
                    $campaignsArray[$campaign['townName']] = $campaign['townRegionPrice'];
                }
            } else {
                // у кампании задан регион
                $campaignsArray[$campaign['regionName']] = $campaign['regionPrice'];
            }
        }
        
        //CustomFuncs::printr($campaignsArray);
        
        $this->render($this->template, array(
            'campaignsArray' =>  $campaignsArray,
        ));
    }
    
}