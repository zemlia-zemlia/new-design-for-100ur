<?php

namespace Tests\integration;

use Codeception\Test\Unit;
use Faker\Factory;
use Faker\Generator;

class BaseIntegrationTest extends Unit
{
    /**
     * @var \IntegrationTester
     */
    protected $tester;

    /** @var Generator $faker */
    protected $faker;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->faker = Factory::create('ru_RU');
    }

    /**
     * Записывает в базу массив из нескольких записей.
     *
     * @param string $tableName
     * @param array  $data
     */
    protected function loadToDatabase($tableName, $data)
    {
        foreach ($data as $record) {
            if (is_array($record)) {
                $this->tester->haveInDatabase($tableName, $record);
            }
        }
    }
}
