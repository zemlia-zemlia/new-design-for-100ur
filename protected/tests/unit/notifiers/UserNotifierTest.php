<?php

use Codeception\Test\Unit;

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
}
