<?php

// change the following paths if necessary
$composer = dirname(__FILE__) . '/protected/vendor/autoload.php';
$yii=dirname(__FILE__).'/protected/vendor/yiisoft/yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';
// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// режим разработки
defined('YII_DEV') or define('YII_DEV', true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
require_once($composer);
require_once($yii);
Yii::createWebApplication($config)->run();
