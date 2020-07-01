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
     * Определение города пользователя по IP адресу.
     *
     * @param string|null $ip
     * @param string|null $userAgent
     * @param DetectBotHelper $botDetector
     * @return Town город или NULL
     * @throws \Exception
     */
    public static function detectTown(?string $ip = null, ?string $userAgent = null, DetectBotHelper $botDetector): ?Town
    {
        $town = null;

        if ($userAgent && $botDetector->detectBot($userAgent)) {
            return null;
        }

        if (!Yii::app()->user->getState('currentTownId')) {
            // если принудительно не задан IP, берем текущий IP адрес
            if (empty($ip)) {
                $ip = IpHelper::getUserIP();
            }

            $ipCacheKey = 'unknown_town_ip_' . $ip;
            // в кеше сохраняем ip адреса, для которых не удается определить город, чтобы повторно не дергать внешний сервис
            if (Yii::app()->cache->get($ipCacheKey) !== false) {
                $townName = null;
            } else {
                $apiResolver = new TownByIpStrategyResolver();
                $apiService = $apiResolver->createClass();
                $townName = $apiService->getCityName($ip);

                if ($townName == null) {
                    Yii::app()->cache->set($ipCacheKey, 1, 600);
                }

                $userAgent = Yii::app()->request->getUserAgent();
                $ipServiceLogger = Yii::app()->monolog->getNewLogger('town_by_ip', [new RotatingFileHandler(Yii::getPathOfAlias('webroot') .
                    '/protected/runtime/town_by_ip/request.txt', Logger::DEBUG)]);
                $ipServiceLogger->addDebug('success', [
                    'ip' => $ip,
                    'town' => $townName,
                    'userAgent' => $userAgent,
                ]);
            }

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
