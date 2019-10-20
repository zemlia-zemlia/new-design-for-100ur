<?php

namespace Tests\Unit\Extensions;

use FileSystemHelper;
use GTMail;

class GTMailTest extends Unit
{
    /** @var GTMail  */
    protected $mailer;

    public function setUp()
    {
        defined(YII_DEV) or define(YII_DEV, true);
        parent::setUp();
        FileSystemHelper::delFolderContent(GTMail::getTestMessagesFolder() . '/' . GTMail::TEST_MESSAGES_FOLDER);
        $this->mailer = new GTMail();
    }

    public function testConstructor()
    {
        $this->assertEquals(true, $this->mailer->isTestMode());
    }

    public function testSaveMessage()
    {
        $this->mailer->subject = 'Тестовое письмо';
        $this->mailer->message = 'Текст тестового письма';
        $this->mailer->email = 'hello@100yuristov.com';

        $this->assertEquals(true, $this->mailer->sendMail());
    }
}
