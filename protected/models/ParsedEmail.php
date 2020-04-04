<?php

namespace App\models;

/**
 * Класс для работы со спарсенными письмами.
 */
class ParsedEmail
{
    protected $subject;
    protected $body;

    public function __construct($body, $subject)
    {
        $this->body = $body;
        $this->subject = $subject;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }
}
