<?php

class SendMail
{
    public $headers = "MIME-Version: 1.0\n
        Content-type: text/html; charset=utf-8\n
        From: Турфирма Пилигрим<info@pgrim.ru>\n\n";
    public $subject;
    public $message;
    public $email;
    
    public function sendMessage()
    {
        if(mail($this->email, $this->subject, $this->message, $this->headers)) return true;
            else return false;
    }
}
?>
