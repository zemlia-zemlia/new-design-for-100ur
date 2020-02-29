<?php


class BuyerRepository
{
    public function getBuyersCampaignsDataProvider($buyerId)
    {
        $myCampaigns = Campaign::getCampaignsForBuyer($buyerId);
        $myCampaignIds = array();

        foreach ($myCampaigns as $campaign) {
            $myCampaignIds[] = $campaign->id;
        }

        $criteria = new CDbCriteria;

        $criteria->addInCondition('campaignId', $myCampaignIds);
        $criteria->addColumnCondition(['buyerId' => $buyerId], 'AND', 'OR');
        $criteria->order = 'deliveryTime DESC';

        $dataProvider = new CActiveDataProvider('Lead', array(
            'criteria' => $criteria,
        ));

        return $dataProvider;
    }
}