<?php

namespace Tests\Factories;

use App\models\Campaign;

/**
 * Генератор атрибутов кампании
 * Class CampaignFactory.
 */
class CampaignFactory extends BaseFactory
{
    /**
     * {@inheritdoc}
     *
     * @param array $forcedParams
     */
    public function generateOne($forcedParams = []): array
    {
        $requestParams = [
            'id' => $this->faker->randomNumber(),
            'regionId' => $this->faker->numberBetween(1, 99),
            'townId' => $this->faker->numberBetween(1, 999),
            'balance' => $this->faker->numberBetween(100000, 1000000),
            'timeFrom' => 0,
            'timeTo' => 24,
            'price' => 15000,
            'leadsDayLimit' => 10,
            'realLimit' => 10,
            'brakPercent' => 20,
            'buyerId' => $this->faker->randomNumber(),
            'active' => 1,
            'type' => Campaign::TYPE_BUYERS,
        ];

        $requestParams = array_merge($requestParams, $forcedParams);

        return $requestParams;
    }
}
