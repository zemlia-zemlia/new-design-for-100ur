<?php

namespace Tests\Unit\Notifiers;

use Codeception\Test\Unit;
use Exception;
use GTMail;
use User;
use UserNotifier;
use Yii;

class UserNotifierTest extends Unit
{
    public function testCreateNotifier()
    {
        $mailerMock = $this->createMock(GTMail::class);
        $userMock = $this->createMock(User::class);
        $notifier = new UserNotifier($mailerMock, $userMock);

        $mailerMock->expects($this->once())->method('sendMail');

        $notifier->sendTestNotification();
    }

    /**
     * @dataProvider confirmationProvider
     *
     * @param int    $role
     * @param string $newPassword
     * @param string $expectedMailFragment
     *
     * @throws Exception
     */
    public function testSendUserConfirmationMail($role, $newPassword, $expectedMailFragment)
    {
        $mailer = Yii::app()->mailer;
        $user = new User();

        $user->setAttributes([
            'role' => $role,
            'email' => 'tester@100yuristov.local',
        ]);

        $notifier = new UserNotifier($mailer, $user);
        $sendResult = $notifier->sendConfirmation($newPassword);

        $this->assertTrue($sendResult);
        $this->assertStringContainsString($expectedMailFragment, $mailer->message);

        if ($newPassword) {
            $this->assertStringContainsString($newPassword, $mailer->message);
        }
    }

    /**
     * @return array
     */
    public function confirmationProvider(): array
    {
        return [
            'jurist' => [
                'role' => User::ROLE_JURIST,
                'newPassword' => '',
                'expectedMailFragment' => 'Вы зарегистрировались в качестве юриста',
            ],
            'buyer' => [
                'role' => User::ROLE_BUYER,
                'newPassword' => '',
                'expectedMailFragment' => 'Вы зарегистрировались в качестве покупателя лидов',
            ],
            'partner' => [
                'role' => User::ROLE_PARTNER,
                'newPassword' => '',
                'expectedMailFragment' => 'Вы зарегистрировались в качестве вебмастера',
            ],
            'client' => [
                'role' => User::ROLE_CLIENT,
                'newPassword' => '',
                'expectedMailFragment' => 'Вы задали вопрос на сайте',
            ],
            'client with password' => [
                'role' => User::ROLE_CLIENT,
                'newPassword' => '767werwr767r',
                'expectedMailFragment' => 'Вы задали вопрос на сайте',
            ],
        ];
    }
}
