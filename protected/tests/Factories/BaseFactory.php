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

    /**
     * Создает массив массивов атрибутов.
     *
     * @param int   $numberOfItems
     * @param array $commonForcedAttributes
     *
     * @return array
     */
    public function generateFew($numberOfItems = 1, $commonForcedAttributes = [])
    {
        $items = [];
        for ($i = 0; $i < $numberOfItems; ++$i) {
            $items[] = $this->generateOne($commonForcedAttributes);
        }

        return $items;
    }
}
