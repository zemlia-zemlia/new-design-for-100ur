<?php

namespace Tests\Factories;

use App\models\Answer;

class AnswerFactory extends BaseFactory
{
    public function generateOne($forcedParams = []): array
    {
        $requestParams = [
            'id' => $this->faker->randomNumber(6),
            'questionId' => $this->faker->randomNumber(),
            'answerText' => $this->faker->paragraph(),
            'authorId' => $this->faker->randomNumber(),
            'status' => Answer::STATUS_PUBLISHED,
            'karma' => $this->faker->numberBetween(0, 10),
        ];

        $requestParams = array_merge($requestParams, $forcedParams);

        return $requestParams;
    }
}
