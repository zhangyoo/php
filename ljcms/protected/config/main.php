<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'乐居文件管理系统',
    'language'=>'zh_cn',
    'timeZone'=>'Asia/Shanghai',
	'theme'=>'default',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
        'application.components.widgets.*',//自定义小物件
        'application.extensions.*',
        'application.extensions.mail.*',
        'application.extensions.nav.*',
        'application.extensions.temp.*',
	),
    
    'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'password',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
	),

	'defaultController'=>'default',

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
        'session'=>array(
            'class'=>'HttpSession'
        ),
        'thumb'=>array(
			'class'=>'CThumb',
		),
        
		// uncomment the following to use a MySQL database
        //数据库,暂时先用leju数据库
//        'db'=>array(
//			'connectionString' => 'mysql:host=192.168.16.251;dbname=leju',
//			'emulatePrepare' => true,
//			'username' => 'gezsns',
//			'password' => 'gezsns',
//			'charset' => 'utf8',
//			'tablePrefix' => 'tbl_',
//		),
		'db'=>array(
			'connectionString' => 'mysql:host=127.0.0.1;dbname=gezlife_test',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'tablePrefix' => 'tbl_',
		),
//        //数据库2
//        'db2'=>array(
//			'connectionString' => 'mysql:host=192.168.16.251;dbname=leju',
//			'emulatePrepare' => true,
//			'username' => 'gezsns',
//			'password' => 'gezsns',
//			'charset' => 'utf8',
//			'tablePrefix' => 'tbl_',
//            'class'=> 'CDbConnection'
//		),
        'authManager'=>array(
			'class'=>'CDbAuthManager',
			'defaultRoles'=>array('guest'),//默认角色
            'itemTable' => 'cms_auth_item',//认证项表名称
            'itemChildTable' => 'cms_auth_item_child',//认证项父子关系
            'assignmentTable' => 'cms_auth_assignment',//认证项赋权关系
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=>false,//去掉路径中的index.php
			'rules'=>array(
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
//				array(//在页面上显示信息
//		            'class'=>'CWebLogRoute',
//		            'levels'=>'trace, info, error, warning',   //级别为trace
//		            'categories'=>'system.db.*' //只显示关于数据库信息,包括数据库连接,数据库执行语句
//		        ),
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);