<?php

namespace Tests\Factories;

use Comment;

class CommentFactory extends BaseFactory
{
    public function generateOne($forcedParams = []): array
    {
        $requestParams = [
            'id' => $this->faker->randomNumber(6),
            'type' => Comment::TYPE_ANSWER,
            'authorId' => $this->faker->randomNumber(6),
            'objectId' => $this->faker->randomNumber(6),
            'questionId' => $this->faker->randomNumber(6),
            'text' => $this->faker->paragraph,
            'dateTime' => $this->faker->dateTime()->format('Y-m-d H:i:s'),
            'rating' => $this->faker->numberBetween(0,10),
            'status' => Comment::STATUS_NEW,
            'seen' => 0,
        ];

        $requestParams = array_merge($requestParams, $forcedParams);

        return $requestParams;
    }
}
