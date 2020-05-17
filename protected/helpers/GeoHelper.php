<?php

namespace App\helpers;

use App\models\Town;
use CHtml;
use PhoneHelper;
use Yii;

class GeoHelper
{
    /**
     * @return string|null
     */
    public static function detectTownLink(?string $ip = null, string $selector)
    {
        $town = self::detectTown($ip);
        if ($town) {
            $link = CHtml::link($town . '?', '', ['onclick' => "$('" . $selector . "').val('" . $town . "')", 'class' => 'suggest-link']);

            return $link;
        }

        return null;
    }

    /**
     * Определение города пользователя по IP адресу.
     *
     * @return Town город или NULL
     */
    public static function detectTown(?string $ip = null): ?Town
    {
        $town = null;

        if (!Yii::app()->user->getState('currentTownId')) {
            // если принудительно не задан IP, берем текущий IP адрес
            if (empty($ip)) {
                $ip = IpHelper::getUserIP();
            }

            $data = '<ipquery><fields><all/></fields><ip-list><ip>' . $ip . '</ip></ip-list></ipquery>';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://194.85.91.253:8090/geo/geo.html');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $xml = curl_exec($ch);
            curl_close($ch);
            $xml = iconv('windows-1251', 'utf-8', $xml);
            preg_match("/<city>(.*?)<\/city>/", $xml, $a);
            $townName = isset($a[1]) ? $a[1] : '';

            $currentTown = null;
            if ($townName) {
                $currentTown = Town::model()->findByAttributes(['name' => $townName]);
            }

            return $currentTown;
        }

        return $town;
    }

    /**
     * Возвращает id города по номеру телефона.
     *
     * @param string $phoneNumber Номер телефона
     *
     * @return int ID города в базе. null, если город в базе не найден
     */
    public static function detectTownIdByPhone($phoneNumber): ?int
    {
        // приводим номер телефона к виду 7xxxxxxxxxx
        $phoneNumber = PhoneHelper::normalizePhone($phoneNumber);
        $htmlwebApiResponse = file_get_contents('http://htmlweb.ru/geo/api.php?json&telcod=' . $phoneNumber . '&charset=utf-8&api_key=' . Yii::app()->params['htmlwebApiKey']);

        // расшифровываем JSON-ответ от сервера в ассоциативный массив
        $htmlwebApiResponseArray = json_decode($htmlwebApiResponse, true);

        if (!isset($htmlwebApiResponseArray[0]['name'])) {
            return null;
        }

        $townName = $htmlwebApiResponseArray[0]['name'];
        Yii::log('Получение города по номеру телефона ' . $phoneNumber . '. Город: ' . $townName);

        $town = Town::model()->find('name="' . CHtml::encode($townName) . '"');

        if ($town) {
            return $town->id;
        }

        return null;
    }
}
