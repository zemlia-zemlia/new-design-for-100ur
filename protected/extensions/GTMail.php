<?php

class GTMail
{
    public $headers  = "MIME-Version: 1.0\nContent-type: text/html; charset=utf-8\nFrom:";
    public $subject;
    public $message;
    public $email;
    
    public function sendMail($appendSuffix=true)
    {
        if($appendSuffix==true)
        $this->message .="<br /><br /><i>Это письмо написал робот. Пожалуйста, не отвечайте на него.</i><br />";
 	
        $fromHeader = "=?utf-8?b?" . base64_encode("CRM юридической фирмы") . "?=<info@kc-zakon.ru>";
	$this->headers .= $fromHeader . "\n\n";
        
        $this->subject = "=?utf-8?b?" . base64_encode($this->subject) . "?="; //так по-правильному нужно кодировать тему письма

        if(mail($this->email, $this->subject, $this->message, $this->headers)) return true;
            else return false;
    }
}
?>
