<?php

use PHPUnit\Framework\TestCase;

class GTMailTest extends TestCase
{
    protected $mailer;

    public function setUp()
    {
        parent::setUp();
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

    public function testComposeMessage()
    {
        $mailerStub = $this->createMock(GTMail::class);

        $mailerStub->method('loadFooter')->willReturn('My footer');
        $mailerStub->method('loadHeader')->willReturn('My header');
        $mailerStub->message = 'Message text';

        $this->assertEquals('', $mailerStub->createMessage(false));
    }
}