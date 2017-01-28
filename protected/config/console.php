<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

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

	// application components
	'components'=>array(
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=100yuristov',
			'emulatePrepare' => true,
			'username' => 'crm',
			'password' => 'crm_local',
			'charset' => 'utf8',
                        'tablePrefix' => '100_',
			'enableProfiling' => true,
			'schemaCachingDuration' => 3000,
                        'queryCacheID' => 'cache',
		),
            
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
                        '/region/<countryAlias:[\w\-]+>/<regionAlias:[\w\-]+>'                =>  '/region/view',
                        '/region/<countryAlias:[\w\-]+>/<regionAlias:[\w\-]+>/<name:[\w\-]+>'  =>  '/town/alias',
                        ),
                    ),
            
                'ih'=>array(
                        'class'=>'CImageHandler',
                    ),
		
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
        'params'=>require(dirname(__FILE__).'/params.php'),
);