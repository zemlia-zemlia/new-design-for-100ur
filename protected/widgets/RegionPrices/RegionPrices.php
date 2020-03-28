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
        $campaignsArray = (new CampaignRepository())->getActiveCampaigns($this->activityIntervalDays);

        $this->render($this->template, [
            'campaignsArray' => $campaignsArray,
        ]);
    }
}
