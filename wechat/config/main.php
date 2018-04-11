<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-wechat',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'wechat\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\UcenterMember',
            'enableAutoLogin' => true,
            'idParam' => '__member',
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
        'wechat' => [
            'class' => 'callmez\wechat\sdk\Wechat',
            'appId' => 'wx32814d588c44c17c',
            'appSecret' => '23b0f218574551f18db1dc991dbee87f',
            'token' => 'weixin'
        ]
    ],
    'params' => $params,
];
