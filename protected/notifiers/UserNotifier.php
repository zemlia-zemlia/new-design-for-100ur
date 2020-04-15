<?php

namespace App\notifiers;

use App\models\Answer;
use App\models\Chat;
use CHtml;
use App\models\Comment;
use GTMail;
use MoneyFormat;
use App\models\Question;
use App\models\User;
use Yii;

/**
 * Класс, отвечающий за различные уведомления пользователям
 * Class App\notifiers\UserNotifier.
 */
class UserNotifier
{
    /** @var GTMail $mailer */
    private $mailer;

    /** @var \App\models\User $user */
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
     * Если указан параметр $newPassword, он будет выслан в письме  как новый пароль.
     *
     * @param null $newPassword
     * @param bool $useSMTP
     *
     * @return bool
     */
    public function sendConfirmation($newPassword = null, $useSMTP = false)
    {
        $mailTransportType = (true === $useSMTP) ? GTMail::TRANSPORT_TYPE_SMTP : GTMail::TRANSPORT_TYPE_SENDMAIL;
        $this->mailer->setTransportType($mailTransportType);

        $confirmLink = CHtml::decode(Yii::app()->createUrl('user/confirm', [
                'email' => $this->user->email,
                'code' => $this->user->confirm_code,
            ])) . '?utm_source=100yuristov&utm_medium=mail&utm_campaign=user_registration';

        $this->mailer->subject = '100 Юристов - Подтверждение Email';

        $this->mailer->message = '
            <h1>Пожалуйста подтвердите Email</h1>
            <p>Здравствуйте!<br />';

        if (User::ROLE_JURIST == $this->user->role) {
            $this->mailer->message .= 'Вы зарегистрировались в качестве юриста на сайте ' . CHtml::link('100 Юристов', Yii::app()->createUrl('/')) . '</p>' .
                "<p>Для активации профиля Вам необходимо подтвердить email. Для этого нажмите кнопку 'Подтвердить Email':</p>";
        } elseif (User::ROLE_BUYER == $this->user->role) {
            $this->mailer->message .= 'Вы зарегистрировались в качестве покупателя лидов на сайте ' . CHtml::link('100 Юристов', Yii::app()->createUrl('/')) . '</p>' .
                "<p>Для активации профиля Вам необходимо подтвердить email. Для этого нажмите кнопку 'Подтвердить Email':</p>";
        } elseif (User::ROLE_PARTNER == $this->user->role) {
            $this->mailer->message .= 'Вы зарегистрировались в качестве вебмастера на сайте ' . CHtml::link('100 Юристов', Yii::app()->createUrl('/')) . '</p>' .
                "<p>Для активации профиля Вам необходимо подтвердить email. Для этого нажмите кнопку 'Подтвердить Email':</p>";
        } else {
            $this->mailer->message .= 'Вы задали вопрос на сайте ' . CHtml::link('100 Юристов', Yii::app()->createUrl('/')) . '</p>' .
                "<p>Для того, чтобы юристы увидели Ваш вопрос, необходимо подтвердить email. Для этого нажмите кнопку 'Подтвердить Email':</p>";
        }

        $this->mailer->message .= '<p><strong>' . CHtml::link('Подтвердить Email', $confirmLink, ['style' => ' padding: 10px;
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
        ']) . '</strong></p>';

        if ($newPassword) {
            $this->mailer->message .= '<h2>Ваш временный пароль</h2>
            <p>После подтверждения Email вы сможете войти на сайт, используя временный пароль <strong>' . $newPassword . '</strong></p>';
        }
        $this->mailer->email = $this->user->email;

        $additionalHeaders = [
            'X-Postmaster-Msgtype' => 'Подтверждение Email',
            'List-id' => 'Подтверждение Email',
            'X-Mailru-Msgtype' => 'Подтверждение Email',
        ];

        return $this->mailer->sendMail(true, $additionalHeaders);
    }

    /**
     * Отправляет пользователю его пароль по почте (используем после активации аккаунта).
     *
     * @param string $newPassword новый пароль
     *
     * @return bool true - удалось отправить письмо, false - не удалось
     */
    public function sendNewPassword($newPassword)
    {
        $this->mailer->subject = CHtml::encode($this->user->name) . ', Ваш пароль для личного кабинета 100 юристов';
        $this->mailer->message = 'Здравствуйте!<br />
            Вы упешно зарегистрировались на портале 100 юристов.<br /><br />
            Ваш логин: ' . CHtml::encode($this->user->email) . '<br />
            Ваш временный пароль: ' . $newPassword . '<br /><br />
            Вы всегда можете поменять его на любой другой, зайдя в ' . CHtml::link('личный кабинет', Yii::app()->createUrl('site/login')) . ' на нашем сайте.<br /><br />
            <br /><br />';
        $this->mailer->email = $this->user->email;

        return $this->mailer->sendMail();
    }

    /**
     * Высылает на email пользователю ссылку на смену пароля.
     *
     * @param string $changePasswordLink
     *
     * @return bool true - удалось отправить письмо, false - не удалось
     */
    public function sendChangePasswordLink($changePasswordLink)
    {
        $this->mailer->subject = 'Смена пароля пользователя';
        $this->mailer->message = 'Здравствуйте!<br />
            Ваша ссылка для смены пароля на портале 100 Юристов:<br />' .
            CHtml::link($changePasswordLink, $changePasswordLink) .
            '<br />';

        $this->mailer->email = $this->user->email;

        return $this->mailer->sendMail();
    }

    /**
     * Высылает пароль $newPassword на email пользователю.
     *
     * @param string $newPassword Новый пароль
     *
     * @return bool true - удалось отправить письмо, false - не удалось
     */
    public function sendChangedPassword($newPassword)
    {
        $this->mailer->subject = 'Смена пароля пользователя';
        $this->mailer->message = 'Здравствуйте!<br />
            Вы или кто-то, указавший ваш E-mail, запросил восстановление пароля на портале 100 юристов.<br /><br />
            Ваш временный пароль: ' . $newPassword . '<br /><br />
            Вы всегда можете поменять его на любой другой, зайдя в ' . CHtml::link('личный кабинет', Yii::app()->createUrl('site/login')) . ' на нашем сайте.<br /><br />
            Если Вы не запрашивали восстановление пароля, обратитесь, пожалуйста, к администратору сайта. <br /><br />';
        $this->mailer->email = $this->user->email;

        return $this->mailer->sendMail();
    }

    /**
     * отправка письма пользователю, на вопрос которого дан ответ
     *
     * @param Answer $answer
     * @param Question $question
     * @param $questionLink
     * @param $testimonialLink
     *
     * @return bool
     */
    public function sendAnswerNotification(Answer $answer, Question $question, $questionLink, $testimonialLink)
    {
        $this->mailer->subject = CHtml::encode($this->user->name) . ', новый ответ на Ваш вопрос!';
        $this->mailer->message = '<h1>Новый ответ на Ваш вопрос</h1>
            <p>Здравствуйте, ' . CHtml::encode($this->user->name) . '<br /><br />
            Спешим сообщить, что на ' . CHtml::link('Ваш вопрос', $questionLink) . ' получен новый ответ юриста ';
        if (!$answer->videoLink) {
            $this->mailer->message .= CHtml::encode($answer->author->name . ' ' . $answer->author->lastName);
        }
        $this->mailer->message .= '.<br /><br />
            Будем держать Вас в курсе поступления других ответов. 
            <br /><br />
            ' . CHtml::link('Посмотреть ответ', $questionLink, ['class' => 'btn']) . '
            </p>';

        $this->mailer->message .= '.<br /><br />
            Вы также можете оставить отзыв юристу, оценив его ответ 
            <br />
            ' . CHtml::link('Оставить отзыв', $testimonialLink, ['class' => 'btn']) . '
            </p>';

        // отправляем письмо на почту пользователя
        $this->mailer->email = $this->user->email;

        $additionalHeaders = [
            'X-Postmaster-Msgtype' => 'Уведомление об ответе',
            'List-id' => 'Уведомление об ответе',
            'X-Mailru-Msgtype' => 'Уведомление об ответе',
        ];

        if ($this->mailer->sendMail(true, $additionalHeaders)) {
            Yii::log('Отправлено письмо пользователю ' . $this->user->email . ' с уведомлением об ответе на вопрос ' . $question->id, 'info', 'system.web.User');

            return true;
        } else {
            // не удалось отправить письмо
            Yii::log('Не удалось отправить письмо пользователю ' . $this->user->email . ' с уведомлением об ответе на вопрос ' . $question->id, 'error', 'system.web.User');

            return false;
        }
    }

    /**
     * функция отправки уведомления юристу или клиенту о новом комментарии на его ответ / комментарий.
     *
     * @param Question $question
     * @param Comment $comment
     *
     * @return bool
     */
    public function sendCommentNotification(Question $question, Comment $comment, $questionLink)
    {
        $this->mailer->subject = CHtml::encode($this->user->name) . ', обновление в переписке по вопросу!';
        $this->mailer->message = '<h1>Обновление в переписке по вопросу</h1>
            <p>Здравствуйте, ' . CHtml::encode($this->user->name) . '<br /><br />
            Спешим сообщить, что в переписке по вопросу ' . CHtml::link(CHtml::encode($question->title), $questionLink) . ' появился новый комментарий от ' . CHtml::encode($comment->author->name . ' ' . $comment->author->lastName) . '.
            <br /><br />
            Будем держать Вас в курсе поступления других комментариев. 
            <br /><br />
            ' . CHtml::link('Посмотреть комментарий', $questionLink, ['class' => 'btn']) . '
            </p>';

        $this->mailer->email = $this->user->email;

        $additionalHeaders = [
            'X-Postmaster-Msgtype' => 'Уведомление о комментарии',
            'List-id' => 'Уведомление о комментарии',
            'X-Mailru-Msgtype' => 'Уведомление о комментарии',
        ];

        if ($this->mailer->sendMail(true, $additionalHeaders)) {
            Yii::log('Отправлено письмо пользователю ' . $this->user->email . ' с уведомлением о комментарии ' . $comment->id, 'info', 'system.web.User');

            return true;
        } else {
            // не удалось отправить письмо
            Yii::log('Не удалось отправить письмо пользователю ' . $this->user->email . ' с уведомлением о комментарии ' . $comment->id, 'error', 'system.web.User');

            return false;
        }
    }

    /**
     * Отправляет покупателю письмо с уведомлением по его кампании.
     *
     * @param int $eventType
     *
     * @return bool
     */
    public function sendBuyerNotification($eventType)
    {
        $cabinetLink = Yii::app()->createUrl('/buyer');

        switch ($eventType) {
            case User::BUYER_EVENT_CONFIRM:
                $this->mailer->subject = CHtml::encode($this->user->name) . ', Ваша кампания одобрена';
                $this->mailer->message = '<h1>Ваша кампания одобрена модератором</h1>
                    <p>Здравствуйте, ' . CHtml::encode($this->user->name) . "<br /><br />
                    Ваша кампания по покупке лидов одобрена. Параметры кампании Вы можете увидеть в ее настройках
                    в <a href='" . $cabinetLink . "'>личном кабинете</a>. Для получения лидов Вам необходимо пополнить баланс. Способы пополнения
                    также доступны в личном кабинете.
                    </p>";
                break;
            case User::BUYER_EVENT_TOPUP:
                $this->mailer->subject = CHtml::encode($this->user->name) . ', Ваш баланс пополнен';
                $this->mailer->message = '<h1>Ваш баланс пополнен</h1>
                    <p>Здравствуйте, ' . CHtml::encode($this->user->name) . '<br /><br />
                    Ваш баланс пополнен и составляет ' . MoneyFormat::rubles($this->user->balance) . ' руб. '
                    . "Информация о списаниях и зачислениях доступна в <a href='" . $cabinetLink . "'>личном кабинете</a>.
                    </p>";
                break;
            case User::BUYER_EVENT_LOW_BALANCE:
                $this->mailer->subject = CHtml::encode($this->user->name) . ', уведомление о расходе средств';
                $this->mailer->message = '<h1>Уведомление о расходе средств</h1>
                    <p>Здравствуйте, ' . CHtml::encode($this->user->name) . '<br /><br />
                    Ваш баланс составляет ' . MoneyFormat::rubles($this->user->balance) . ' руб. '
                    . "Пополнить баланс, увидеть информацию о списаниях и зачислениях можно в <a href='" . $cabinetLink . "'>личном кабинете</a>.
                        
                    </p>";
                break;

            default:
                return false;
        }

        $this->mailer->email = $this->user->email;

        $additionalHeaders = [
            'X-Postmaster-Msgtype' => 'Уведомление покупателю',
            'List-id' => 'Уведомление покупателю',
            'X-Mailru-Msgtype' => 'Уведомление покупателю',
        ];

        if ($this->mailer->sendMail(true, $additionalHeaders)) {
            Yii::log('Отправлено письмо покупателю ' . $this->user->email, 'info', 'system.web.User');

            return true;
        } else {
            // не удалось отправить письмо
            Yii::log('Не удалось отправить письмо покупателю ' . $this->user->email, 'error', 'system.web.User');

            return false;
        }
    }

    /**
     * Отправка юристу уведомления о зачислении благодарности за консультацию.
     *
     * @param \App\models\Answer $answer
     * @param int $yuristBonus В копейках
     *
     * @return bool
     */
    public function sendDonateNotification(Answer $answer, $yuristBonus)
    {
        $yurist = $answer->author;

        $this->mailer->subject = 'Зачислена благодарность за ответ на вопрос';

        $this->mailer->message = '<h1>Благодарность за консультацию по вопросу</h1>
            <p>Здравствуйте, ' . CHtml::encode($yurist->name) . '<br /><br />' .
            'Вам зачислены ' . MoneyFormat::rubles($yuristBonus) . ' руб. в благодарность за консультацию по вопросу ' .
            CHtml::link(CHtml::encode($answer->question->title), Yii::app()->createUrl('question/view', ['id' => $answer->questionId])) . '</p>';
        $this->mailer->message .= '<p>Ваш баланс и история его изменений доступны в Личном кабинете.</p>';
        $this->mailer->message .= '<p>Благодарим за сотрудничество!</p>';

        $this->mailer->email = $yurist->email;

        $additionalHeaders = [
            'X-Postmaster-Msgtype' => 'Уведомление юристу о донате',
            'List-id' => 'Уведомление юристу о донате',
            'X-Mailru-Msgtype' => 'Уведомление юристу о донате',
        ];

        if ($this->mailer->sendMail(true, $additionalHeaders)) {
            Yii::log('Отправлено письмо юристу ' . $yurist->email . ' с уведомлением о благодарности по вопросу ' . $answer->question->id, 'info', 'system.web.User');

            return true;
        } else {
            // не удалось отправить письмо
            Yii::log('Не удалось отправить письмо пользователю ' . $yurist->email . ' с уведомлением о благодарности по вопросу ' . $answer->question->id, 'error', 'system.web.User');

            return false;
        }
    }

    /**
     * Отправка юристу уведомления о новом отзыве.
     */
    public function sendChatNote(User $user, Chat $chat)
    {
        $this->mailer->subject = 'Новый чат';

        $this->mailer->message = '<h1>Новый чат</h1>
            <p>Здравствуйте, ' . CHtml::encode($user->name) . '<br /><br />' .
            'ссылка на чат ' . CHtml::link('/chat?=room=' . $chat->chat_id, '/chat?=room=' . $chat->chat_id);

        $this->mailer->email = $this->user->email;

        $additionalHeaders = [
            'X-Postmaster-Msgtype' => 'Уведомление юристу об чате',
            'List-id' => 'Уведомление юристу об чате',
            'X-Mailru-Msgtype' => 'Уведомление юристу об чате',
        ];

        if ($this->mailer->sendMail(true, $additionalHeaders)) {
            Yii::log('Отправлено письмо юристу ' . $this->user->email . ' с уведомлением о новом чате', 'info', 'system.web.User');

            return true;
        } else {
            // не удалось отправить письмо
            Yii::log('Не удалось отправить письмо пользователю ' . $this->user->email . ' с уведомлением о новом отзыве', 'error', 'system.web.User');

            return false;
        }
    }
    /**
     * Отправка юристу уведомления о новом отзыве.
     */
    public function sendTestimonialNotification()
    {
        $this->mailer->subject = 'Вам оставили отзыв';

        $this->mailer->message = '<h1>Новый отзыв в вашем профиле</h1>
            <p>Здравствуйте, ' . CHtml::encode($this->user->name) . '<br /><br />' .
            'Вам только что оставили отзыв. Посмотреть его вы можете в своем профиле.';

        $this->mailer->email = $this->user->email;

        $additionalHeaders = [
            'X-Postmaster-Msgtype' => 'Уведомление юристу об отзыве',
            'List-id' => 'Уведомление юристу об отзыве',
            'X-Mailru-Msgtype' => 'Уведомление юристу об отзыве',
        ];

        if ($this->mailer->sendMail(true, $additionalHeaders)) {
            Yii::log('Отправлено письмо юристу ' . $this->user->email . ' с уведомлением о новом отзыве', 'info', 'system.web.User');

            return true;
        } else {
            // не удалось отправить письмо
            Yii::log('Не удалось отправить письмо пользователю ' . $this->user->email . ' с уведомлением о новом отзыве', 'error', 'system.web.User');

            return false;
        }
    }

    /**
     * Отправляет письмо юристу с уведомлением о смене ранга.
     *
     * @param array $newRangInfo
     *
     * @return bool
     */
    public function sendNewRangNotification($newRangInfo)
    {
        $this->mailer->subject = 'Измененилось ваше звание';

        $this->mailer->message = '<h1>Ваше новое звание: ' . $newRangInfo['name'] . '</h1>
            <p>Звания показывают активность и профессионализм юриста. Они присваиваются за определенное количество ответов, отзывов и среднюю оценку по отзывам.</p>';

        $this->mailer->email = $this->user->email;

        if ($this->mailer->sendMail()) {
            Yii::log('Отправлено письмо юристу ' . $this->user->email . ' с уведомлением о новом звании', 'info', 'system.web.User');

            return true;
        } else {
            Yii::log('Не удалось отправить письмо пользователю ' . $this->user->email . ' с уведомлением о новом звании', 'error', 'system.web.User');

            return false;
        }
    }
}
