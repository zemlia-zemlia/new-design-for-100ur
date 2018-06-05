<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => '100 юристов',
    'defaultController' => 'site',
    'theme' => '2017',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.components.emailParsers.*',
        'application.extensions.*',
        'application.extensions.XWebDebugRouter.*',
        'application.extensions.CustomFuncs.*',
        'application.extensions.cleditor.ECLEditor',
        'application.extensions.StoYuristovClient.StoYuristovClient',
        'application.extensions.imperavi-redactor-widget.ImperaviRedactorWidget',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool

        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => '159357',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
        'admin',
        'webmaster' => array(
            'defaultController' => 'default',
        ),
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'class' => 'WebUser',
        ),
        'authManager' => array(
            // Будем использовать свой менеджер авторизации
            'class' => 'PhpAuthManager',
            // Роль по умолчанию. Все, кто не админы, модераторы и юзеры — гости.
            'defaultRoles' => array('guest'),
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'urlSuffix' => '/',
            'baseUrl' => 'http://100juristov',
            'rules' => array(
                '/q' => '/question/index',
                '/q/<id:\d+>' => '/question/view',
                '/q/<date:[\w\-]+>' => '/question/archive',
                '/cat' => '/questionCategory/index',
                array(
                    'class' => 'application.components.QuestionCategoryRule',
                ),
                '/ord/<id:\d+>' => '/order/view',
                '/town/<id:\d+>' => '/town/view',
                '/blog' => '/blog/index',
                '/blog/<id:\d+>-<alias:[\w\-]+>' => '/post/view',
                '/post/<id:\d+>'                =>  '/post/view',
                '/company/<alias:[\w\-]+>' => '/yurCompany/town',
                '/company' => '/yurCompany/index',
                '/firm/<id:\d+>' => '/yurCompany/view',
                '/user/<id:\d+>' => '/user/view',
                '/konsultaciya-yurista-<name:[\w\-]+>' => '/town/aliasOld',
                '/yurist/<countryAlias:[\w\-]+>' => '/region/country',
                '/yurist/<countryAlias:[\w\-]+>/<regionAlias:[\w\-]+>' => '/region/view',
                '/yurist/<countryAlias:[\w\-]+>/<regionAlias:[\w\-]+>/<name:[\w\-]+>' => '/town/alias',
                '/codecs/<codecsAlias:[\w\-\.]+>' => '/codecs/view',
                '/codecs/<codecsAlias:[\w\-\.]+>/<partAlias:[\w\-\.]+>' => '/codecs/view',
                '/codecs/<codecsAlias:[\w\-\.]+>/<partAlias:[\w\-\.]+>/<glavaAlias:[\w\-\.]+>' => '/codecs/view',
                '/codecs/<codecsAlias:[\w\-\.]+>/<partAlias:[\w\-\.]+>/<glavaAlias:[\w\-\.]+>/<articleAlias:[\w\-\.]+>' => '/codecs/view',
            ),
        ),
        'clientScript' => array(
            'scriptMap' => array(
                'jquery.js' => '/js/jquery-1.11.1.min.js',
                'jquery.min.js' => '/js/jquery-1.11.1.min.js'
            ),
        ),
        'ih' => array(
            'class' => 'CImageHandler',
        ),
        // uncomment the following to use a MySQL database
        'db' => require(dirname(__FILE__) . '/db.php'),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, info',
                ),
                // uncomment the following to show log messages on web pages

                /* array(
                  'class'         =>  'CWebLogRoute',
                  'showInFireBug' =>  false,
                  'categories'    =>  'application',
                  ), */
                array(
                    'class' => 'CProfileLogRoute',
                ),
            ),
        ),
        'cache' => array(
            'class' => 'system.caching.CFileCache',
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => require(dirname(__FILE__) . '/params.php'),
);
