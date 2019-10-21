<?php


namespace Tests\unit\models;

use Codeception\Test\Unit;
use Tests\Factories\UserFactory;
use User;
use UserNotifier;

class UserTest extends Unit
{
    /**
     * @dataProvider providerTestSendNewPassword
     * @param bool $sendResult
     * @param bool $expectedResult
     */
    public function testSendNewPassword($sendResult, $expectedResult)
    {
        $notifierMock = $this->createMock(UserNotifier::class);
        $notifierMock->method('sendNewPassword')->willReturn($sendResult);
        $notifierMock->expects($this->once())->method('sendNewPassword');

        $user = new User();
        $user->setNotifier($notifierMock);

        $this->assertEquals($expectedResult, $user->sendNewPassword('123456'));
    }

    /**
     * @return array
     */
    public function providerTestSendNewPassword(): array
    {
        return [
            [
                'sendResult' => true,
                'expectedResult' => true,
            ],
            [
                'sendResult' => false,
                'expectedResult' => false,
            ],
        ];
    }

    /**
     * @dataProvider providerTestGetAvatarUrl
     * @param string $avatar
     * @param string $size
     * @param string $expectedResult
     */
    public function testGetAvatarUrl($avatar, $size, $expectedResult)
    {
        $user = new User();
        $user->avatar = $avatar;

        $this->assertEquals($expectedResult, $user->getAvatarUrl($size));
    }

    /**
     * @return array
     */
    public function providerTestGetAvatarUrl(): array
    {
        return [
            [
                'avatar' => null,
                'size' => 'thumb',
                'expectedResult' => User::DEFAULT_AVATAR_FILE,
            ],
            [
                'avatar' => null,
                'size' => null,
                'expectedResult' => User::DEFAULT_AVATAR_FILE,
            ],
            [
                'avatar' => 'vasya.jpg',
                'size' => 'thumb',
                'expectedResult' => User::USER_PHOTO_PATH . User::USER_PHOTO_THUMB_FOLDER . '/vasya.jpg',
            ],
            [
                'avatar' => 'vasya.jpg',
                'size' => null,
                'expectedResult' => User::USER_PHOTO_PATH . '/vasya.jpg',
            ],
        ];
    }

    /**
     * @dataProvider providerTestGetShortName
     * @param string $name
     * @param string $name2
     * @param string $lastName
     * @param string $expectedResult
     */
    public function testGetShortName($name, $name2, $lastName, $expectedResult)
    {
        $user = new User();
        $user->name = $name;
        $user->name2 = $name2;
        $user->lastName = $lastName;

        $this->assertEquals($expectedResult, $user->getShortName());
    }

    /**
     * @return array
     */
    public function providerTestGetShortName(): array
    {
        return [
            [
                'name' => 'Иван',
                'name2' => 'Васильевич',
                'lastName' => 'Грозный',
                'expectedResult' => 'Грозный И.В.',
            ],
            [
                'name' => 'Иван',
                'name2' => '',
                'lastName' => 'Грозный',
                'expectedResult' => 'Грозный И.',
            ],
            [
                'name' => 'Мадонна',
                'name2' => '',
                'lastName' => '',
                'expectedResult' => 'Мадонна',
            ],
            [
                'name' => '',
                'name2' => '',
                'lastName' => 'Наполеон',
                'expectedResult' => 'Наполеон',
            ],
        ];
    }
}
