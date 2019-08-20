<?php

use PHPUnit\Framework\TestCase;

class GTMailTest extends TestCase
{
    /** @var GTMail  */
    protected $mailer;

    public function setUp()
    {
        defined(YII_DEV) or define(YII_DEV, true);
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
}
