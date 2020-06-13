<?php
/**
 * Интерфейс определяет город по айпи
 */

namespace App\components\detectCityByIp;


interface GetNameByIpInterface
{
    public function getCityName($ip);

}