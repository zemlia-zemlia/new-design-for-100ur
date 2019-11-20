<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
            'request' => [
                'enableCsrfValidation' => false,
                'enableCookieValidation' => false,
            ],
            'db' => [
                'connectionString' => 'mysql:host=localhost;dbname=100yuristov_test',
                'username' => '100yuristov_test',
                'password' => '',
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
		),
	)
);
