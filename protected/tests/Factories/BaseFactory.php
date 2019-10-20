<?php

namespace Tests\Factories;

use Faker\Factory;

abstract class BaseFactory implements TestFactoryInterface
{
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    public function __construct($locale = 'ru_RU')
    {
        $this->faker = Factory::create($locale);
    }

    public function generateFew($numberOfItems = 1)
    {
        $items = [];
        for ($i = 0; $i < $numberOfItems; $i++) {
            $items[] = $this->generateOne();
        }

        return $items;
    }
}