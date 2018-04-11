<?php
return [
    'language' => 'zh-CN',
    'name' => '万虎网络P2P',
    'timeZone' => 'Asia/Shanghai',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'language'=>'zh-CN',
    'components' => [
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,//放在本地的邮件列表,测试邮件的时候改为true
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.sina.com',
                'username' => 'wanhunet@sina.com',
                'password' => 'wanhunet123',
                'port' => '25',
            ],
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '101.200.195.101',
            'port' => 6379,
            'database' => 0,
        ],
        'cache'      => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            //'defaultRoles' => ['guest'],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [
            ],
        ],
    ],
];
