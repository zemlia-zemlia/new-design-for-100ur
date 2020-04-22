<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
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
            'enableCsrfValidation' => true,
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
            'baseUrl' => 'http://100yuristov.local',
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
                '/codecs/<codecsAlias:[\w\-\.]+>' => '/codecs/view',
                '/codecs/<codecsAlias:[\w\-\.]+>/<partAlias:[\w\-\.]+>' => '/codecs/view',
                '/codecs/<codecsAlias:[\w\-\.]+>/<partAlias:[\w\-\.]+>/<glavaAlias:[\w\-\.]+>' => '/codecs/view',
                '/codecs/<codecsAlias:[\w\-\.]+>/<partAlias:[\w\-\.]+>/<glavaAlias:[\w\-\.]+>/<articleAlias:[\w\-\.]+>' => '/codecs/view',
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
//            'errorAction' => 'site/error',
        ],
        'log' => [
            'class' => 'CLogRouter',
            'routes' => [
                [
                    'class' => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                    'ipFilters' => array('127.0.0.1'),
//                    'class' => 'CFileLogRoute',
//                    'levels' => 'error, warning, info',
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
    ],
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => require(dirname(__FILE__) . '/params.php'),
];
