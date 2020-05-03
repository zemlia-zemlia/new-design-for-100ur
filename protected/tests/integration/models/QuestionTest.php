<?php

namespace tests\integration\models;

use App\models\Answer;
use DateTime;
use App\models\Money;
use App\models\Question;
use App\models\Question2category;
use App\models\QuestionCategory;
use Tests\Factories\AnswerFactory;
use Tests\Factories\QuestionCategoryFactory;
use Tests\Factories\QuestionFactory;
use Tests\Factories\UserFactory;
use Tests\integration\BaseIntegrationTest;
use App\models\User;
use Yii;

class QuestionTest extends BaseIntegrationTest
{
    protected function _before()
    {
        Yii::app()->db->createCommand()->truncateTable(Question::getFullTableName());
    }

    public function testGetQuestionsByAuthor()
    {
        $questionFactory = new QuestionFactory();

        $fixtures = [
            $questionFactory->generateOne([
                'title' => 'Где находится нофелет',
                'id' => 1,
                'authorId' => 10,
                'status' => Question::STATUS_NEW,
                'questionText' => 'Новый вопрос',
            ]),
            $questionFactory->generateOne([
                'title' => 'Кому на Руси жить хорошо',
                'id' => 2,
                'authorId' => 10,
                'status' => Question::STATUS_CHECK,
                'questionText' => 'Предварительно опубликованный вопрос 1',
            ]),
            $questionFactory->generateOne([
                'title' => 'Как ковырять в носу правильно',
                'id' => 3,
                'authorId' => 11,
                'status' => Question::STATUS_CHECK,
                'questionText' => 'Предварительно опубликованный вопрос 2',
            ]),
            $questionFactory->generateOne([
                'title' => 'Как ковырять в носу НЕправильно',
                'id' => 4,
                'authorId' => 11,
                'status' => Question::STATUS_SPAM,
                'questionText' => 'Спамный вопрос',
            ]),
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

    public function testGetCountByStatus()
    {
        $questionFactory = new QuestionFactory();
        $questionsAttributes = array_merge(
            $questionFactory->generateFew(3, ['status' => Question::STATUS_CHECK]),
            $questionFactory->generateFew(2, ['status' => Question::STATUS_PUBLISHED])
        );

        $this->loadToDatabase(Question::getFullTableName(), $questionsAttributes);

        $this->assertEquals(3, Question::getCountByStatus(Question::STATUS_CHECK));
        $this->assertEquals(2, Question::getCountByStatus(Question::STATUS_PUBLISHED));
        $this->assertEquals(0, Question::getCountByStatus(Question::STATUS_SPAM));
    }

    public function testGetCountWithoutAnswers()
    {
        $questionFactory = new QuestionFactory();
        $answerFactory = new AnswerFactory();

        $questionsAttributes = array_merge(
            $questionFactory->generateFew(3, ['status' => Question::STATUS_CHECK]),
            $questionFactory->generateFew(2, ['status' => Question::STATUS_SPAM]),
            $questionFactory->generateFew(1, [
                'status' => Question::STATUS_PUBLISHED,
                'createDate' => (new DateTime())->modify('-1 year')->format('Y-m-d H:i:s'),
            ])
        );

        $answerAttributes = $answerFactory->generateOne([
            'questionId' => $questionsAttributes[0]['id'],
        ]);

        $this->loadToDatabase(Question::getFullTableName(), $questionsAttributes);
        $this->loadToDatabase(Answer::getFullTableName(), [$answerAttributes]);

        $this->assertEquals(2, Question::getCountWithoutAnswers(30));
        $this->assertEquals(3, Question::getCountWithoutAnswers(370));
    }

    /**
     * @dataProvider providerCreateAuthor
     *
     * @param array      $questionAttributes
     * @param array|null $userAttributes
     * @param bool       $expectedResult
     */
    public function testCreateAuthor($questionAttributes, $userAttributes, $expectedResult)
    {
        if ($userAttributes) {
            $this->loadToDatabase(User::getFullTableName(), [$userAttributes]);
        }

        $question = new Question();
        $question->attributes = $questionAttributes;

        $createAuthorResult = $question->createAuthor();

        $this->assertEquals($expectedResult, $createAuthorResult);

        if ($userAttributes && true == $expectedResult) {
            $this->assertEquals($userAttributes['id'], $question->authorId);
        }
    }

    /**
     * @return array
     */
    public function providerCreateAuthor(): array
    {
        $userFactory = new UserFactory();
        $questionFactory = new QuestionFactory();

        return [
            'existing user' => [
                'questionAttributes' => $questionFactory->generateOne([
                    'email' => 'pupkin@100yuristov.com',
                ]),
                'userAttributes' => $userFactory->generateOne([
                    'id' => 100,
                    'email' => 'pupkin@100yuristov.com',
                ]),
                'expectedResult' => true,
            ],
            'new user' => [
                'questionAttributes' => $questionFactory->generateOne([
                    'email' => 'pechkin@100yuristov.com',
                ]),
                'userAttributes' => null,
                'expectedResult' => true,
            ],
            'incorrect user' => [
                'questionAttributes' => $questionFactory->generateOne([
                    'email' => 'pechkin@100yuristov.com',
                ]),
                'userAttributes' => $userFactory->generateOne([
                    'id' => 100,
                    'email' => 'pupkin@100yuristov.com',
                    'password' => '12',
                ]),
                'expectedResult' => false,
            ],
        ];
    }

    public function testVipNotification()
    {
        $questionAttributes = (new QuestionFactory())->generateOne();

        $question = new Question();
        $question->attributes = $questionAttributes;
        $saveResult = $question->save();

        $this->assertTrue($saveResult);

        $question->vipNotification(100);

        $this->tester->seeInDatabase(Money::getFullTableName(), [
            'type' => Money::TYPE_INCOME,
            'direction' => 504,
            'value' => 100,
        ]);
    }

    /**
     * @dataProvider providerPreSave
     *
     * @param array $questionAttributes
     * @param bool  $expectedResult
     */
    public function testPreSave($questionAttributes, $expectedResult)
    {
        $question = new Question();
        $question->attributes = $questionAttributes;

        $this->assertEquals($expectedResult, $question->preSave());
    }

    /**
     * @return array
     */
    public function providerPreSave(): array
    {
        $questionFactory = new QuestionFactory();

        return [
            'no session' => [
                'questionAttributes' => $questionFactory->generateOne([
                    'sessionId' => '',
                ]),
                'expectedResult' => true,
            ],
            'session' => [
                'questionAttributes' => $questionFactory->generateOne([
                    'sessionId' => '123',
                ]),
                'expectedResult' => true,
            ],
            'bad question data' => [
                'questionAttributes' => $questionFactory->generateOne([
                    'sessionId' => '',
                    'questionText' => '',
                ]),
                'expectedResult' => false,
            ],
        ];
    }
}
