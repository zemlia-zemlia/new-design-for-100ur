<?php

/**
 * виджет для вывода регионов и цен покупки лидов по ним
 */

use App\repositories\CampaignRepository;

class RegionPrices extends CWidget
{
    const MODE_REGIONS_AND_TOWNS = 'regionsAndTowns';
    const MODE_REGIONS_WITH_CAPITALS = 'regionsWithCapitals';

    public $template = 'default'; // представление виджета по умолчанию
    public $cacheTime = 600; // время кеширования по умолчанию
    public $activityIntervalDays = 3; // при выборе регионов учитывать кампании, в которых были продажи за последние несколько дней
    public $mode = 'regionsAndTowns'; // режим работы (regionsAndTowns или regionsWithCapitals)

    public function run()
    {
        $campaignsArray = $this->getData();

        $this->render($this->template, [
            'campaignsArray' => $campaignsArray,
        ]);
    }

    /**
     * @return array
     * @throws CException
     */
    private function getData(): array
    {
        $campaignRepository = new CampaignRepository();

        switch ($this->mode) {
            case self::MODE_REGIONS_WITH_CAPITALS:
                return $campaignRepository->getBuyPricesForRegionsAndCapitalsCurrentlyActive($this->activityIntervalDays);
            case self::MODE_REGIONS_AND_TOWNS:
            default:
                $currentUser = Yii::app()->user->getModel();
                return $campaignRepository->getActiveCampaigns($currentUser, $this->activityIntervalDays);
        }
    }
}
