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
                'application.components.emailParsers.*',
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
			'username' => '100yuristov',
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
                    'baseUrl'	=>	'http://100juristov',
                    'rules'=>array(
                        '/q'                            =>  '/question/index',
                        '/q/<id:\d+>'                   =>  '/question/view',
                        '/q/<date:[\w\-]+>'             =>  '/question/archive',
                        '/cat'                          =>  '/questionCategory/index',
                        array(
                            'class' => 'application.components.QuestionCategoryRule',
                        ),
                        '/ord/<id:\d+>'                 => '/order/view',
                        '/town/<id:\d+>'                =>  '/town/view',
                        '/blog/<id:\d+>-<alias:[\w\-]+>' => '/post/view',
                        '/post/<id:\d+>'                =>  '/post/view',
                        '/company/<alias:[\w\-]+>'      =>  '/yurCompany/town',
                        '/company'                      =>  '/yurCompany/index',
                        '/firm/<id:\d+>'                =>  '/yurCompany/view',
                        '/yurist/<countryAlias:[\w\-]+>'                =>  '/region/country',
                        '/yurist/<countryAlias:[\w\-]+>/<regionAlias:[\w\-]+>'                =>  '/region/view',
                        '/yurist/<countryAlias:[\w\-]+>/<regionAlias:[\w\-]+>/<name:[\w\-]+>'  =>  '/town/alias',
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
