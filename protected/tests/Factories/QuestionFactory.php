<?php

namespace Tests\Factories;

use App\models\Question;

class QuestionFactory extends BaseFactory
{
    public function generateOne($forcedParams = []): array
    {
        $requestParams = [
            'id' => $this->faker->randomNumber(6),
            'questionText' => $this->faker->paragraph,
            'title' => $this->faker->sentence,
            'authorName' => $this->faker->name,
            'authorId' => $this->faker->randomNumber(6),
            'status' => Question::STATUS_PUBLISHED,
            'townId' => $this->faker->numberBetween(1, 1000),
            'sourceId' => 0,
        ];

        $requestParams = array_merge($requestParams, $forcedParams);

        return $requestParams;
    }
}
