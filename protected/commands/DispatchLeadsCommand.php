<?php

class DispatchLeadsCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $criteria = new CDbCriteria;
            
        $criteria->addColumnCondition(array('leadStatus'=>Lead100::LEAD_STATUS_DEFAULT));
        $criteria->addColumnCondition(array('question_date>'=>date('Y-m-d')));
        $criteria->with = array('town', 'town.region');

        // сколько лидов обрабатывать за раз
        $criteria->limit = 100;

        $leads = Lead100::model()->findAll($criteria);

        foreach($leads as $lead) {
            $campaignId = Campaign::getCampaignsForLead($lead->id);

            if(!$campaignId) {
                continue;
            }

            $lead->sendToCampaign($campaignId);

        }
    }
}


