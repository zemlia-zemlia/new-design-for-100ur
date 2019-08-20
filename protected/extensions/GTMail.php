<?php

class GTMail
{
    const TRANSPORT_TYPE_SMTP = 'smtp';
    const TRANSPORT_TYPE_SENDMAIL = 'sendmail';

    public $subject;
    public $message;
    public $email;

    protected $mailer; // объект сторонней библиотеки для отправки почты
    protected $testMode = false;
    protected $transportType = self::TRANSPORT_TYPE_SMTP;

    /**
     * Конструктор
     * @param string $transportType Тип транспорта
     * @throws Exception
     */
    public function __construct($transportType = self::TRANSPORT_TYPE_SMTP)
    {
        $this->testMode = (YII_DEV === true) ? true : false;
        $this->transportType = $transportType;

        $transport = $this->createMailTransport($transportType);

        // Create the Mailer using your created Transport
        $this->mailer = new Swift_Mailer($transport);
    }

    /**
     * @return bool
     */
    public function isTestMode(): bool
    {
        return $this->testMode;
    }

    /**
     * @param bool $testMode
     * @return GTMail
     */
    public function setTestMode(bool $testMode): GTMail
    {
        $this->testMode = $testMode;
        return $this;
    }

    /**
     * Отправка сообщения
     * @param bool $appendSuffix Включать ли подпись
     * @return bool Результат отправки
     */
    public function sendMail($appendSuffix = true): bool
    {
        $mailerMessage = $this->createMessage($appendSuffix);

        if ($this->testMode == false) {
            return ($this->mailer->send($mailerMessage) > 0) ? true : false;
        }
        return $this->saveMessage($mailerMessage, $this->testMode);
    }

    /**
     * Сохраняет сообщение в папку на диске
     */
    protected function saveMessage(Swift_Message $mailerMessage, $testing)
    {
        try {
            file_put_contents($this->getTestMessageFilePath($testing), $mailerMessage->getBody());
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    protected function getTestMessagesFolder()
    {
        return Yii::getPathOfAlias('application.runtime.mail');
    }

    /**
     * @param bool $testing
     * @return bool|mixed|string
     * @throws Exception
     */
    protected function getTestMessageFilePath($testing = false): string
    {
        $messageFilePath = $this->getTestMessagesFolder();
        if ($testing == true) {
            $messageFilePath .= '/test';
            if (!is_dir($messageFilePath)) {
                mkdir($messageFilePath);
            }
        }
        $messageFilePath .= '/message_' .
            (new DateTime())->format('YmdHis') . mt_rand(100, 999) . '.txt';

        return $messageFilePath;
    }

    /**
     * Создает объект сообщения
     * @param bool $appendSuffix Включать ли подпись
     * @return Swift_Message
     */
    protected function createMessage($appendSuffix): Swift_Message
    {
        // хак на случай, если отправка почты производится из консольного приложения
        if (isset(Yii::app()->controller)) {
            $controller = Yii::app()->controller;
        } else {
            $controller = new CController('GTMail');
        }

        $header = $this->loadHeader($controller);
        $this->message = $header . $this->message;

        // если задано добавлять подпись к письму, подгружаем ее из внешнего файла
        if ($appendSuffix == true) {
            $this->message .= $this->loadPreFooter($controller);
        }

        $this->message .= $this->loadFooter($controller);

        return (new Swift_Message($this->subject))
            ->setFrom([Yii::app()->params['leadsEmail'] => '100 Юристов'])
            ->setTo([$this->email])
            ->setBody($this->message, 'text/html');
    }

    /**
     * Загружает из файла шапку письма
     * @param CController $controller
     * @return false|string
     */
    protected function loadHeader(CController $controller)
    {
        return $controller->renderInternal(Yii::getPathOfAlias('application.extensions.GTMail.templates.default') . '/header.php', NULL, true);
    }

    /**
     * Загружает подпись письма
     * @param CController $controller
     * @return false|string
     */
    protected function loadPreFooter(CController $controller)
    {
        return $controller->renderInternal(Yii::getPathOfAlias('application.extensions.GTMail.templates.default') . '/pre_footer.php', ['mailer' => $this], true);
    }

    /**
     * @param CController $controller
     * @return false|string
     */
    protected function loadFooter(CController $controller)
    {
        return $controller->renderInternal(Yii::getPathOfAlias('application.extensions.GTMail.templates.default') . '/footer.php', NULL, true);
    }

    /**
     * @param string $transportType
     * @return Swift_Transport_SmtpAgent
     * @throws Exception
     */
    protected function createMailTransport($transportType): Swift_Transport_SmtpAgent
    {
        switch ($transportType) {
            case self::TRANSPORT_TYPE_SMTP:
                $transport = (new Swift_SmtpTransport(Yii::app()->params['smtpServer'], Yii::app()->params['smtpPort'], 'ssl'))
                    ->setUsername(Yii::app()->params['smtpLogin'])
                    ->setPassword(Yii::app()->params['smtpPassword']);
                break;
            case self::TRANSPORT_TYPE_SENDMAIL:
                $transport = new Swift_SendmailTransport();
                break;
            default:
                throw new Exception('Unknown mail transport type');
        }

        return $transport;
    }
}
