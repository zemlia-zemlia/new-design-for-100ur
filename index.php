<?php

// change the following paths if necessary
$composer = dirname(__FILE__) . '/protected/vendor/autoload.php';
$config=dirname(__FILE__).'/protected/config/main.php';
$settings=dirname(__FILE__).'/protected/config/settings.php';

require_once($settings);
require_once($composer);
Yii::createWebApplication($config)->run();
