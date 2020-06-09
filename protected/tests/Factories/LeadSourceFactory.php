<?php

namespace Tests\Factories;

class LeadSourceFactory extends BaseFactory
{
    /**
     * Генерация массива атрибутов модели.
     *
     * @param array $forcedParams Атрибуты, которые необходимо переопределить [key => value]
     */
    public function generateOne($forcedParams = []): array
    {
        $attributes = [
            'id' => $this->faker->randomNumber(),
            'appId' => $this->faker->randomNumber(),
            'secretKey' => $this->faker->lexify('test????'),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'active' => 1,
            'userId' => $this->faker->randomNumber(),
            'priceByPartner' => 1,
        ];

        $attributes = array_merge($attributes, $forcedParams);

        return $attributes;
    }
}
