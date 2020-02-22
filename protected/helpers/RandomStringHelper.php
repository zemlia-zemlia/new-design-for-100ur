<?php

class RandomStringHelper
{

    public static function generateRandomString($legth = 10)
    {
        $charaters = '01234567890abcdefghijklmnopqrstuvwxyz';
        $randomString = '';
        for ($i = 0; $i < $legth; ++$i) {
            $randomString .= $charaters[rand(0, strlen($charaters) - 1)];
        }

        return $randomString;
    }
}