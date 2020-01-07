<?php

namespace Tests\Factories;

use Question2Category;

class Question2CategoryFactory extends BaseFactory
{
    public function generateOne($forcedParams = []): array
    {
        $requestParams = [
            'qid' => $this->faker->randomNumber(6),
            'cid' => $this->faker->randomNumber(6),
        ];

        $requestParams = array_merge($requestParams, $forcedParams);

        return $requestParams;
    }
}
