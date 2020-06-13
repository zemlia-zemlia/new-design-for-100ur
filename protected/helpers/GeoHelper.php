<?php

namespace App\helpers;

use App\models\Town;
use CHtml;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use PhoneHelper;
use Yii;
use App\components\detectCityByIp\TownByIpStrategyResolver;

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

            $apiResolver = new TownByIpStrategyResolver();
            $apiService = $apiResolver->createClass();
            $townName = $apiService->getCityName($ip);


            $ipServiceLogger = Yii::app()->monolog->getNewLogger('town_by_ip', [new RotatingFileHandler(Yii::getPathOfAlias('webroot') .
                '/protected/runtime/town_by_ip/request.txt', Logger::DEBUG)]);
            $ipServiceLogger->addDebug('success', ['ip' => $ip, 'town' => $townName]);

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
