<?php

class DispatchLeadsCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $criteria = new CDbCriteria;
            
        $criteria->addColumnCondition(array('leadStatus'=>Lead100::LEAD_STATUS_DEFAULT));
        $criteria->addCondition('question_date>NOW()-INTERVAL 17 HOUR');
        $criteria->with = array('town', 'town.region');

        // сколько лидов обрабатывать за раз
        $criteria->limit = 100;

        $leads = Lead100::model()->findAll($criteria);

        foreach($leads as $lead) {
            $campaignId = Campaign::getCampaignsForLead($lead->id);
            //echo $lead->id . ' - ' . $campaignId . PHP_EOL;
            if(!$campaignId) {
                continue;
            }

            $lead->sendToCampaign($campaignId);

        }
        
        // получим статистику запросов к базе
        $dbStats = Yii::app()->db->getStats();
        if(is_array($dbStats)) {
           // echo "Number of queries: " . $dbStats[0] . PHP_EOL;
            //echo "Queries duration (s): " . $dbStats[1] . PHP_EOL;
        }
        
    }
}


