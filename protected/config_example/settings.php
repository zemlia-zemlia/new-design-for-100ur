<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

defined('YII_DEBUG') or define('YII_DEBUG', getenv('YII_DEBUG'));
// режим разработки
defined('YII_DEV') or define('YII_DEV', getenv('YII_DEV'));
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', getenv('YII_TRACE_LEVEL'));
// dev / test / prod
defined('YII_ENV') or define('YII_ENV', getenv('ENV'));
