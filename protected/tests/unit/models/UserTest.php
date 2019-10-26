<?php


namespace Tests\unit\models;

use Codeception\Test\Unit;
use GTMail;
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

    public function testHashPassword()
    {
        $pass = 'Hello world';
        $this->assertNotEquals($pass, User::hashPassword($pass));

        $emptyPass = '';
        $this->assertNotEquals($emptyPass, User::hashPassword($emptyPass));
    }

    /**
     * Зашифруем пароль и проверим его
     */
    public function testHashAndValidatePassword()
    {
        $correctPassword = 'myPassword';
        $user = new User();
        $user->password = User::hashPassword($correctPassword);

        $this->assertEquals(true, $user->validatePassword($correctPassword));
        $this->assertEquals(false, $user->validatePassword('wrong password'));
    }

    public function testSendConfirmation()
    {
        $userNotifierMock = $this->createMock(UserNotifier::class);
        $userNotifierMock->expects($this->once())->method('sendConfirmation');

        $user = new User();
        $user->setNotifier($userNotifierMock);

        $user->sendConfirmation();
    }

    public function testSendBuyerNotification()
    {
        $userNotifierMock = $this->createMock(UserNotifier::class);
        $userNotifierMock->expects($this->once())->method('sendBuyerNotification');

        $user = new User();
        $user->setNotifier($userNotifierMock);

        $user->sendBuyerNotification(1);
    }

    public function testGetChangePasswordLink()
    {
        $user = new User();
        $user->email = 'vasya@pupkin.ru';
        $user->confirm_code = '123afg';

        $this->assertStringContainsString(urlencode($user->email), $user->getChangePasswordLink());
        $this->assertStringContainsString($user->confirm_code, $user->getChangePasswordLink());
    }

    public function testVerifyUnsubscribeCode()
    {
        $email = '911@100yuristov.com';
        $wrongCode = '123';
        $correctCode = md5(User::UNSUBSCRIBE_SALT . $email);

        $this->assertTrue(User::verifyUnsubscribeCode($correctCode, $email));
        $this->assertFalse(User::verifyUnsubscribeCode($wrongCode, $email));
    }

    public function testSetGetNotifier()
    {
        $user = new User();
        $this->assertInstanceOf(UserNotifier::class, $user->getNotifier());
    }

    public function testActivate()
    {
        $user = new User();
        $user->activate();

        $this->assertEquals(1, $user->active100);
    }
}
