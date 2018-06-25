<?php

/**
 * Фабрика для создания классов работы с API партнерских программ
 */
class ApiClassFactory
{
    public static function getApiClass($className)
    {
        switch ($className) {
            case 'ApiLexprofit':
                return new ApiLexProfit();
            case 'ApiLeadia':
                return new ApiLeadia();    
        }
    }
}