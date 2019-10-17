<?php

// change the following paths if necessary
$composer = dirname(__FILE__) . '/protected/vendor/autoload.php';
$settings = dirname(__FILE__) . '/protected/config/settings.php';
require_once($settings);

$config = YII_ENV == 'test' ?
    dirname(__FILE__) . '/protected/config/test.php' :
    dirname(__FILE__) . '/protected/config/main.php';

require_once($composer);
Yii::createWebApplication($config)->run();
