<?php

namespace Tests\Unit\Models;

use \Codeception\Test\Unit;
use Faker\Factory;
use Lead;
use PhoneHelper;

class LeadTest extends Unit
{
    protected $faker;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker = Factory::create('ru_RU');
    }

    /**
     * Проверка работы правил валидации
     * @dataProvider providerValidation
     */
    public function testValidation(array $attributes, bool $expectedResult, ?string $scenario = null)
    {
        $lead = new Lead();

        if ($scenario) {
            $lead->setScenario($scenario);
            $this->assertEquals($scenario, $lead->getScenario());
        }
        $lead->setAttributes($attributes);
        $lead->phone = PhoneHelper::normalizePhone($lead->phone);

        $validationResult = $lead->validate();

        if ($expectedResult == true) {
            $this->assertEquals([], $lead->getErrors());
        }

        $this->assertEquals($expectedResult, $validationResult);
    }

    public function providerValidation(): array
    {
        return [
            'Correct' => [
                'attributes' => $this->createValidLeadAttributes(),
                'expectedResult' => true,
            ],
            'Agree field zero, scenario Create call' => [
                'attributes' => $this->createValidLeadAttributes() + [
                        'agree' => 0,
                    ],
                'expectedResult' => false,
                'scenario' => 'createCall',
            ],
            'Agree field zero, scenario Create' => [
                'attributes' => $this->createValidLeadAttributes() + [
                        'agree' => 0,
                    ],
                'expectedResult' => false,
                'scenario' => 'create',
            ],
            'Empty question, scenario Create call' => [
                'attributes' => array_merge($this->createValidLeadAttributes()) + [
                        'question' => '',
                    ],
                'expectedResult' => true,
                'scenario' => 'createCall',
            ],
            'Long name' => [
                'attributes' => array_merge($this->createValidLeadAttributes(), [
                        'name' => $this->faker->sentence(100), // 100 слов
                    ]),
                'expectedResult' => false,
            ],
            'Name with spec characters' => [
                'attributes' => array_merge($this->createValidLeadAttributes(), [
                    'name' => 'My хакер_> name',
                ]),
                'expectedResult' => false,
            ],
            'Empty name' => [
                'attributes' => array_merge($this->createValidLeadAttributes(), [
                    'name' => '',
                ]),
                'expectedResult' => false,
            ],
            'Bad email' => [
                'attributes' => array_merge($this->createValidLeadAttributes(), [
                    'name' => 'хакервася@mail.ru',
                ]),
                'expectedResult' => false,
            ],
            'Bad dates' => [
                'attributes' => array_merge($this->createValidLeadAttributes(), [
                    'date1' => '2019-01-01 11:00',
                    'date2' => '2019-01-02 11:00:00',
                ]),
                'expectedResult' => false,
            ],
        ];
    }

    /**
     * Возвращает набор атрибутов валидного лида
     * @return array
     */
    protected function createValidLeadAttributes(): array
    {
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'sourceId' => 100,
            'townId' => 200,
            'town' => 'Биробиджан',
            'question' => $this->faker->paragraph,
        ];
    }

    public function testGetLeadStatusName()
    {
        $lead = new Lead();
        $lead->leadStatus = Lead::LEAD_STATUS_BRAK;

        $this->assertEquals('брак', $lead->getLeadStatusName());
    }

    public function testGetLeadTypeName()
    {
        $lead = new Lead();
        $lead->type = Lead::TYPE_CALL;

        $this->assertEquals('запрос звонка', $lead->getLeadTypeName());

        $lead->type = Lead::TYPE_QUESTION;
        $this->assertEquals('вопрос', $lead->getLeadTypeName());
    }

    public function testGetReasonName()
    {
        $lead = new Lead();
        $lead->brakReason = Lead::BRAK_REASON_BAD_NUMBER;

        $this->assertEquals('неверный номер', $lead->getReasonName());
    }
}
