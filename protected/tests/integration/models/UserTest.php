<?php

namespace Tests\Integration\Models;

use Answer;
use CActiveDataProvider;
use CHttpException;
use Codeception\Test\Unit;
use Comment;
use DateTime;
use Exception;
use Leadsource;
use PartnerTransaction;
use Question;
use Tests\Factories\AnswerFactory;
use Tests\Factories\CommentFactory;
use Tests\Factories\LeadSourceFactory;
use Tests\Factories\PartnerTransactionFactory;
use Tests\Factories\QuestionFactory;
use Tests\Factories\UserFactory;
use Tests\Factories\YuristSettingsFactory;
use Tests\integration\BaseIntegrationTest;
use User;
use UserNotifier;
use UserStatusRequest;
use Yii;
use YuristSettings;

class UserTest extends BaseIntegrationTest
{

    protected function _before()
    {
        Yii::app()->db->createCommand()->truncateTable(User::getFullTableName());
    }

    /**
     * Тест создания пользователя
     * @dataProvider providerTestCreate
     * @param array $userParams
     * @param bool $expectedSaveResult
     */
    public function testCreate($userParams, $expectedSaveResult)
    {
        $user = new User();
        $user->attributes = $userParams;

        $saveResult = $user->save();

        if ($expectedSaveResult === true) {
            $this->assertEquals([], $user->getErrors());
        } else {
            $this->tester->dontSeeInDatabase(User::getFullTableName(), ['name' => $userParams['name']]);
        }
        $this->assertEquals($expectedSaveResult, $saveResult);
    }

    /**
     * @return array
     */
    public function providerTestCreate()
    {
        $correctAttributes = (new UserFactory())->generateOne();
        $correctAttributes = array_merge($correctAttributes, [
            'password2' => $correctAttributes['password'],
        ]);
        $wrongPassword2Attributes = array_merge($correctAttributes, [
            'password2' => '376736573575',
        ]);

        return [
            'correct' => [
                'userParams' => $correctAttributes,
                'saveResult' => true,
            ],
            'passwords not match' => [
                'userParams' => $wrongPassword2Attributes,
                'saveResult' => false,
            ],
        ];
    }

    /**
     *  Тест получения роли
     */
    public function testGetRoleName()
    {
        $user = new User();
        $user->role = 10;
        $this->assertEquals('юрист', $user->getRoleName());

        $user->role = 666;
        $this->assertEquals(null, $user->getRoleName());
    }

    /**
     * Тестирование получения менеджеров из базы
     */
    public function testGetManagers()
    {
        $fixture = [
            [
                'name' => 'Манагер',
                'id' => 10000,
                'role' => User::ROLE_MANAGER,
                'active100' => 1
            ],
            [
                'name' => 'Манагер неактивный',
                'id' => 10001,
                'role' => User::ROLE_MANAGER,
                'active100' => 0
            ],
            [
                'name' => 'Покупатель',
                'id' => 10002,
                'role' => User::ROLE_BUYER,
                'active100' => 1
            ],
        ];

        $this->loadToDatabase(User::getFullTableName(), $fixture);
        $this->assertEquals(1, sizeof(User::getManagers()));
        $this->assertEquals(10000, User::getManagers()[0]->id);
    }

    public function testGetManagersNames()
    {
        $fixture = [
            [
                'name' => 'Манагер',
                'id' => 10000,
                'role' => User::ROLE_MANAGER,
                'active100' => 1
            ],
            [
                'name' => 'Манагер неактивный',
                'id' => 10001,
                'role' => User::ROLE_MANAGER,
                'active100' => 0
            ],
        ];

        $this->loadToDatabase(User::getFullTableName(), $fixture);
        $managersName = User::getManagersNames();
        $this->assertEquals(2, sizeof($managersName));
        $this->assertEquals('нет руководителя', $managersName[0]);
        $this->assertEquals('Манагер', $managersName[10000]);
    }

    /**
     * Тестирование получения юристов из базы
     */
    public function testGetAllJuristsIdsNames()
    {
        $fixtures = [
            (new UserFactory())->generateOne([
                'name' => 'Ivan',
                'id' => 100001,
                'role' => User::ROLE_JURIST,
                'active100' => 1,
            ]),
            (new UserFactory())->generateOne([
                'role' => User::ROLE_JURIST,
                'active100' => 1,
            ]),
            (new UserFactory())->generateOne([
                'role' => User::ROLE_BUYER,
                'active100' => 1,
            ]),
            (new UserFactory())->generateOne([
                'role' => User::ROLE_JURIST,
                'active100' => 0,
            ]),
        ];

        $this->loadToDatabase(User::getFullTableName(), $fixtures);

        $this->assertEquals(2, sizeof(User::getAllJuristsIdsNames()));
        $this->assertEquals('Ivan', User::getAllJuristsIdsNames()[100001]);
    }

    /**
     * Тестирование получения юристов из базы
     */
    public function testGetAllBuyersIdsNames()
    {
        $fixtures = [
            (new UserFactory())->generateOne([
                'name' => 'Ivan',
                'lastName' => 'Kulakov',
                'id' => 100001,
                'role' => User::ROLE_BUYER,
                'active100' => 1,
            ]),
            (new UserFactory())->generateOne([
                'role' => User::ROLE_BUYER,
                'active100' => 1,
            ]),
            (new UserFactory())->generateOne([
                'role' => User::ROLE_PARTNER,
                'active100' => 1,
            ]),
            (new UserFactory())->generateOne([
                'role' => User::ROLE_BUYER,
                'active100' => 0,
            ]),
        ];

        $this->loadToDatabase(User::getFullTableName(), $fixtures);

        $this->assertEquals(2, sizeof(User::getAllBuyersIdsNames()));
        $this->assertEquals('Kulakov Ivan', User::getAllBuyersIdsNames()[100001]);
    }

    /**
     * Попытка создать пользователя с существующим мейлом
     */
    public function testDuplicateEmail()
    {
        $userAttributes = (new UserFactory())->generateOne();
        $userAttributes = array_merge($userAttributes, [
            'password2' => $userAttributes['password'],
        ]);

        $user1 = new User();
        $user1->setAttributes($userAttributes);
        $this->assertEquals(true, $user1->save());

        $user2 = new User();
        $user2->setAttributes($userAttributes);

        $this->assertEquals(false, $user2->save());
        $this->assertArrayHasKey('email', $user2->getErrors());
    }

    /**
     * @dataProvider providerChangePassword
     * @param $newPassword
     */
    public function testChangePassword(
        $userAttributes,
        $newPassword,
        $expectedPasswordLength,
        $sendResult,
        $sendInvokedTimes,
        $expectedResult
    )
    {
        $notifierMock = $this->createMock(UserNotifier::class);
        $notifierMock->method('sendChangedPassword')->willReturn($sendResult);
        if ($sendInvokedTimes > 0) {
            $notifierMock->expects($this->once())->method('sendChangedPassword');
        }

        $user = new User();
        $user->attributes = $userAttributes;
        $user->setNotifier($notifierMock);

        $changePasswordResult = $user->changePassword($newPassword);

        $this->assertEquals($expectedPasswordLength, mb_strlen($user->password));
        $this->assertEquals($expectedPasswordLength, mb_strlen($user->password2));

        $this->assertEquals($expectedResult, $changePasswordResult);
    }

    public function providerChangePassword(): array
    {
        $userFactory = new UserFactory();
        return [
            [
                'userAttributes' => $userFactory->generateOne(),
                'newPassword' => null,
                'expectedPasswordLength' => 6,
                'sendResult' => true,
                'sendInvokedTimes' => 1,
                'expectedResult' => true,
            ],
            [
                'userAttributes' => $userFactory->generateOne(),
                'newPassword' => 'abcdef123',
                'expectedPasswordLength' => 9,
                'sendResult' => true,
                'sendInvokedTimes' => 1,
                'expectedResult' => true,
            ],
            [
                'userAttributes' => $userFactory->generateOne(),
                'newPassword' => 'abcdef123',
                'expectedPasswordLength' => 9,
                'sendResult' => false,
                'sendInvokedTimes' => 1,
                'expectedResult' => false,
            ],
            [
                'userAttributes' => $userFactory->generateOne([
                    'name' => '',
                ]),
                'newPassword' => 'abcdef123',
                'expectedPasswordLength' => 9,
                'sendResult' => false,
                'sendInvokedTimes' => 0,
                'expectedResult' => false,
            ],
        ];
    }

    /**
     * @dataProvider providerGetReferalBonus
     * @param array $userAttributes
     * @param integer $expectedBonus
     */
    public function testGetReferalBonus($userAttributes, $expectedBonus)
    {
        $answersOfYuristWithFewAnswers = (new AnswerFactory())->generateFew(5, [
            'authorId' => 1000,
        ]);
        $settingsOfYuristWithFewAnswers = (new YuristSettingsFactory())->generateOne([
            'yuristId' => 1000,
        ]);

        $answersOfYuristWithManyAnswers = (new AnswerFactory())->generateFew(50, [
            'authorId' => 1001,
        ]);
        $settingsOfYuristWithManyAnswers = (new YuristSettingsFactory())->generateOne([
            'yuristId' => 1001,
        ]);

        $questions = (new QuestionFactory())->generateFew(3, [
            'authorId' => 1003,
        ]);

        $this->loadToDatabase(Answer::getFullTableName(), $answersOfYuristWithFewAnswers);
        $this->loadToDatabase(Answer::getFullTableName(), $answersOfYuristWithManyAnswers);
        $this->loadToDatabase(Question::getFullTableName(), $questions);
        $this->tester->haveInDatabase(YuristSettings::getFullTableName(), $settingsOfYuristWithFewAnswers);
        $this->tester->haveInDatabase(YuristSettings::getFullTableName(), $settingsOfYuristWithManyAnswers);

        $user = new User();
        $user->scenario = 'test';
        $user->attributes = $userAttributes;
        $user->save();

        $this->assertEquals($userAttributes['id'], $user->id);

        if ($userAttributes['role'] == User::ROLE_JURIST) {
            $this->assertInstanceOf(YuristSettings::class, $user->settings);
        }

        $this->assertEquals($expectedBonus, $user->referalOk());
    }

    /**
     * @return array
     */
    public function providerGetReferalBonus(): array
    {
        $yuristWithFewAnswersAttributes = (new UserFactory())->generateOne([
            'refId' => 10,
            'role' => User::ROLE_JURIST,
            'id' => 1000,
            'password' => '123456',
            'password2' => '123456',
        ]);
        $yuristWithManyAnswersAttributes = (new UserFactory())->generateOne([
            'refId' => 10,
            'role' => User::ROLE_JURIST,
            'id' => 1001,
            'password' => '123456',
            'password2' => '123456',
        ]);

        $clientWithoutQuestions = (new UserFactory())->generateOne([
            'refId' => 10,
            'role' => User::ROLE_CLIENT,
            'id' => 1002,
            'password' => '123456',
            'password2' => '123456',
        ]);

        $clientWithQuestions = (new UserFactory())->generateOne([
            'refId' => 10,
            'role' => User::ROLE_CLIENT,
            'id' => 1003,
            'password' => '123456',
            'password2' => '123456',
        ]);

        return [
            'no reference' => [
                'userAttributes' => (new UserFactory())->generateOne([
                    'refId' => 0,
                    'password' => '123456',
                    'password2' => '123456',
                ]),
                'expectedBonus' => 0,
            ],
            'yurist with few answers' => [
                'userAttributes' => $yuristWithFewAnswersAttributes,
                'expectedBonus' => 0,
            ],
            'yurist with many answers' => [
                'userAttributes' => $yuristWithManyAnswersAttributes,
                'expectedBonus' => 25000,
            ],
            'client without questions' => [
                'userAttributes' => $clientWithoutQuestions,
                'expectedBonus' => 0,
            ],
            'client with questions' => [
                'userAttributes' => $clientWithQuestions,
                'expectedBonus' => 5000,
            ],
        ];
    }

    public function testCalculateWebmasterBalance()
    {
        $partnerTransactions = (new PartnerTransactionFactory())->generateFew(5, [
            'partnerId' => 100,
            'sum' => 15000,
            'datetime' => (new DateTime())->modify('-6 days')->format('Y-m-d H:i:s'),
        ]);

        $partnerTransactions[] = (new PartnerTransactionFactory())->generateOne([
            'partnerId' => 100,
            'sum' => 12000,
            'leadId' => 10,
            'datetime' => (new DateTime())->modify('-6 hours')->format('Y-m-d H:i:s'),
        ]);

        $this->loadToDatabase(PartnerTransaction::getFullTableName(), $partnerTransactions);

        $user = new User();
        $user->id = 100;
        $user->role = User::ROLE_PARTNER;

        $this->assertEquals(87000, $user->calculateWebmasterBalance());
        $this->assertEquals(12000, $user->calculateWebmasterHold());

        $client = new User();
        $client->role = User::ROLE_CLIENT;
        $this->assertEquals(0, $client->calculateWebmasterHold());
    }

    /**
     * @dataProvider providerGetProfileNotification
     * @param string|boolean $expectedResult
     * @param array $userAttributes
     * @param array $yuristSettings
     */
    public function testGetProfileNotification($expectedResult, $userAttributes, $yuristSettings)
    {
        if ($yuristSettings) {
            $this->tester->haveInDatabase(YuristSettings::getFullTableName(), $yuristSettings);
        }

        $user = new User();
        $user->scenario = 'test';
        $user->attributes = $userAttributes;
        $user->save();

        if ($expectedResult === false) {
            $this->assertFalse($user->getProfileNotification());
        }

        $this->assertStringContainsString($expectedResult, $user->getProfileNotification());
    }

    public function providerGetProfileNotification(): array
    {
        return [
            [
                'expectedResult' => false,
                'userAttributes' => (new UserFactory())->generateOne([
                    'role' => User::ROLE_PARTNER,
                    'id' => 1000,
                    'password' => '123456',
                    'password2' => '123456',
                ]),
                'yuristSettings' => null,
            ],
            [
                'expectedResult' => false,
                'userAttributes' => (new UserFactory())->generateOne([
                    'role' => User::ROLE_JURIST,
                    'id' => 1001,
                    'password' => '123456',
                    'password2' => '123456',
                ]),
                'yuristSettings' => null,
            ],
            [
                'expectedResult' => 'Пожалуйста, подтвердите свою квалификацию',
                'userAttributes' => (new UserFactory())->generateOne([
                    'role' => User::ROLE_JURIST,
                    'id' => 1002,
                    'password' => '123456',
                    'password2' => '123456',
                ]),
                'yuristSettings' => (new YuristSettingsFactory())->generateOne([
                    'yuristId' => 1002,
                    'isVerified' => 0,
                ]),
            ],
            [
                'expectedResult' => 'Пожалуйста, загрузите свою фотографию',
                'userAttributes' => (new UserFactory())->generateOne([
                    'role' => User::ROLE_JURIST,
                    'id' => 1003,
                    'password' => '123456',
                    'password2' => '123456',
                    'avatar' => null,
                ]),
                'yuristSettings' => (new YuristSettingsFactory())->generateOne([
                    'yuristId' => 1003,
                    'isVerified' => 1,
                ]),
            ],
            [
                'expectedResult' => 'Пожалуйста, заполните текст приветствия в своем профиле',
                'userAttributes' => (new UserFactory())->generateOne([
                    'role' => User::ROLE_JURIST,
                    'id' => 1004,
                    'password' => '123456',
                    'password2' => '123456',
                ]),
                'yuristSettings' => (new YuristSettingsFactory())->generateOne([
                    'yuristId' => 1004,
                    'isVerified' => 1,
                    'hello' => '',
                ]),
            ],
            [
                'expectedResult' => 'Пожалуйста, укажите телефон или Email',
                'userAttributes' => (new UserFactory())->generateOne([
                    'role' => User::ROLE_JURIST,
                    'id' => 1005,
                    'password' => '123456',
                    'password2' => '123456',
                ]),
                'yuristSettings' => (new YuristSettingsFactory())->generateOne([
                    'yuristId' => 1005,
                    'isVerified' => 1,
                    'phoneVisible' => '',
                ]),
            ],
            [
                'expectedResult' => 'Пожалуйста, напишите немного о себе в своем профиле',
                'userAttributes' => (new UserFactory())->generateOne([
                    'role' => User::ROLE_JURIST,
                    'id' => 1006,
                    'password' => '123456',
                    'password2' => '123456',
                ]),
                'yuristSettings' => (new YuristSettingsFactory())->generateOne([
                    'yuristId' => 1006,
                    'isVerified' => 1,
                    'description' => '',
                ]),
            ],
            [
                'expectedResult' => 'Пожалуйста, укажите свои специализации в профиле',
                'userAttributes' => (new UserFactory())->generateOne([
                    'role' => User::ROLE_JURIST,
                    'id' => 1007,
                    'password' => '123456',
                    'password2' => '123456',
                ]),
                'yuristSettings' => (new YuristSettingsFactory())->generateOne([
                    'yuristId' => 1007,
                    'isVerified' => 1,
                ]),
            ],
        ];
    }

    public function testPublishNewQuestions()
    {
        $questionSourceUser = new User();
        $questionSourceUser->scenario = 'test';
        $questionSourceUser->id = 200;
        $questionSourceUser->save(false);

        $questionSource = new Leadsource();
        $questionSource->scenario = 'test';
        $questionSource->attributes = (new LeadSourceFactory())->generateOne([
            'id' => 1,
            'userId' => $questionSourceUser->id,
        ]);
        $questionSource->save(false);

        $questionsFactory = new QuestionFactory();
        $questions = $questionsFactory->generateFew(5, [
            'authorId' => 15,
            'status' => Question::STATUS_NEW,
        ]);
        $questions[] = $questionsFactory->generateOne([
            'authorId' => 15,
            'status' => Question::STATUS_NEW,
            'sourceId' => 1,
            'buyPrice' => 1000,
        ]);
        $questions[] = $questionsFactory->generateOne([
            'authorId' => 15,
            'status' => Question::STATUS_SPAM,
        ]);
        $questions[] = $questionsFactory->generateOne([
            'authorId' => 16,
            'status' => Question::STATUS_NEW,
        ]);
        $this->loadToDatabase(Question::getFullTableName(), $questions);

        $user = new User();
        $user->id = 15;

        $this->assertEquals(6, $user->publishNewQuestions());
        $this->tester->seeInDatabase(Question::getFullTableName(), [
            'authorId' => 15,
            'status' => Question::STATUS_CHECK,
        ]);
        $this->tester->seeInDatabase(PartnerTransaction::getFullTableName(), [
            'sourceId' => 1,
            'sum' => 1000,
            'partnerId' => $questionSourceUser->id,
        ]);
    }

    /**
     * @dataProvider providerSendChangePasswordLink
     * @param array $userAttributes
     * @param boolean $sendResult
     * @param boolean|Exception $expectedResult
     * @throws CHttpException
     */
    public function testSendChangePasswordLink($userAttributes, $sendResult, $expectedResult)
    {
        $notifierMock = $this->createMock(UserNotifier::class);
        $notifierMock->method('sendChangePasswordLink')->willReturn($sendResult);
        if (!($expectedResult instanceof Exception)) {
            $notifierMock->expects($this->once())->method('sendChangePasswordLink');
        }

        $user = new User();
        $user->attributes = $userAttributes;
        $user->setNotifier($notifierMock);

        if ($expectedResult instanceof Exception) {
            $this->expectException(CHttpException::class);
        }

        $sendResult = $user->sendChangePasswordLink();

        if (!($expectedResult instanceof Exception)) {
            $this->assertEquals([], $user->getErrors());
            $this->assertEquals($expectedResult, $sendResult);
            $this->assertNotEquals('', $user->confirm_code);
        }
    }

    public function providerSendChangePasswordLink()
    {
        return [
            'cannot save' => [
                'userAttributes' => (new UserFactory())->generateOne([
                    'confirm_code' => '',
                    'name' => '',
                    'email' => '',
                ]),
                'sendResult' => false,
                'expectedResult' => new CHttpException(400),
            ],
            'saved and sent' => [
                'userAttributes' => (new UserFactory())->generateOne([
                    'confirm_code' => '123',
                ]),
                'sendResult' => true,
                'expectedResult' => true,
            ],
        ];
    }

    public function testSearch()
    {
        $leadsFixture = [
            (new UserFactory())->generateOne([
                'name' => 'Вася',
                'active100' => 1,
            ]),
            (new UserFactory())->generateOne([
                'name' => 'Иван',
                'active100' => 0,
            ]),
            (new UserFactory())->generateOne([
                'name' => 'Игорь',
                'active100' => 1,
            ]),
        ];
        $this->loadToDatabase(User::getFullTableName(), $leadsFixture);

        $searchModel = new User();
        $searchModel->name = 'Вася';
        $searchModel->active100 = 1;

        $searchResult = $searchModel->search();
        $this->assertInstanceOf(CActiveDataProvider::class, $searchResult);
        $this->assertEquals(1, $searchResult->totalItemCount);
    }

    /**
     * @dataProvider providerSendAnswerNotification
     * @param array $userAttributes
     * @param Question|null $question
     * @param Answer|null $answer
     * @param boolean $sendResult
     * @param boolean $expectedResult
     */
    public function testSendAnswerNotification(
        $userAttributes,
        ?Question $question,
        ?Answer $answer,
        $sendResult,
        $expectedResult
    )
    {
        $notifierMock = $this->createMock(UserNotifier::class);
        $notifierMock->method('sendAnswerNotification')->willReturn($sendResult);

        $user = new User();
        $user->attributes = $userAttributes;
        $user->setNotifier($notifierMock);

        $actualResult = $user->sendAnswerNotification($question, $answer);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function providerSendAnswerNotification(): array
    {
        $userFactory = new UserFactory();
        $question = $this->createMock(Question::class);
        $answer = $this->createMock(Answer::class);

        return [
            'inactive user' => [
                'userAttributes' => $userFactory->generateOne([
                    'active100' => 0,
                ]),
                'question' => $question,
                'answer' => $answer,
                'sendResult' => null,
                'expectedResult' => false,
            ],
            'active user, no question' => [
                'userAttributes' => $userFactory->generateOne(),
                'question' => null,
                'answer' => $answer,
                'sendResult' => null,
                'expectedResult' => false,
            ],
            'incorrect user data' => [
                'userAttributes' => $userFactory->generateOne([
                    'name' => '',
                ]),
                'question' => $question,
                'answer' => $answer,
                'sendResult' => true,
                'expectedResult' => true,
            ],
            'correct user data' => [
                'userAttributes' => $userFactory->generateOne([
                    'password' => '123456',
                    'password2' => '123456',
                ]),
                'question' => $question,
                'answer' => $answer,
                'sendResult' => true,
                'expectedResult' => true,
            ],
        ];
    }

    /**
     * @dataProvider providerSendCommentNotification
     * @param array $userAttributes
     * @param Question|null $question
     * @param Comment|null $comment
     * @param boolean $sendResult
     * @param boolean $expectedResult
     */
    public function testSendCommentNotification(
        $userAttributes,
        ?Question $question,
        ?Comment $comment,
        $sendResult,
        $expectedResult
    )
    {
        $notifierMock = $this->createMock(UserNotifier::class);
        $notifierMock->method('sendCommentNotification')->willReturn($sendResult);

        $user = new User();
        $user->attributes = $userAttributes;
        $user->setNotifier($notifierMock);

        $actualResult = $user->sendCommentNotification($question, $comment);

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function providerSendCommentNotification(): array
    {
        $userFactory = new UserFactory();
        $question = $this->createMock(Question::class);
        $comment = $this->createMock(Comment::class);

        return [
            'inactive user' => [
                'userAttributes' => $userFactory->generateOne([
                    'active100' => 0,
                ]),
                'question' => $question,
                'comment' => $comment,
                'sendResult' => false,
                'expectedResult' => false,
            ],
            'no question' => [
                'userAttributes' => $userFactory->generateOne(),
                'question' => null,
                'comment' => $comment,
                'sendResult' => false,
                'expectedResult' => false,
            ],
            'no comment' => [
                'userAttributes' => $userFactory->generateOne(),
                'question' => $question,
                'comment' => null,
                'sendResult' => false,
                'expectedResult' => false,
            ],
            'incorrect user' => [
                'userAttributes' => $userFactory->generateOne([
                    'name' => '',
                ]),
                'question' => $question,
                'comment' => $comment,
                'sendResult' => true,
                'expectedResult' => true,
            ],
            'correct user' => [
                'userAttributes' => $userFactory->generateOne([
                    'password' => '123456',
                    'password2' => '123456',
                ]),
                'question' => $question,
                'comment' => $comment,
                'sendResult' => true,
                'expectedResult' => true,
            ],
        ];
    }

    public function testGetFeed()
    {
        $userAttributes = (new UserFactory())->generateOne();
        $questionAttributes = (new QuestionFactory())->generateOne();
        $answerAttributes = (new AnswerFactory())->generateOne([
            'authorId' => $userAttributes['id'],
            'questionId' => $questionAttributes['id'],
        ]);
        $commentAttributes = (new CommentFactory())->generateFew(3, [
            'authorId' => $userAttributes['id'],
            'objectId' => $answerAttributes['id'],
            'dateTime' => (new DateTime())->modify('-6 hours')->format('Y-m-d H:i:s'),
            'type' => Comment::TYPE_ANSWER,
        ]);

        $this->loadToDatabase(Comment::getFullTableName(), $commentAttributes);
        $this->tester->haveInDatabase(Answer::getFullTableName(), $answerAttributes);
        $this->tester->haveInDatabase(Question::getFullTableName(), $questionAttributes);
        $this->tester->haveInDatabase(User::getFullTableName(), $userAttributes);

        $user = new User();
        $user->id = $userAttributes['id'];

        $feed = $user->getFeed(2);
        $feedCount = $user->getFeed(2, true);

        $this->assertEquals(1, sizeof($feed));
        $this->assertEquals(1, $feedCount);
    }

    /**
     * @dataProvider providerGetRangName
     * @param int $rang
     * @param string $expectedName
     * @throws Exception
     */
    public function testGetRangName($rang, $expectedName)
    {
        $user = new User();
        $user->scenario = 'test';
        $user->attributes = (new UserFactory())->generateOne();
        $user->save(false);

        $yuristSettings = new YuristSettings();
        $yuristSettings->attributes = (new YuristSettingsFactory())->generateOne([
            'yuristId' => $user->id,
            'rang' => $rang,
        ]);
        $yuristSettings->save();

        $this->assertEquals($expectedName, $user->getRangName());
    }

    public function providerGetRangName(): array
    {
        return [
            [
                'rang' => 1,
                'expectedName' => 'Специалист',
            ],
        ];
    }

    public function testGetRecentQuestionCount()
    {
        $questions = (new QuestionFactory())->generateFew(5, [
            'authorId' => 10,
            'createDate' => (new DateTime())->modify('-3 hours')->format('Y-m-d H:i:s'),
        ]);

        $this->loadToDatabase(Question::getFullTableName(), $questions);

        $user = new User();
        $user->id = 10;

        $this->assertEquals(5, $user->getRecentQuestionCount(6));
        $this->assertEquals(0, $user->getRecentQuestionCount(2));
    }
}
