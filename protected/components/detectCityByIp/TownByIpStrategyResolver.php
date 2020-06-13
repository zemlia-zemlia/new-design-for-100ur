<?php
/**
 * Создает класс определения города по айпи в зависимости от переменной окружения.
 */

namespace App\components\detectCityByIp;


class TownByIpStrategyResolver
{
    public function createClass() : GetNameByIpInterface
    {
        if (\Yii::app()->params['townByIpService'] == 'ApiDadata') {

            return new ApiDadata();
        }

        if (\Yii::app()->params['townByIpService'] == 'Api8090') {

            return new Api8090();
        }

        throw new \Exception(404, 'Не задан сервис определения города по IP в .env');
    }
}
