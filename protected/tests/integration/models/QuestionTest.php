<?php

namespace tests\integration\models;

use Codeception\Test\Unit;
use Question;
use Question2category;
use QuestionCategory;
use Tests\Factories\QuestionCategoryFactory;
use Tests\Factories\QuestionFactory;
use Tests\integration\BaseIntegrationTest;
use User;
use Yii;

class QuestionTest extends BaseIntegrationTest
{

    protected function _before()
    {
        Yii::app()->db->createCommand()->truncateTable(Question::getFullTableName());
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
            $this->tester->haveInDatabase(Question::getFullTableName(), $fixture);
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

    public function testGetRandomId()
    {
        $questionsAttributes = (new QuestionFactory())->generateFew(5, [
            'status' => Question::STATUS_PUBLISHED,
        ]);
        $this->loadToDatabase(Question::getFullTableName(), $questionsAttributes);

        $categoriesAttributes = (new QuestionCategoryFactory())->generateFew(5);
        $this->loadToDatabase(QuestionCategory::getFullTableName(), $categoriesAttributes);

        $q2c = new Question2category();
        $q2c->qId = $demoQuestionId = $questionsAttributes[0]['id'];
        $q2c->cId = $demoCatId = $categoriesAttributes[0]['id'];
        $this->assertTrue($q2c->save());

        $democat = new QuestionCategory();
        $democat->scenario = 'testing';
        $democat->setAttributes([
            'id' => $demoCatId,
        ]);
        $userMock = $this->createMock(User::class);
        $categoriesArray = [$democat];
        $userMock->method('getCategories')->willReturn($categoriesArray);

        $this->assertEquals($demoQuestionId, Question::getRandomId($userMock));
    }
}
