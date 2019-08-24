<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'My Console Application',

    // preloading 'log' component
    'preload' => array('log'),

    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.components.emailParsers.*',
        'application.components.apiClasses.*',
        'application.extensions.*',
        'application.extensions.XWebDebugRouter.*',
        'application.extensions.CustomFuncs.*',
        'application.extensions.cleditor.ECLEditor',
        'application.extensions.Logger.*',
        'application.extensions.TurboApi.*',
        'application.notifiers.*',
        'application.helpers.*',
    ),

    // application components
    'components' => array(
        'db' => require(dirname(__FILE__) . '/db.php'),

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
                '/blog/<id:\d+>-<alias:[\w\-]+>' => '/post/view',
                '/post/<id:\d+>' => '/post/view',
                '/yurist/<countryAlias:[\w\-]+>' => '/region/country',
                '/yurist/<countryAlias:[\w\-]+>/<regionAlias:[\w\-]+>' => '/region/view',
                '/yurist/<countryAlias:[\w\-]+>/<regionAlias:[\w\-]+>/<name:[\w\-]+>' => '/town/alias',
            ),
        ),

        'ih' => array(
            'class' => 'CImageHandler',
        ),

        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
    ),
    'params' => require(dirname(__FILE__) . '/params.php'),
);
