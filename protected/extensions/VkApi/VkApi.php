<?php
/**
 * Класс для работы с Api Вконтакте
 *
 * @author Михаил Крутиков, студия Большая Рыба
 * michael@bigfishstudio.ru
 * www.bigfishstudio.ru
 *
 */

class VkApi
{
    public static function getAccessToken($code, $redirectUri)
    {
        $vkUrl = "https://oauth.vk.com/access_token?".
                    "client_id=" . Yii::app()->params['vkApiId'] .
                    "&client_secret=" . Yii::app()->params['vkSecret'] .
                    "&code=" . $code .
                    "&redirect_uri="."http://" . $_SERVER['SERVER_NAME'] . $redirectUri . "&";
        // обращаемся через CURL к серверу ВКонтакте за токеном
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $vkUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $vkResponse = curl_exec($ch);
        curl_close($ch);
        return $vkResponse;
    }
    
    public static function request($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $vkResponse = curl_exec($ch);
        curl_close($ch);
        return $vkResponse;
    }
}
