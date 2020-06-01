<?php

class GTMail extends CApplicationComponent
{
    const TRANSPORT_TYPE_SMTP = 'smtp';
    const TRANSPORT_TYPE_SENDMAIL = 'sendmail';
    const TEST_MESSAGES_FOLDER = 'test';

    public $subject;
    public $message;
    public $email;

    protected $mailer; // объект сторонней библиотеки для отправки почты
    protected $testMode = false;
    protected $transportType = self::TRANSPORT_TYPE_SMTP;

    /**
     * Конструктор
     *
     * @param string $transportType Тип транспорта
     *
     * @throws Exception
     */
    public function __construct($transportType = self::TRANSPORT_TYPE_SENDMAIL)
    {
        $this->testMode = YII_DEV === true;
        $this->transportType = $transportType;

        $transport = $this->createMailTransport($transportType);

        // Create the Mailer using your created Transport
        $this->mailer = new Swift_Mailer($transport);
    }

    public function isTestMode(): bool
    {
        return $this->testMode;
    }

    public function setTestMode(bool $testMode): GTMail
    {
        $this->testMode = $testMode;

        return $this;
    }

    public function getTransportType(): string
    {
        return $this->transportType;
    }

    public function setTransportType(string $transportType): GTMail
    {
        $this->transportType = $transportType;

        return $this;
    }

    /**
     * Отправка сообщения.
     *
     * @param bool  $appendSuffix      Включать ли подпись
     * @param array $additionalHeaders Дополнительные заголовки письма
     *
     * @return bool Результат отправки
     */
    public function sendMail($appendSuffix = true, $additionalHeaders = []): bool
    {
        $mailerMessage = $this->createMessage($appendSuffix);
        $mailerMessage = $this->appendHeaders($mailerMessage, $additionalHeaders);

        if (false == $this->testMode) {
            return ($this->mailer->send($mailerMessage) > 0) ? true : false;
        }

        return $this->saveMessage($mailerMessage, $this->testMode);
    }

    /**
     * Добавляет письму служебные заголовки.
     *
     * @param array $additionalHeaders
     */
    protected function appendHeaders(Swift_Message $message, $additionalHeaders = []): Swift_Message
    {
        $headers = $message->getHeaders();
        foreach ($additionalHeaders as $headerName => $headerValue) {
            $headers->addTextHeader($headerName, $headerValue);
        }

        return $message;
    }

    /**
     * Сохраняет сообщение в папку на диске.
     *
     * @param bool $testing
     */
    protected function saveMessage(Swift_Message $mailerMessage, $testing): bool
    {
        try {
            file_put_contents($this->getTestMessageFilePath($testing), $mailerMessage->getBody());

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Возвращает путь к папке для хранения писем в виде текстовых файлов.
     *
     * @return mixed
     */
    public static function getTestMessagesFolder()
    {
        return Yii::getPathOfAlias('application.runtime.mail');
    }

    /**
     * @param bool $testing
     *
     * @return bool|mixed|string
     *
     * @throws Exception
     */
    protected function getTestMessageFilePath($testing = false): string
    {
        $messageFilePath = self::getTestMessagesFolder();
        if (true == $testing) {
            $messageFilePath .= '/' . self::TEST_MESSAGES_FOLDER;
            if (!is_dir($messageFilePath)) {
                mkdir($messageFilePath);
            }
        }
        $messageFilePath .= '/message_' .
            (new DateTime())->format('YmdHis') . mt_rand(100, 999) . '.txt';

        return $messageFilePath;
    }

    /**
     * Создает объект сообщения.
     *
     * @param bool $appendSuffix Включать ли подпись
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
        if (true == $appendSuffix) {
            $this->message .= $this->loadPreFooter($controller);
        }

        $this->message .= $this->loadFooter($controller);

        return (new Swift_Message($this->subject))
            ->setFrom([Yii::app()->params['leadsEmail'] => '100 Юристов'])
            ->setTo([$this->email])
            ->setBody($this->message, 'text/html');
    }

    /**
     * Загружает из файла шапку письма.
     *
     * @return false|string
     */
    protected function loadHeader(CController $controller)
    {
        return $controller->renderInternal(Yii::getPathOfAlias('application.extensions.GTMail.templates.default') . '/header.php', null, true);
    }

    /**
     * Загружает подпись письма.
     *
     * @return false|string
     */
    protected function loadPreFooter(CController $controller)
    {
        return $controller->renderInternal(Yii::getPathOfAlias('application.extensions.GTMail.templates.default') . '/pre_footer.php', ['mailer' => $this], true);
    }

    /**
     * @return false|string
     */
    protected function loadFooter(CController $controller)
    {
        return $controller->renderInternal(Yii::getPathOfAlias('application.extensions.GTMail.templates.default') . '/footer.php', null, true);
    }

    /**
     * @param string $transportType
     *
     * @throws Exception
     */
    protected function createMailTransport($transportType): Swift_Transport
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
