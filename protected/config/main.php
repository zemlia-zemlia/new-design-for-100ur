<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'100 юристов',
        'defaultController'=>'site',

	'theme'=>'2015',
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
                'application.extensions.*',
		'application.extensions.XWebDebugRouter.*',
                'application.extensions.CustomFuncs.*',
                'application.extensions.cleditor.ECLEditor',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'159357',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
            'admin',
		
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
                        'class' => 'WebUser',
		),
                'authManager' => array(
                    // Будем использовать свой менеджер авторизации
                    'class' => 'PhpAuthManager',
                    // Роль по умолчанию. Все, кто не админы, модераторы и юзеры — гости.
                    'defaultRoles' => array('guest'),
                  ),
            
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
                    'urlFormat'=>'path',
                    'showScriptName'=>false,
                    'urlSuffix'=>'/',
                    'rules'=>array(
                        '/q/<id:\d+>'                   =>  '/question/view',
                        '/cat/<id:\d+>'                 =>  '/questionCategory/view',
                        '/cat/<name:[\w\-]+>'           =>  '/questionCategory/alias',
                        '/town/<id:\d+>'                =>  '/town/view',
                        '/blog/<id:\d+>'                =>  '/blog/view',
                        '/post/<id:\d+>'                =>  '/post/view',
                        '/company/<alias:[\w\-]+>'      =>  '/yurCompany/town',
                        '/company'                      =>  '/yurCompany/index',
                        '/firm/<id:\d+>'                =>  '/yurCompany/view',
                        '/konsultaciya-yurista-<name:[\w\-]+>'  =>  '/town/aliasOld',
                        '/region/<countryAlias:[\w\-]+>/<regionAlias:[\w\-]+>'                =>  '/region/view',
                        '/region/<countryAlias:[\w\-]+>/<regionAlias:[\w\-]+>/<name:[\w\-]+>'  =>  '/town/alias',
                        '/codecs/<codecsAlias:[\w\-\.]+>'  =>  '/codecs/view',
                        '/codecs/<codecsAlias:[\w\-\.]+>/<partAlias:[\w\-\.]+>'  =>  '/codecs/view',
                        '/codecs/<codecsAlias:[\w\-\.]+>/<partAlias:[\w\-\.]+>/<glavaAlias:[\w\-\.]+>'  =>  '/codecs/view',
                        '/codecs/<codecsAlias:[\w\-\.]+>/<partAlias:[\w\-\.]+>/<glavaAlias:[\w\-\.]+>/<articleAlias:[\w\-\.]+>'  =>  '/codecs/view',
                        ),
                    ),

		'clientScript'=>array(

			'scriptMap'=>array(
                                //'jquery.js'=>'/js/jquery-1.11.1.min.js',
                                'jquery.js'=>'/js/jquery-1.11.1.min.js',
                                'jquery.min.js'=>'/js/jquery-1.11.1.min.js'
			),

		),
            
                'ih'=>array(
                        'class'=>'CImageHandler',
                    ),
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=crm',
			'emulatePrepare' => true,
			'username' => 'crm',
			'password' => 'crm_local',
			'charset' => 'utf8',
                        'tablePrefix' => 'crm_',
			'enableProfiling' => true,
			'schemaCachingDuration' => 3000,
                        'queryCacheID' => 'cache',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
                                    
				),
				// uncomment the following to show log messages on web pages
				
				/*array(
                                    'class'         =>  'CWebLogRoute',
                                    'showInFireBug' =>  false,
                                    'categories'    =>  'application',
				),*/
				
                                array(

					'class'=>'CProfileLogRoute',

				),
                                 
			),
		),
            'cache'=>array(

            'class'=>'system.caching.CFileCache',
            ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);