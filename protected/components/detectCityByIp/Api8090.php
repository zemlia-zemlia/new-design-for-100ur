<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 05.06.2020
 * Time: 16:09
 */

namespace App\components\detectCityByIp;


class Api8090 implements GetNameByIpInterface
{
    public function getCityName($ip)
    {
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

            return $townName;
    }


}


