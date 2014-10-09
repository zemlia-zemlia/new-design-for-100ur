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
 	
        $fromHeader = "=?utf-8?b?" . base64_encode("lidlaw@mail.ru") . "?=<lidlaw@mail.ru>";
	$this->headers .= $fromHeader . "\n\n";
        
        $this->subject = "=?utf-8?b?" . base64_encode($this->subject) . "?="; //так по-правильному нужно кодировать тему письма

        if(mail($this->email, $this->subject, $this->message, $this->headers)) return true;
            else return false;
    }
}
?>
