<?php

use \PHPUnit\Framework\TestCase;
use app\notifiers\UserNotifier;

class UserNotifierTest extends TestCase
{
    public function testCreateNotifier()
    {
        $mailerMock = $this->createMock(GTMail::class);
        $userMock = $this->createMock(User::class);
        $notifier = new UserNotifier($mailerMock, $userMock);

        $mailerMock->expects($this->once())->method('sendMail');

        $notifier->sendTestNotification();
    }
}
