<?php

class GTMail
{
    public $headers  = "MIME-Version: 1.0\nContent-type: text/html; charset=utf-8\nFrom:";
    public $subject;
    public $message;
    public $email;
    
    public function sendMail($appendSuffix=true)
    {
 	// хак на случай, если отправка почты производится из консольного приложения
        if(isset(Yii::app()->controller)) {
            $controller = Yii::app()->controller;
        } else {
            $controller = new CController('GTMail');
        }
        
        $fromHeader = "=?utf-8?b?" . base64_encode(Yii::app()->params['leadsEmail']) . "?=<". Yii::app()->params['leadsEmail'] . ">";
	$this->headers .= $fromHeader . "\n";
        
        $this->subject = "=?utf-8?b?" . base64_encode($this->subject) . "?="; //так по-правильному нужно кодировать тему письма

        $header = $controller->renderInternal(Yii::getPathOfAlias('application.extensions.GTMail.templates.default').'/header.php', NULL, true);
        
        $this->message = $header . $this->message;
        
        // если задано добавлять подпись к письму, подгружаем ее из внешнего файла
        if($appendSuffix==true) {
            $this->message .= $controller->renderInternal(Yii::getPathOfAlias('application.extensions.GTMail.templates.default').'/pre_footer.php', NULL, true);
        }
                        
        $footer = $controller->renderInternal(Yii::getPathOfAlias('application.extensions.GTMail.templates.default').'/footer.php', NULL, true);

        $this->message .= $footer;
        
        if(mail($this->email, $this->subject, $this->message, $this->headers)) return true;
            else return false;
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
        
        $text = preg_replace("#href=(['|\"]{1})([^?'\"]*)[?]{0,1}([^'\"]*)#", 'href=$1$2?$3'.$tagsString, $text);
        return $text;
    }
}
?>
