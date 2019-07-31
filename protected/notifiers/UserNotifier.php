<?php

namespace app\notifiers;

/**
 * Класс, отвечающий за различные уведомления пользователям
 * Class UserNotifier
 * @todo Перенести сюда всю логику отправки уведомлений пользователям из класса User
 */
class UserNotifier
{
    /** @var GTMail $mailer */
    private $mailer;

    /** @var User $user */
    private $user;

    public function __construct(GTMail $mailer, User $user)
    {
        $this->mailer = $mailer;
    }

    public function sendTestNotification()
    {
        $this->mailer->sendMail();
    }
}
