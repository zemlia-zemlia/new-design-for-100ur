<?php


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
        $this->user = $user;
    }

    public function sendTestNotification()
    {
        $this->mailer->sendMail();
    }

    /**
     * Отправляет пользователю письмо со ссылкой на подтверждение email.
     * Если указан параметр $newPassword, он будет выслан в письме  как новый пароль
     * @param null $newPassword
     * @param bool $useSMTP
     * @return bool
     */
    public function sendConfirmation($newPassword = null, $useSMTP = false)
    {
        $mailTransportType = ($useSMTP === true) ? GTMail::TRANSPORT_TYPE_SMTP : GTMail::TRANSPORT_TYPE_SENDMAIL;
        $this->mailer->setTransportType($mailTransportType);

        $confirmLink = CHtml::decode(Yii::app()->createUrl('user/confirm', [
                'email' => $this->user->email,
                'code' => $this->user->confirm_code,
            ])) . "?utm_source=100yuristov&utm_medium=mail&utm_campaign=user_registration";

        $this->mailer->subject = "100 Юристов - Подтверждение Email";


        $this->mailer->message = "
            <h1>Пожалуйста подтвердите Email</h1>
            <p>Здравствуйте!<br />";

        if ($this->user->role == User::ROLE_JURIST) {
            $this->mailer->message .= "Вы зарегистрировались в качестве юриста на сайте " . CHtml::link("100 Юристов", Yii::app()->createUrl('/')) . "</p>" .
                "<p>Для активации профиля Вам необходимо подтвердить email. Для этого нажмите кнопку 'Подтвердить Email':</p>";
        } elseif ($this->user->role == User::ROLE_BUYER) {
            $this->mailer->message .= "Вы зарегистрировались в качестве покупателя лидов на сайте " . CHtml::link("100 Юристов", Yii::app()->createUrl('/')) . "</p>" .
                "<p>Для активации профиля Вам необходимо подтвердить email. Для этого нажмите кнопку 'Подтвердить Email':</p>";
        } elseif ($this->user->role == User::ROLE_PARTNER) {
            $this->mailer->message .= "Вы зарегистрировались в качестве вебмастера на сайте " . CHtml::link("100 Юристов", Yii::app()->createUrl('/')) . "</p>" .
                "<p>Для активации профиля Вам необходимо подтвердить email. Для этого нажмите кнопку 'Подтвердить Email':</p>";
        } else {
            $this->mailer->message .= "Вы задали вопрос на сайте " . CHtml::link("100 Юристов", Yii::app()->createUrl('/')) . "</p>" .
                "<p>Для того, чтобы юристы увидели Ваш вопрос, необходимо подтвердить email. Для этого нажмите кнопку 'Подтвердить Email':</p>";
        }

        $this->mailer->message .= "<p><strong>" . CHtml::link("Подтвердить Email", $confirmLink, array('style' => ' padding: 10px;
            width: 150px;
            display: block;
            text-decoration: none;
            border: 1px solid #84BEEB;
            text-align: center;
            font-size: 18px;
            font-family: Arial, sans-serif;
            font-weight: bold;
            color: #000;
            background: linear-gradient(to bottom, #ffc154 0%,#e88b0f 100%);
            border: 1px solid #EF9A27;
            border-radius: 4px;
            line-height: 17px;
            margin:0 auto;
        ')) . "</strong></p>";

        if ($newPassword) {
            $this->mailer->message .= "<h2>Ваш временный пароль</h2>
            <p>После подтверждения Email вы сможете войти на сайт, используя временный пароль <strong>" . $newPassword . "</strong></p>";
        }
        $this->mailer->email = $this->user->email;

        return $this->mailer->sendMail();
    }
}
