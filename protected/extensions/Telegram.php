<?php

/**
 * Class Telegram
 * Позволяет отправлять сообщения от имени бота в телеграм
 * @package API\Custom\Telegram
 */
class Telegram
{
    protected static $_token        = "365714143:AAGQIe1TCEkJ38HaACtm34sbQihcRNTm-7g";

    protected static $_chat_id      = "StoYuristov";

    protected static function _curl($url, $method)
    {
        $domain = "https://api.telegram.org/bot" . static::$_token;

        $request = $domain . $url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data  = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * Послать сообщение одному чату
     * @param $chat_id
     * @param $text
     * @return mixed
     */
    public static function send($chat_id, $text)
    {
        $url = '/sendMessage';
        $params = array(
            'disable_web_page_preview'  => true,
            'chat_id'                   => $chat_id,
            'text'                      => $text,
        );
        return static::_curl($url . '?' . http_build_query($params), 'GET');
    }

    /**
     * Оповестить всех кто добавлен в список чатов
     * @param $text
     * @return bool
     */
    public static function alert($text)
    {
        foreach (static::$_chat_id as $chat)
            static::send($chat, $text);
        return true;
    }

    public static function getUpdates()
    {
        $url = '/getUpdates';
        return static::_curl($url, 'GET');
    }
}