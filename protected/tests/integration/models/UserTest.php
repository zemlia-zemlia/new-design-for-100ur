<?php

namespace Tests\Integration\Models;

use Codeception\Test\Unit;
use Tests\Factories\UserFactory;
use Tests\integration\BaseIntegrationTest;
use User;
use UserNotifier;
use Yii;

class UserTest extends BaseIntegrationTest
{
    const USER_TABLE = '100_user';

    protected function _before()
    {
        Yii::app()->db->createCommand()->truncateTable(self::USER_TABLE);
    }

    /**
     * Тест создания пользователя
     * @dataProvider providerTestCreate
     * @param array $userParams
     * @param bool $expectedSaveResult
     */
    public function testCreate($userParams, $expectedSaveResult)
    {
        $user = new \User();
        $user->attributes = $userParams;

        $saveResult = $user->save();

        if ($expectedSaveResult === true) {
            $this->assertEquals([], $user->getErrors());
        } else {
            $this->tester->dontSeeInDatabase(self::USER_TABLE, ['name' => $userParams['name']]);
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
        $user = new \User();
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

        $this->loadToDatabase(self::USER_TABLE, $fixture);
        $this->assertEquals(1, sizeof(User::getManagers()));
        $this->assertEquals(10000, User::getManagers()[0]->id);
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

        $this->loadToDatabase(self::USER_TABLE, $fixtures);

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

        $this->loadToDatabase(self::USER_TABLE, $fixtures);

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
    public function testChangePassword($newPassword, $expectedPasswordLength,$sendResult, $expectedResult)
    {
        $notifierMock = $this->createMock(UserNotifier::class);
        $notifierMock->method('sendChangedPassword')->willReturn($sendResult);
        $notifierMock->expects($this->once())->method('sendChangedPassword');

        $user = new User();
        $userAttributes = (new UserFactory())->generateOne();
        $user->attributes = $userAttributes;
        $user->setNotifier($notifierMock);

        $changePasswordResult = $user->changePassword($newPassword);

        $this->assertEquals($expectedPasswordLength, mb_strlen($user->password));
        $this->assertEquals($expectedPasswordLength, mb_strlen($user->password2));

        $this->assertEquals($expectedResult, $changePasswordResult);
    }

    public function providerChangePassword(): array
    {
        return [
            [
                'newPassword' => null,
                'expectedPasswordLength' => 6,
                'sendResult' => true,
                'expectedResult' => true,
            ],
            [
                'newPassword' => 'abcdef123',
                'expectedPasswordLength' => 9,
                'sendResult' => true,
                'expectedResult' => true,
            ],
            [
                'newPassword' => 'abcdef123',
                'expectedPasswordLength' => 9,
                'sendResult' => false,
                'expectedResult' => false,
            ],
        ];
    }

}
