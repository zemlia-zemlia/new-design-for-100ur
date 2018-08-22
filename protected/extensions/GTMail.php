<?php

class GTMail {

    public $headers = "MIME-Version: 1.0\nContent-type: text/html; charset=utf-8\nFrom:";
    public $subject;
    public $message;
    public $email;
    protected $mailer; // объект сторонней библиотеки для отправки почты
    protected $mailerMessage; // объект сообщения сторонней библиотеки

    /**
     * Конструктор
     * @param boolean $useLibrary Использовать ли стороннюю библиотеку
     */

    public function __construct($useLibrary = false) {
        
        // при отправке писем с локальной машины при разработке не используем SMTP сервер
        if(YII_DEV === true || USE_SMTP == false) {
            $useLibrary = false;
        }
        /*
         * Для совместимости со старым кодом, указываем в параметре конструктора,
         * использовать ли стороннюю библиотеку (Swiftmailer). Если не используем,
         * письма будут отправляться встроенной функцией mail()
         * При этом на сервере есть проблема с отправкой писем на наши адреса (@100yuristov.com)  
         */
        if ($useLibrary === true) {
            // Create the Transport
            $transport = (new Swift_SmtpTransport(Yii::app()->params['smtpServer'], Yii::app()->params['smtpPort'], 'ssl'))
                    ->setUsername(Yii::app()->params['smtpLogin'])
                    ->setPassword(Yii::app()->params['smtpPassword']);

            // Create the Mailer using your created Transport
            $this->mailer = new Swift_Mailer($transport);
        }
    }

    public function sendMail($appendSuffix = true) {

        // хак на случай, если отправка почты производится из консольного приложения
        if (isset(Yii::app()->controller)) {
            $controller = Yii::app()->controller;
        } else {
            $controller = new CController('GTMail');
        }

        $fromHeader = "=?utf-8?b?" . base64_encode("100 Юристов") . "?=<" . Yii::app()->params['leadsEmail'] . ">";
        $this->headers .= $fromHeader . "\r\n";

        $this->subject = "=?utf-8?b?" . base64_encode($this->subject) . "?="; //так по-правильному нужно кодировать тему письма

        $header = $controller->renderInternal(Yii::getPathOfAlias('application.extensions.GTMail.templates.default') . '/header.php', NULL, true);

        $this->message = $header . $this->message;

        // если задано добавлять подпись к письму, подгружаем ее из внешнего файла
        if ($appendSuffix == true) {
            $this->message .= $controller->renderInternal(Yii::getPathOfAlias('application.extensions.GTMail.templates.default') . '/pre_footer.php', ['mailer' => $this], true);
        }

        $footer = $controller->renderInternal(Yii::getPathOfAlias('application.extensions.GTMail.templates.default') . '/footer.php', NULL, true);

        $this->message .= $footer;

        // Если используем стороннюю библиотеку
        if (isset($this->mailer)) {
            $this->mailerMessage = (new Swift_Message($this->subject))
                    ->setFrom([Yii::app()->params['leadsEmail'] => '100 Юристов'])
                    ->setTo([$this->email])
                    ->setBody($this->message, 'text/html');
            return ($this->mailer->send($this->mailerMessage) > 0) ? true : false;
        } else {
            // Если отправляем средствами функции mail() нашего сервера
            return (mail($this->email, $this->subject, $this->message, $this->headers)) ? true : false;
        }
    }

    // вставляет во все ссылки в сообщении utm метки
    static public function insertTags($text, $tags = array()) {

        $tagsString = "";
        $tagsString .= "&utm_medium=" . urlencode($tags['utm_medium']);

        $tagsString .= "&utm_source=" . urlencode($tags['utm_source']);

        $tagsString .= "&utm_campaign=" . urlencode($tags['utm_campaign']);

        $tagsString .= "&utm_term=" . urlencode($tags['utm_term']);

        $tagsString .= "&utm_content=" . urlencode($tags['utm_content']);

        $text = preg_replace("#href=(['|\"]{1})([^?'\"]*)[?]{0,1}([^'\"]*)#", 'href=$1$2?$3' . $tagsString, $text);
        return $text;
    }

}

?>
