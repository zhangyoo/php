<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);

$config = [
    'id' => 'app-apps',
    'defaultRoute' => 'index',//默认控制器
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'apps\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 't4NSrzaWOWj7d05G1ggiSx7013pvGGRv',
        ],
        'view' => [
            'theme' => [  
                'pathMap' => ['@app/views' => '@app/themes/bootstrap'],  
                'baseUrl' => '@web/themes/bootstrap',  
            ], 
        ],
        //路由解析配置
        'urlManager' => [
            'showScriptName' => false, //隐藏入口脚本index.php
            'enablePrettyUrl' => true, //路由的路径优化
            'suffix' => '.html', //加入假后缀(fake suffix) .html,开启伪静态
            'rules' => [
                
            ],
        ],
    ],
    'params' => $params,
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
