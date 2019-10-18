<?php

namespace Tests\Unit\Notifiers;

use Codeception\Test\Unit;
use GTMail;
use User;
use UserNotifier;

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
