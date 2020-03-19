<?php

namespace App\components;
use App\components\apiClasses\Api8088;
use App\components\apiClasses\ApiLeadia;
use App\components\apiClasses\ApiLexProfit;
use App\components\apiClasses\ApiPravoved;
use App\components\apiClasses\ApiSovinform;
use App\components\apiClasses\ApiTestHandler;

/**
 * Фабрика для создания классов работы с API партнерских программ
 */
class ApiClassFactory
{
    public function getApiClass($className)
    {
        if (YII_ENV != 'prod') {
            return new ApiTestHandler();
        }
        switch ($className) {
            case 'ApiLexprofit':
                return new ApiLexProfit();
            case 'ApiLeadia':
                return new ApiLeadia();
            case 'Api8088':
                return new Api8088();
            case 'ApiPravoved':
                return new ApiPravoved();
            case 'ApiSovinform':
                return new ApiSovinform();
        }
    }
}
