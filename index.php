<?php
// необходимо для запуска тестов API
// change the following paths if necessary
error_reporting(E_ERROR);
$composer = dirname(__FILE__) . '/protected/vendor/autoload.php';
require_once($composer);
$settings = dirname(__FILE__) . '/protected/config/settings.php';
require_once($settings);

$config = YII_ENV == 'test' ?
    dirname(__FILE__) . '/protected/config/test.php' :
    dirname(__FILE__) . '/protected/config/main.php';


Yii::createWebApplication($config)->run();
