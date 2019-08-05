<?php

namespace models;

use Codeception\Util\Fixtures;
use User;

class UserTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    const USER_TABLE = '100_user';

    protected function _before()
    {
        $this->tester->clearTable(self::USER_TABLE);
    }

    protected function _after()
    {
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
        }
        $this->assertEquals($expectedSaveResult, $saveResult);
        $this->tester->dontSeeInDatabase(self::USER_TABLE, ['name' => $userParams['name']]);
    }

    /**
     * @return array
     */
    public function providerTestCreate()
    {
        return [
            'correct' => [
                'userParams' => [
                    'name' => 'Иван',
                    'name2' => 'Васильевич',
                    'lastName' => 'Грозный',
                    'email' => 'ivan1@grozny.ru',
                    'phone' => '+7(988)7776655',
                    'townId' => 598,
                    'password' => '123456',
                    'password2' => '123456',
                ],
                'saveResult' => true,
            ],
            'correct, only name' => [
                'userParams' => [
                    'name' => 'Иван',
                    'name2' => '',
                    'lastName' => '',
                    'email' => 'ivan1@grozny.ru',
                    'phone' => '+7(988)7776655',
                    'townId' => 598,
                    'password' => '123456',
                    'password2' => '123456',
                ],
                'saveResult' => true,
            ],
            'passwords not match' => [
                'userParams' => [
                    'name' => 'Иван',
                    'name2' => 'Васильевич',
                    'lastName' => 'Грозный',
                    'email' => 'ivan2@grozny.ru',
                    'phone' => '+7(988)7776655',
                    'townId' => 598,
                    'password' => '123456',
                    'password2' => '12345',
                ],
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
            'name' => 'Манагер',
            'id' => 100,
            'role' => User::ROLE_MANAGER,
            'active100' => 1
        ];

        $this->tester->haveInDatabase(self::USER_TABLE, $fixture);

        $this->assertEquals(1, sizeof(User::getManagers()));
        $this->assertEquals(100, User::getManagers()[0]->id);
    }

    /**
     * Тестирование получения менеджеров из базы
     */
    public function testGetAllJuristsIdsNames()
    {
        $fixtures = [
            [
                'name' => 'Ivan',
                'id' => 101,
                'role' => User::ROLE_JURIST,
                'active100' => 1,
            ],
            [
                'name' => 'Аленушка',
                'id' => 102,
                'role' => User::ROLE_JURIST,
                'active100' => 1,
            ],
            [
                'name' => 'Баба Яга',
                'id' => 103,
                'role' => User::ROLE_JURIST,
                'active100' => 0,
            ],
        ];

        foreach ($fixtures as $fixture) {
            $this->tester->haveInDatabase(self::USER_TABLE, $fixture);
        }

        $this->assertEquals(2, sizeof(User::getAllJuristsIdsNames()));
        $this->assertEquals('Ivan', User::getAllJuristsIdsNames()[101]);
    }
}
