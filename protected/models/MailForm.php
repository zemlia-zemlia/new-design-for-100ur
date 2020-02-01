<?php

/**
 * Класс почтовой рассылки
 */
class MailForm extends CFormModel
{
    public $recipientEmail;
    public $roleId;
    public $subject;
    public $message;

    /**
     * Правила проверки полей
     */
    public function rules()
    {
        return array(
            // username and password are required
            array('subject, message', 'required', 'message' => 'Поле {attribute} не может быть пустым'),
            array('recipientEmail', 'email', 'message' => 'Email не похож на настоящий'),
            array('roleId', 'numerical', 'integerOnly' => true),
        );
    }

    /**
     * Наименования полей формы
     */
    public function attributeLabels()
    {
        return array(
            'recipientEmail' => 'E-mail',
            'roleId' => 'Роль пользователей',
            'message' => 'Текст письма',
            'subject' => 'Тема сообщения',
        );
    }

    /**
     * Отправка рассылки
     * @param boolean $useSMTP Использовать для отправки SMTP
     * @return integer Количесто отправленных писем
     */
    public function send($useSMTP = false)
    {
        // разрешим скрипту работать долго
        ini_set('max_execution_time', 600);

        $this->message = nl2br($this->message);

        if ($this->recipientEmail != '') {
            // отправляем одному получателю
            $mailTransportType = ($useSMTP === true) ? GTMail::TRANSPORT_TYPE_SMTP : GTMail::TRANSPORT_TYPE_SENDMAIL;
            $mailer = new GTMail($mailTransportType);

            $mailer->subject = $this->subject;
            $mailer->message = $this->message;
            $mailer->email = $this->recipientEmail;
            return ($mailer->sendMail() === true) ? 1 : 0;
        } elseif ($this->roleId != '') {
            // отправляем группе
            $mailsSent = 0;
            $users = Yii::app()->db->createCommand()
                ->select('email, autologin')
                ->from('{{user}}')
                ->where('active100=1 AND isSubscribed=1 AND role=:role AND email!=""', [':role' => $this->roleId])
                ->queryAll();

            $mailTransportType = ($useSMTP === true) ? GTMail::TRANSPORT_TYPE_SMTP : GTMail::TRANSPORT_TYPE_SENDMAIL;

            foreach ($users as $user) {
                $mailer = new GTMail($mailTransportType);

                $mailer->subject = $this->subject;
                $mailer->message = $this->message;

                if ($user['autologin'] != '') {
                    $autologinLink = Yii::app()->createUrl('/site/index', ['autologin' => $user['autologin']]);
                    $mailer->message .= '<p>Ваша ссылка для входа на сайт без ввода пароля (ссылка действительна один раз):' .
                        CHtml::link($autologinLink, $autologinLink)
                        . '</p>';
                }

                $mailer->email = $user['email'];
                if ($mailer->sendMail()) {
                    $mailsSent++;
                }
            }

            return $mailsSent;
        }
    }

    /**
     * Создание заданий на отправку писем для рассылки
     * @param Mail $mail Рассылка
     * @return integer Количество отправленных писем
     */
    public function createTasks(Mail $mail)
    {
        $createTasksCount = 0;

        if ($this->recipientEmail != '') {
            // отправляем одному получателю
            $createTasksCount = Yii::app()->db->createCommand()
                ->insert('{{mailtask}}', [
                    'mailId' => $mail->id,
                    'email' => $this->recipientEmail,
                    'startDate' => date('Y-m-d'),
                ]);
        } elseif ($this->roleId != '') {
            // отправляем группе
            $users = Yii::app()->db->createCommand()
                ->select('email, id')
                ->from('{{user}}')
                ->where('active100=1 AND isSubscribed=1 AND role=:role AND email!=""', [':role' => $this->roleId])
                ->queryAll();

            foreach ($users as $user) {
                $createTasksCount += Yii::app()->db->createCommand()
                    ->insert('{{mailtask}}', [
                        'mailId' => $mail->id,
                        'email' => $user['email'],
                        'userId' => $user['id'],
                        'startDate' => date('Y-m-d'),
                    ]);
            }
        }
        return $createTasksCount;
    }
}
