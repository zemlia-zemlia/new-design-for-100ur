<?php

/**
 * Настройки логирования
 */

$logRoutes = [
    [
        // Стандартный логгер Yii, пишет в текстовый файл runtime/application.log
        'class' => 'CFileLogRoute',
        'levels' => 'error, warning, info',
    ],
    [
        // Monolog, по умолчанию пишет в файл runtime/monolog/*
        // Включение-выключение - в компоненте MonologComponent
        'class' => 'application.components.MonologLoggerRoute',
        'levels' => 'error, warning, info',
    ],
];

if (getenv('WEB_LOG_ENABLED')) {
    // вывод отладочной информации на страницу
    $logRoutes += [
        'class' => 'CWebLogRoute',
        'showInFireBug' => false,
        'categories' => 'application',
    ];
}

if (getenv('PROFILE_LOG_ENABLED')) {
    // вывод профилирования запросов на странице
    $logRoutes += [
        'class' => 'CProfileLogRoute',
    ];
}


return [
    'class' => 'CLogRouter',
    'routes' => $logRoutes,
];
