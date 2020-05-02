<?php

namespace Tests\Factories;

use App\helpers\PhoneHelper;
use App\models\Lead;

class LeadFactory extends BaseFactory
{
    public function generateOne($forcedParams = []): array
    {
        $attributes = [
            'name' => $this->faker->name,
            'phone' => PhoneHelper::normalizePhone($this->faker->phoneNumber),
            'email' => $this->faker->numberBetween(100, 1000) . '@yurcrm.ru',
            'townId' => $this->faker->numberBetween(1, 1000),
            'sourceId' => $this->faker->numberBetween(1, 1000),
            'question' => $this->faker->paragraph,
            'price' => 9500,
            'leadStatus' => Lead::LEAD_STATUS_DEFAULT,
        ];

        $attributes = array_merge($attributes, $forcedParams);

        return $attributes;
    }
}
