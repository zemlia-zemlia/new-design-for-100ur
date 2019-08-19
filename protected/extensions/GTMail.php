<?php

class GTMail
{

    public $headers = "MIME-Version: 1.0\nContent-type: text/html; charset=utf-8\nFrom:";
    public $subject;
    public $message;
    public $email;
    protected $mailer; // объект сторонней библиотеки для отправки почты
    /** @var Swift_Message */
    protected $mailerMessage; // объект сообщения сторонней библиотеки
    protected $testMode = false;

    /**
     * Конструктор
     */

    public function __construct()
    {
        $this->testMode = (YII_DEV === true) ? true : false;

        /*
         * Для совместимости со старым кодом, указываем в параметре конструктора,
         * использовать ли стороннюю библиотеку (Swiftmailer). Если не используем,
         * письма будут отправляться встроенной функцией mail()
         * При этом на сервере есть проблема с отправкой писем на наши адреса (@100yuristov.com)  
         */
        $transport = (new Swift_SmtpTransport(Yii::app()->params['smtpServer'], Yii::app()->params['smtpPort'], 'ssl'))
            ->setUsername(Yii::app()->params['smtpLogin'])
            ->setPassword(Yii::app()->params['smtpPassword']);

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


    public function sendMail($appendSuffix = true): bool
    {
        $mailerMessage = $this->createMessage($appendSuffix);


        if ($this->testMode == false) {
            return ($this->mailer->send($mailerMessage) > 0) ? true : false;
        }
        return $this->saveMessage($mailerMessage, $this->testMode);
    }

    // вставляет во все ссылки в сообщении utm метки
    static public function insertTags($text, $tags = array())
    {

        $tagsString = "";
        $tagsString .= "&utm_medium=" . urlencode($tags['utm_medium']);

        $tagsString .= "&utm_source=" . urlencode($tags['utm_source']);

        $tagsString .= "&utm_campaign=" . urlencode($tags['utm_campaign']);

        $tagsString .= "&utm_term=" . urlencode($tags['utm_term']);

        $tagsString .= "&utm_content=" . urlencode($tags['utm_content']);

        $text = preg_replace("#href=(['|\"]{1})([^?'\"]*)[?]{0,1}([^'\"]*)#", 'href=$1$2?$3' . $tagsString, $text);
        return $text;
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

    public function getTestMessagesFolder()
    {
        return Yii::getPathOfAlias('application.runtime.mail');
    }

    /**
     * @param bool $testing
     * @return bool|mixed|string
     * @throws Exception
     */
    protected function getTestMessageFilePath($testing = false):string
    {
        $messageFilePath = $this->getTestMessagesFolder();
        if($testing == true) {
            $messageFilePath .= '/test';
            if(!is_dir($messageFilePath)) {
                mkdir($messageFilePath);
            }
        }
        $messageFilePath .= '/message_' .
            (new DateTime())->format('YmdHis') . mt_rand(100,999). '.txt';

        return $messageFilePath;
    }

    /**
     * @param $appendSuffix
     * @return Swift_Message
     */
    public function createMessage($appendSuffix): Swift_Message
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

    public function loadHeader(CController $controller)
    {
        return $controller->renderInternal(Yii::getPathOfAlias('application.extensions.GTMail.templates.default') . '/header.php', NULL, true);
    }

    public function loadPreFooter(CController $controller)
    {
        return $controller->renderInternal(Yii::getPathOfAlias('application.extensions.GTMail.templates.default') . '/pre_footer.php', ['mailer' => $this], true);
    }

    public function loadFooter(CController $controller)
    {
        return $controller->renderInternal(Yii::getPathOfAlias('application.extensions.GTMail.templates.default') . '/footer.php', NULL, true);
    }
}
