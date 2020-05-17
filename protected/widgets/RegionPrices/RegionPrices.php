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

    /** @var CampaignRepository */
    private $campaignRepository;

    public function run()
    {
        $this->campaignRepository = new CampaignRepository();

        $campaignsArray = $this->getData();
        $capitalsPrices = $this->campaignRepository->getSellPricesOfCapitalsByRegions($this->activityIntervalDays);

        $this->render($this->template, [
            'campaignsArray' => $campaignsArray,
            'capitalsPrices' => $capitalsPrices,
        ]);
    }

    /**
     * @throws CException
     */
    private function getData(): array
    {
        switch ($this->mode) {
            case self::MODE_REGIONS_WITH_CAPITALS:
                return $this->campaignRepository->getBuyPricesForRegionsAndCapitalsCurrentlyActive($this->activityIntervalDays);
            case self::MODE_REGIONS_AND_TOWNS:
            default:
                $currentUser = Yii::app()->user->getModel();

                return $this->campaignRepository->getActiveCampaigns($currentUser, $this->activityIntervalDays);
        }
    }
}
