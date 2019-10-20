<?php

namespace Tests\Factories;

interface TestFactoryInterface
{
    /**
     * Генерация массива атрибутов модели
     * @param array $forcedParams Атрибуты, которые необходимо переопределить [key => value]
     * @return array
     */
    public function generateOne($forcedParams = []): array;
}
