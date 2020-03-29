<?php

/**
 * виджет для вывода регионов и цен покупки лидов по ним
*/

class RegionPrices extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $cacheTime = 600; // время кеширования по умолчанию
    public $activityIntervalDays = 3; // при выборе регионов учитывать кампании, в которых были продажи за последние несколько дней

    public function run()
    {
        $currentUser = Yii::app()->user->getModel();
        $campaignsArray = (new CampaignRepository())->getActiveCampaigns($currentUser, $this->activityIntervalDays);

        $this->render($this->template, [
            'campaignsArray' => $campaignsArray,
        ]);
    }
}
