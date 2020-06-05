<?php
/**
 * Создает класс определения города по айпи в зависимости от переменной окружения.
 */

namespace App\components\detectCityByIp;


class TownByIpStrategyResolver
{
    public function createClass()
    {
        if (\Yii::app()->params['townByIpService'] == 'ApiDadata') {

            return new ApiDadata();
        }

        if (\Yii::app()->params['townByIpService'] == 'Api8090') {

            return new Api8090();
        }
    }

}