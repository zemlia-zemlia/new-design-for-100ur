<?php

namespace Tests\unit\models;

use Codeception\Test\Unit;
use Question;

class QuestionTest extends Unit
{
    /**
     * @dataProvider providerFormTitle
     *
     * @param string $questionText
     * @param string $expectedTitle
     */
    public function testFormTitle($questionText, $expectedTitle)
    {
        $question = new Question();
        $question->questionText = $questionText;
        $question->formTitle(10);
        $this->assertEquals($expectedTitle, $question->title);
    }

    public function providerFormTitle(): array
    {
        return [
            [
                'questionText' => '',
                'expectedTitle' => '',
            ],
            [
                'questionText' => 'Привет мир',
                'expectedTitle' => 'Привет мир',
            ],
            [
                'questionText' => 'Привет мир!',
                'expectedTitle' => 'Привет мир',
            ],
            [
                'questionText' => 'Быть или не быть - вот в чем вопрос. Достойно ли смиряться по ударами судьбы?',
                'expectedTitle' => 'Быть или не быть вот в чем вопрос Достойно ли',
            ],
            [
                'questionText' => 'Здравствуйте, я ваша тетя',
                'expectedTitle' => 'Я ваша тетя',
            ],
        ];
    }

    /**
     * @dataProvider providerPriceLevel
     *
     * @param int $level
     * @param int $expectedPrice
     */
    public function testGetPriceByLevel($level, $expectedPrice)
    {
        $this->assertEquals($expectedPrice, Question::getPriceByLevel($level));
    }

    /**
     * @return array
     */
    public function providerPriceLevel(): array
    {
        return [
            [
                'level' => 0,
                'expectedPrice' => 0,
            ],
            [
                'level' => Question::LEVEL_1,
                'expectedPrice' => 142,
            ],
            [
                'level' => Question::LEVEL_2,
                'expectedPrice' => 265,
            ],
            [
                'level' => Question::LEVEL_3,
                'expectedPrice' => 385,
            ],
            [
                'level' => Question::LEVEL_4,
                'expectedPrice' => 515,
            ],
            [
                'level' => Question::LEVEL_5,
                'expectedPrice' => 695,
            ],
        ];
    }
}
