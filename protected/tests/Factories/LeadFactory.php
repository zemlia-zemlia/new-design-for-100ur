<?php

namespace Tests\Factories;

use PhoneHelper;

class LeadFactory extends BaseFactory
{
    public function generateOne($forcedParams = []): array
    {
        $attributes = [
            'name' => $this->faker->name,
            'phone' => PhoneHelper::normalizePhone($this->faker->phoneNumber),
            'email' => $this->faker->numberBetween(100, 1000) . '@yurcrm.ru',
            'townId' => $this->faker->numberBetween(1,1000),
            'question' => $this->faker->paragraph,
            'price' => 95,
        ];

        $attributes = array_merge($attributes, $forcedParams);

        return $attributes;
    }
}
