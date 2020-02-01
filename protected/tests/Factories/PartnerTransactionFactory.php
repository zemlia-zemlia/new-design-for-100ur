<?php

namespace Tests\Factories;

use PartnerTransaction;

class PartnerTransactionFactory extends BaseFactory
{
    public function generateOne($forcedParams = []): array
    {
        $requestParams = [
            'id' => $this->faker->randomNumber(6),
            'partnerId' => $this->faker->randomNumber(6),
            'sourceId' => $this->faker->randomNumber(6),
            'sum' => $this->faker->numberBetween(10000,100000),
            'status' => PartnerTransaction::STATUS_COMPLETE,
            'comment' => $this->faker->sentence,
        ];

        $requestParams = array_merge($requestParams, $forcedParams);

        return $requestParams;
    }
}
