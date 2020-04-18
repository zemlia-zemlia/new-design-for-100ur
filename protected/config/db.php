<?php

// параметры доступа к БД
if (YII_DEV == true) {
    return [
        'connectionString' => 'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME'),
        'emulatePrepare' => true,
        'username' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
        'charset' => 'utf8',
        'tablePrefix' => '100_',
        'enableProfiling' => true,
        'schemaCachingDuration' => 3000,
        'queryCacheID' => 'cache',
    ];
} else {
    // Настройки для продакшена..
}
