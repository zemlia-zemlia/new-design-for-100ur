<?php

$composer = dirname(__FILE__) . '/vendor/autoload.php';
// change the following paths if necessary
$yiic = dirname(__FILE__) . '/vendor/yiisoft/yii/framework/yiic.php';
$config = dirname(__FILE__) . '/config/console.php';
$settings = dirname(__FILE__) . '/config/settings.php';

require_once $composer;
require_once $settings;
require_once $yiic;
