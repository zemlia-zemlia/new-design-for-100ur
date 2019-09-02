<?php

namespace tests\integration\models;

use Codeception\Test\Unit;
use Question;
use Yii;

class QuestionTest extends Unit
{

    /**
     * @var \IntegrationTester
     */
    protected $tester;

    const USER_TABLE = '100_user';
    const QUESTION_TABLE = '100_question';

    protected function _before()
    {
        Yii::app()->db->createCommand()->truncateTable(self::QUESTION_TABLE);
    }

    public function testGetQuestionsByAuthor()
    {
        $fixtures = [
            [
                'title' => 'Где находится нофелет',
                'id' => 1,
                'authorId' => 10,
                'status' => Question::STATUS_NEW,
                'questionText' => 'Новый вопрос',
            ],
            [
                'title' => 'Кому на Руси жить хорошо',
                'id' => 2,
                'authorId' => 10,
                'status' => Question::STATUS_CHECK,
                'questionText' => 'Предварительно опубликованный вопрос 1',
            ],
            [
                'title' => 'Как ковырять в носу правильно',
                'id' => 3,
                'authorId' => 11,
                'status' => Question::STATUS_CHECK,
                'questionText' => 'Предварительно опубликованный вопрос 2',
            ],
            [
                'title' => 'Как ковырять в носу НЕправильно',
                'id' => 4,
                'authorId' => 11,
                'status' => Question::STATUS_SPAM,
                'questionText' => 'Спамный вопрос',
            ],
        ];

        foreach ($fixtures as $fixture) {
            $this->tester->haveInDatabase(self::QUESTION_TABLE, $fixture);
        }

        $authorNewQuestions = Question::getQuestionsByAuthor(10);
        $authorQuestionsByStatusNew = Question::getQuestionsByAuthor(10, [
            Question::STATUS_NEW,
        ]);
        $authorQuestionsByStatusNewAndChecked = Question::getQuestionsByAuthor(10, [
            Question::STATUS_NEW,
            Question::STATUS_CHECK,
        ]);

        $this->assertCount(1, $authorNewQuestions);
        $this->assertCount(1, $authorQuestionsByStatusNew);
        $this->assertCount(2, $authorQuestionsByStatusNewAndChecked);
    }
}
