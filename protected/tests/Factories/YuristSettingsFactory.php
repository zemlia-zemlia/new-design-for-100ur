<?php

namespace Tests\Factories;

class YuristSettingsFactory extends BaseFactory
{
    public function generateOne($forcedParams = []): array
    {
        $requestParams = [
            'yuristId' => $this->faker->randomNumber(),
            'alias' => $this->faker->lastName,
            'isVerified' => 1,
        ];

        $requestParams = array_merge($requestParams, $forcedParams);

        return $requestParams;
    }
}
