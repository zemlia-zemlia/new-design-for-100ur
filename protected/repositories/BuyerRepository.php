<?php

namespace App\repositories;

use App\models\Campaign;
use App\models\Lead;
use CActiveDataProvider;
use CDbCriteria;

class BuyerRepository
{
    public function getBuyersCampaignsDataProvider($buyerId)
    {
        $myCampaigns = Campaign::getCampaignsForBuyer($buyerId);
        $myCampaignIds = [];

        foreach ($myCampaigns as $campaign) {
            $myCampaignIds[] = $campaign->id;
        }

        $criteria = new CDbCriteria();

        $criteria->addInCondition('campaignId', $myCampaignIds);
        $criteria->addColumnCondition(['buyerId' => $buyerId], 'AND', 'OR');
        $criteria->order = 'deliveryTime DESC';

        $dataProvider = new CActiveDataProvider(Lead::class, [
            'criteria' => $criteria,
        ]);

        return $dataProvider;
    }
}
