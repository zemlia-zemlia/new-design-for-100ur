<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 05.06.2020
 * Time: 15:24
 */

namespace App\components\detectCityByIp;


class ApiDadata implements GetNameByIpInterface
{
    public function getCityName($ip)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/iplocate/address?ip=46.226.227.20');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: Token ' . getenv('API_KEY_FOR_DADATA')
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);

        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);

        return $response->location->data->city;
    }

}