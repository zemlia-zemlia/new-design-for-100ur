<?php

namespace App\helpers;

class IpHelper
{
    /**
     * пребразует дату в формате yyyy-mm-dd в формат dd-mm-yyyy и наоборот в зависимости от формата аргумента.
     *
     * @return string
     */
    public static function getUserIP(): string
    {
        $ip = '';
        if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}
