<?php

class LoggerFactory
{
    public static function getLogger($type = 'db')
    {
        switch ($type) {
            case 'db': default:
                return new DbLogger(Yii::app()->db, Yii::app()->params['logTable']);
                break;
        }
    }
}
