<?php

// параметры доступа к БД
if (YII_DEV == true) {
    return [
        'connectionString' => 'mysql:host=localhost;dbname=100yuristov_etalon',
        'emulatePrepare' => true,
        'username' => '100yuristov',
        'password' => 'crm_local',
        'charset' => 'utf8',
        'tablePrefix' => '100_',
        'enableProfiling' => true,
        'schemaCachingDuration' => 3000,
        'queryCacheID' => 'cache',
    ];
} else {
    // Настройки для продакшена..
}
