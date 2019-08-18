<?php
// change the following paths if necessary
$composer = dirname(__FILE__) . '/../vendor/autoload.php';
$yiit = dirname(__FILE__).'/../vendor/yiisoft/yii/framework/yiit.php';
$config = dirname(__FILE__).'/../config/test.php';
$settings=dirname(__FILE__).'/../config/settings.php';

require_once($composer);
require_once($yiit);
require_once($settings);

Yii::createWebApplication($config);