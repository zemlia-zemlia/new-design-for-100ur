<?php

return CMap::mergeArray(
    require(dirname(__FILE__) . '/main.php'),
    [
        'components' => [
            'fixture' => [
                'class' => 'system.test.CDbFixtureManager',
            ],
            'request' => [
                'enableCsrfValidation' => false,
                'enableCookieValidation' => false,
            ],
            'db' => [
                'connectionString' => 'mysql:host=' . getenv('DB_TEST_HOST') . ';dbname=' . getenv('DB_TEST_NAME'),
                'username' => getenv('DB_TEST_USER'),
                'password' => getenv('DB_TEST_PASSWORD'),
            ],
            'log' => [
                'class' => 'CLogRouter',
                'routes' => [
                    [
                        'class' => 'CFileLogRoute',
                        'levels' => 'error, warning',
                    ],
                ],
            ],
            'cache' => null,
            'urlManager' => [
                'urlFormat' => 'path',
                'showScriptName' => true,
            ],
        ],
    ]
);
