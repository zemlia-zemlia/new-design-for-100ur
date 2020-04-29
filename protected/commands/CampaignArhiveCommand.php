<?php

use App\models\Campaign;

/**
 * отправляем в архив кампании, которые не имеют активности 15 дней
 *
 */
class CampaignArhiveCommand extends CConsoleCommand
{
    protected $_days = 15; // через сколько дней отправлять в архив

    public function actionIndex()
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('active=' . Campaign::ACTIVE_YES);
        $criteria->addCondition('`lastLeadTime`<NOW() - INTERVAL ' . $this->_days . ' DAY OR lastLeadTime IS NULL');


        $campaigns = Campaign::model()->findAll($criteria);

        foreach ($campaigns as $campaign) {
            $campaign->sendToArchive();
            $ids[] = $campaign->id;
        }


    }
}
