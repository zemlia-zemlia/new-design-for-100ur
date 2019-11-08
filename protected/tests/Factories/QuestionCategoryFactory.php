<?php

namespace Tests\Factories;

use QuestionCategory;

class QuestionCategoryFactory extends BaseFactory
{
    public function generateOne($forcedParams = []): array
    {
        $requestParams = [
            'id' => $this->faker->randomNumber(6),
            'name' => $this->faker->sentence,
            'parentId' => $this->faker->randomNumber(6),
        ];

        $requestParams = array_merge($requestParams, $forcedParams);

        return $requestParams;
    }
}
