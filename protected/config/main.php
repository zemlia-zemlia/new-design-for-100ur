<?php

return [
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => '100 юристов',
    'defaultController' => 'site',
    'theme' => '2020',
    // preloading 'log' component
    'preload' => ['log'],
    // autoloading model and component classes
    'import' => require(dirname(__FILE__) . '/autoload.php'),
    'modules' => [
        // uncomment the following to enable the Gii tool

        'gii' => [
            'class' => 'system.gii.GiiModule',
            'password' => '159357',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => ['127.0.0.1', '::1'],
        ],
        'admin',
        'buyer' => ['defaultController' => 'buyer'],
        'webmaster' => [
            'defaultController' => 'default',
        ],
    ],
    // application components
    'components' => [
        'user' => [
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'class' => 'WebUser',
        ],
        'request' => [
            'enableCsrfValidation' => false,
            'enableCookieValidation' => true,
        ],
        'authManager' => [
            // Будем использовать свой менеджер авторизации
            'class' => 'PhpAuthManager',
            // Роль по умолчанию. Все, кто не админы, модераторы и юзеры — гости.
            'defaultRoles' => ['guest'],
        ],
        // uncomment the following to enable URLs in path-format
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
                '/feedback' => '/site/feedback',
                [
                    'class' => 'application.components.QuestionCategoryRule',
                ],
                '/ord/<id:\d+>' => '/order/view',
                '/town/<id:\d+>' => '/town/view',
                '/blog' => '/blog/index',
                '/blog/<id:\d+>-<alias:[\w\-]+>' => '/post/view',
                '/post/<id:\d+>' => '/post/view',
                '/user/<id:\d+>' => '/user/view',
                '/konsultaciya-yurista-<name:[\w\-]+>' => '/town/aliasOld',
                '/yurist/<countryAlias:[\w\-]+>' => '/region/country',
                '/yurist/<countryAlias:[\w\-]+>/<regionAlias:[\w\-]+>' => '/region/view',
                '/yurist/<countryAlias:[\w\-]+>/<regionAlias:[\w\-]+>/<name:[\w\-]+>' => '/town/alias',
                '/region/<countryAlias:[\w\-]+>' => '/region/redirect',
                '/region/<countryAlias:[\w\-]+>/<regionAlias:[\w\-]+>' => '/region/redirect',
                '/region/<countryAlias:[\w\-]+>/<regionAlias:[\w\-]+>/<name:[\w\-]+>' => '/region/redirect',
            ],
        ],
        'clientScript' => [
            'scriptMap' => [
                'jquery.js' => '/js/jquery-1.11.1.min.js',
                'jquery.min.js' => '/js/jquery-1.11.1.min.js',
            ],
        ],
        'ih' => [
            'class' => 'CImageHandler',
        ],
        // uncomment the following to use a MySQL database
        'db' => require(dirname(__FILE__) . '/db.php'),
        'errorHandler' => [
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ],
        'log' => [
            'class' => 'CLogRouter',
            'routes' => [
                [
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, info',
                ],
                // uncomment the following to show log messages on web pages

                /* array(
                  'class'         =>  'CWebLogRoute',
                  'showInFireBug' =>  false,
                  'categories'    =>  'application',
                  ), */
//                [
//                    'class' => 'CProfileLogRoute',
//                ],
            ],
        ],
        'cache' => [
            'class' => 'system.caching.CFileCache',
        ],
        'mailer' => [
            'class' => 'application.extensions.GTMail',
        ],
        'container' => [
            'class' => 'application.components.DiContainer',
        ],
    ],
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => require(dirname(__FILE__) . '/params.php'),
];
