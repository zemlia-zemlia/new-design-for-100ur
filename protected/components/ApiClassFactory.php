<?php

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
