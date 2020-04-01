<?php

use App\models\Campaign;
use App\models\Lead;

class DispatchLeadsCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $criteria = new CDbCriteria();

        $criteria->addColumnCondition(['leadStatus' => Lead::LEAD_STATUS_DEFAULT]);
        $criteria->addCondition('question_date>NOW()-INTERVAL 24 HOUR');
        $criteria->with = ['town', 'town.region'];

        // сколько лидов обрабатывать за раз
        $criteria->limit = 100;

        $leads = Lead::model()->findAll($criteria);

        foreach ($leads as $lead) {
            $campaignId = Campaign::getCampaignsForLead($lead->id);

            if (!$campaignId) {
                continue;
            }
            $campaign = Campaign::model()->findByPk($campaignId);
            $lead->sellLead(null, $campaign);
        }
    }
}
