<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        //使用配置数组注册"db"组件
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=120.25.239.171;dbname=algdb',
            'username' => 'zy',
            'password' => 'seo!@#123456',
            'charset' => 'utf8',
            'tablePrefix' => 'alg_',
        ],
        //发送邮件配置
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        //使用类名注册"cache"组件
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        //路由解析配置
        'urlManager' => [
            'showScriptName' => false, //隐藏入口脚本index.php
            'enablePrettyUrl' => true, //路由的路径优化
            'rules' => [
                
            ],
        ],
    ],
];
