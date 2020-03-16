<?php

use App\repositories\CampaignRepository;

/**
 * виджет для вывода регионов и цен покупки лидов по ним
 */
class RegionPrices extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $cacheTime = 600; // время кеширования по умолчанию

    public function run()
    {
        $campaignsArray = (new CampaignRepository())->getActiveCampaigns();

        $this->render($this->template, [
            'campaignsArray' => $campaignsArray,
        ]);
    }
}
