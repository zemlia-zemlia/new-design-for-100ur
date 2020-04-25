<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return [
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'My Console Application',

    // preloading 'log' component
    'preload' => ['log'],

        // autoloading model and component classes
    'import' => require(dirname(__FILE__) . '/autoload.php'),

    // application components
    'components' => [
        'db' => require(dirname(__FILE__) . '/db.php'),

                'urlManager' => [
                    'urlFormat' => 'path',
                    'showScriptName' => false,
                    'urlSuffix' => '/',
                    'baseUrl' => getenv('BASE_URL'),
                    'rules' => [
                        '/q' => '/question/index',
                        '/q/<id:\d+>' => '/question/view',
                        '/q/<date:[\w\-]+>' => '/question/archive',
                        '/cat' => '/questionCategory/index',
                        [
                            'class' => 'application.components.QuestionCategoryRule',
                        ],
                        '/ord/<id:\d+>' => '/order/view',
                        '/town/<id:\d+>' => '/town/view',
                        '/blog/<id:\d+>-<alias:[\w\-]+>' => '/post/view',
                        '/post/<id:\d+>' => '/post/view',
                        '/yurist/<countryAlias:[\w\-]+>' => '/region/country',
                        '/yurist/<countryAlias:[\w\-]+>/<regionAlias:[\w\-]+>' => '/region/view',
                        '/yurist/<countryAlias:[\w\-]+>/<regionAlias:[\w\-]+>/<name:[\w\-]+>' => '/town/alias',
                        ],
                    ],

                'ih' => [
                        'class' => 'CImageHandler',
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

        'mailer' => [
            'class' => 'application.extensions.GTMail',
        ],
    ],
    'params' => require(dirname(__FILE__) . '/params.php'),
];
