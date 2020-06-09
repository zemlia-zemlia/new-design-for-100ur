<?php

namespace App\tests\Factories;

use Tests\Factories\BaseFactory;

class ChatFactory extends BaseFactory
{
    /**
     * Генерация массива атрибутов модели.
     *
     * @param array $forcedParams Атрибуты, которые необходимо переопределить [key => value]
     */
    public function generateOne($forcedParams = []): array
    {
        $attributes = [
            'id' => $this->faker->numberBetween(1, 100000),
            'user_id' => $this->faker->numberBetween(1, 100000),
            'lawyer_id' => $this->faker->numberBetween(1, 100000),
            'is_payed' => 0,
            'transaction_id' => null,
            'created' => $this->faker->time('U'),
            'is_closed' => 0,
            'chat_id' => $this->faker->word,
            'is_confirmed' => 0,
            'is_petition' => 0,
        ];

        $attributes = array_merge($attributes, $forcedParams);

        return $attributes;
    }
}
