<?php


namespace Tests\Factories;


use PhoneHelper;
use User;

class UserFactory extends BaseFactory
{

    /**
     * Генерация массива атрибутов модели
     * @param array $forcedParams Атрибуты, которые необходимо переопределить [key => value]
     * @return array
     */
    public function generateOne($forcedParams = []): array
    {
        $attributes = [
            'id' => $this->faker->numberBetween(1, 100000),
            'name' => $this->faker->name,
            'lastName' => $this->faker->lastName,
            'role' => User::ROLE_CLIENT,
            'email' => $this->faker->randomNumber(6) . '@yurcrm.ru',
            'phone' => PhoneHelper::normalizePhone($this->faker->phoneNumber),
            'active100' => 1,
            'townId' => $this->faker->numberBetween(1, 999),
            'balance' => 1000000, // в копейках
            'priceCoeff' => 0.5,
        ];

        $attributes = array_merge($attributes, $forcedParams);

        return $attributes;
    }
}
