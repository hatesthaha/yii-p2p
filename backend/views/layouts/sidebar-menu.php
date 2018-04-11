<?php
use common\widgets\Menu;

echo Menu::widget(
    [
        'options' => [
            'class' => 'sidebar-menu'
        ],
        'items' => [
//            [
//                'label' => Yii::t('app', 'Dashboard'),
//                'url' => ['/gii'],
//                'icon' => 'fa-dashboard',
//                'active' => Yii::$app->request->url === Yii::$app->homeUrl,
//            ],

            [
                'label' => Yii::t('app', '发布项目'),
                'url' => ['/product'],
                'icon' => 'fa-dashboard',
                'active' => Yii::$app->request->url === Yii::$app->homeUrl,
                'visible' => Yii::$app->user->can('product/index'),
            ],
            [
                'label' => Yii::t('app', '第三方发布债权'),
                'url' => ['/thirdproduct'],
                'icon' => 'fa fa-road',
                'visible' => Yii::$app->user->can('thirdproduct/index'),
            ],
            [
                'label' => Yii::t('app', '债权管理'),
                'url' => ['#'],
                'icon' => 'fa fa-suitcase',
                'options' => [
                    'class' => 'treeview',
                ],
                'visible' => Yii::$app->user->can('tproduct/index'),
                'items' => [
                    [
                        'label' => Yii::t('app', '第三方已审核债权'),
                        'url' => ['/thirdproduct/uncheck'],
                        'icon' => 'fa fa-coffee',
                        'visible' => Yii::$app->user->can('thirdproduct/uncheck'),
                    ],
                    [
                        'label' => Yii::t('app', '第三方未审核债权'),
                        'url' => ['/thirdproduct/check'],
                        'icon' => 'fa fa-ticket',
                        'visible' => Yii::$app->user->can('thirdproduct/check'),
                    ],
                ],
            ],
            [
                'label' => Yii::t('app', '活动管理'),
                'url' => ['#'],
                'icon' => 'fa  fa-caret-square-o-left',
                'options' => [
                    'class' => 'treeview',
                ],
                'visible' => Yii::$app->user->can('activity/index'),
                'items' => [
                    [
                        'label' => Yii::t('app', '生成邀请码'),
                        'url' => ['/invitationcode'],
                        'icon' => 'fa fa-stack-exchange',
                        'visible' => Yii::$app->user->can('activity/index'),
                    ],
                    [
                        'label' => Yii::t('app', '体验金类型'),
                        'url' => ['/rule'],
                        'icon' => 'fa fa-stack-exchange',
                        'visible' => Yii::$app->user->can('activity/index'),
                    ],
                    [
                        'label' => Yii::t('app', '体验金记录'),
                        'url' => ['/gold'],
                        'icon' => 'fa fa-stack-exchange',
                        'visible' => Yii::$app->user->can('activity/index'),
                    ],
                    [
                        'label' => Yii::t('app', '签到记录'),
                        'url' => ['/signin'],
                        'icon' => 'fa fa-stack-exchange',
                        'visible' => Yii::$app->user->can('activity/index'),
                    ],
                    [
                        'label' => Yii::t('app', '增息卡管理'),
                        'url' => ['/card'],
                        'icon' => 'fa-stack-exchange',
                        'visible' => Yii::$app->user->can('card/index'),
                    ],
                    [
                        'label' => Yii::t('app', '兑换码管理'),
                        'url' => ['/code'],
                        'icon' => ' fa-barcode',
                        'visible' => Yii::$app->user->can('code/index'),
                    ],
                    [
                        'label' => Yii::t('app', '中秋活动红包记录'),
                        'url' => ['/activitylog'],
                        'icon' => ' fa-barcode',
                    ],
                    [
                        'label' => Yii::t('app', '开展活动'),
                        'url' => ['/holdactivity'],
                        'icon' => ' fa-barcode',
                    ],
                ],
            ],
            [
                'label' => Yii::t('app', '财务报表'),
                'url' => ['#'],
                'icon' => 'fa-indent',
                'options' => [
                    'class' => 'treeview',
                ],
                'visible' => Yii::$app->user->can('finance/index'),
                'items' => [
                    [
                        'label' => Yii::t('app', '资金交易记录'),
                        'url' => ['/log'],
                        'icon' => 'fa fa-road',
                        'visible' => Yii::$app->user->can('log/index'),
                    ],
                    [
                        'label' => Yii::t('app', '充值记录'),
                        'url' => ['/sinadeposit'],
                        'icon' => 'fa-crosshairs',
                        'visible' => Yii::$app->user->can('payment/index'),
                    ],
                    [
                        'label' => Yii::t('app', '提现记录'),
                        'url' => ['/sinawithdraw'],
                        'icon' => ' fa-fire-extinguisher',
                        'visible' => Yii::$app->user->can('withdraw/index'),
                    ],
                    [
                        'label' => Yii::t('app', '投资记录'),
                        'url' => ['/order'],
                        'icon' => 'fa-folder',
                        'visible' => Yii::$app->user->can('order/index'),
                    ],
                    [
                        'label' => Yii::t('app', '新浪绑定银行卡记录'),
                        'url' => ['/sinabank'],
                        'icon' => 'fa-folder',
                        'visible' => Yii::$app->user->can('order/index'),
                    ],
//                    [
//                        'label' => Yii::t('app', '新浪充值记录'),
//                        'url' => ['/sinadeposit'],
//                        'icon' => 'fa-folder',
//                        'visible' => Yii::$app->user->can('order/index'),
//                    ],
                    [
                        'label' => Yii::t('app', '新浪投资记录'),
                        'url' => ['/sinainvest'],
                        'icon' => 'fa-folder',
                        'visible' => Yii::$app->user->can('order/index'),
                    ],
                    [
                        'label' => Yii::t('app', '新浪会员记录'),
                        'url' => ['/sinamember'],
                        'icon' => 'fa-folder',
                        'visible' => Yii::$app->user->can('order/index'),
                    ],
                    [
                        'label' => Yii::t('app', '新浪赎回记录'),
                        'url' => ['/sinaransom'],
                        'icon' => 'fa-folder',
                        'visible' => Yii::$app->user->can('order/index'),
                    ],
                    [
                        'label' => Yii::t('app', '新浪冻结解冻记录'),
                        'url' => ['/sinafreeze'],
                        'icon' => 'fa-folder',
                        'visible' => Yii::$app->user->can('order/index'),
                    ],
                    [
                        'label' => Yii::t('app', '银行限制列表'),
                        'url' => ['/banklist'],
                        'icon' => 'fa fa-road',
//                        'visible' => Yii::$app->user->can('banklist/index'),
                    ],

                    [
                        'label' => Yii::t('app', '交易退款'),
                        'url' => ['/sinawithdraw/refund'],
                        'icon' => 'fa-folder',
                        'visible' => Yii::$app->user->can('order/index'),
                    ],
                ],
            ],
            [
                'label' => Yii::t('app', '会员管理'),
                'url' => ['#'],
                'icon' => ' fa-group',
                'options' => [
                    'class' => 'treeview',
                ],
                'visible' => Yii::$app->user->can('member/index'),
                'items' => [
                    [
                        'label' => Yii::t('app', '会员信息'),
                        'url' => ['/ucentermember'],
                        'icon' => 'fa fa-coffee',
                        'visible' => Yii::$app->user->can('ucentermember/index'),
                    ],
                    [
                        'label' => Yii::t('app', '会员分类'),
                        'url' => ['/ucentercat'],
                        'icon' => 'fa fa-coffee',
                        'visible' => Yii::$app->user->can('ucentermember/index'),
                    ],
                    [
                        'label' => Yii::t('app', '会员资金'),
                        'url' => ['/info'],
                        'icon' => 'fa fa-ticket',
                        'visible' => Yii::$app->user->can('info/index'),

                    ],

                    [
                        'label' => Yii::t('app', '身份认证记录'),
                        'url' => ['/idcardlog'],
                        'icon' => 'fa-magic',
                        'visible' => Yii::$app->user->can('idcardlog/index'),
                    ],


                    [
                        'label' => Yii::t('app', '操作日志'),
                        'url' => ['/operating'],
                        'icon' => ' fa-male',
                        'visible' => Yii::$app->user->can('operating/index'),
                    ],
                    [
                        'label' => Yii::t('app', '登陆日志'),
                        'url' => ['/memberlog'],
                        'icon' => 'fa-picture-o',
                        'visible' => Yii::$app->user->can('memberlog/index'),
                    ],
                ],
            ],

            [
                'label' => Yii::t('app', 'CMS'),
                'url' => ['/cms'],
                'icon' => 'fa  fa-caret-square-o-left',
                'options' => [
                    'class' => 'treeview',
                ],
                'visible' => Yii::$app->user->can('cms/index'),
                'items' => [
                    [
                        'label' => Yii::t('app', 'Category'),
                        'url' => ['/category'],
                        'icon' => 'fa fa-stack-exchange',
                        'visible' => Yii::$app->user->can('category/index'),
                    ],
                    [
                        'label' => Yii::t('app', 'Article'),
                        'url' => ['/article'],
                        'icon' => 'fa-sitemap',
                        'visible' => Yii::$app->user->can('article/index'),
                    ],
                    [
                        'label' => Yii::t('app', '关于我们'),
                        'url' => ['/about'],
                        'icon' => 'fa-sitemap',
                        'visible' => Yii::$app->user->can('article/index'),
                    ],
                    [
                        'label' => Yii::t('app', '网站信息分类'),
                        'url' => ['/cat'],
                        'icon' => 'fa-columns',
                        'visible' => Yii::$app->user->can('cat/index'),
                    ],
                    [
                        'label' => Yii::t('app', '网站信息'),
                        'url' => ['/link'],
                        'icon' => 'fa-list-alt',
                        'visible' => Yii::$app->user->can('link/index'),
                    ],
                    [
                        'label' => Yii::t('app', '反馈信息'),
                        'url' => ['/feedback'],
                        'icon' => 'fa-list-alt',
                        'visible' => Yii::$app->user->can('link/index'),
                    ],
                    [
                        'label' => Yii::t('app', 'app轮播图'),
                        'url' => ['/lunbo'],
                        'icon' => 'fa-list-alt',
//                        'visible' => Yii::$app->user->can('carousel/index'),
                    ],
                ],
            ],
            [
                'label' => Yii::t('app', 'System'),
                'url' => ['#'],
                'icon' => 'fa fa-cog',
                'options' => [
                    'class' => 'treeview',
                ],
                'visible' => Yii::$app->user->can('system/index'),
                'items' => [
                    [
                        'label' => Yii::t('app', 'User'),
                        'url' => ['/user/index'],
                        'icon' => 'fa fa-user',
                        'visible' => Yii::$app->user->can('user/index'),
                    ],
                    [
                        'label' => Yii::t('app', 'Role'),
                        'url' => ['/role/index'],
                        'icon' => 'fa fa-lock',
                        'visible' => Yii::$app->user->can('role/index'),

                    ],
                ],
            ],
            [
                'label' => Yii::t('app', '网站设置'),
                'url' => ['/setting'],
                'icon' => 'fa-windows',
                'active' => Yii::$app->request->url === Yii::$app->homeUrl,
                'visible' => Yii::$app->user->can('setting/index'),
            ],
            [
                'label' => Yii::t('app', '新浪账户配置'),
                'url' => ['sinaconfig/update/1'],
                'icon' => 'fa-windows',
                'active' => Yii::$app->request->url === Yii::$app->homeUrl,
                'visible' => Yii::$app->user->can('setting/index'),
            ],
            [
                'label' => Yii::t('app', '新浪账户二配置'),
                'url' => ['sinaconfig/update/2'],
                'icon' => 'fa-windows',
                'active' => Yii::$app->request->url === Yii::$app->homeUrl,
                'visible' => Yii::$app->user->can('setting/index'),
            ],
            [
                'label' => Yii::t('app', '投资充值提现配置'),
                'url' => ['assetconfig/update/2'],
                'icon' => 'fa-windows',
                'active' => Yii::$app->request->url === Yii::$app->homeUrl,
                'visible' => Yii::$app->user->can('setting/index'),
            ],

            [
                'label' => Yii::t('app', '网站账户与新浪账户'),
                'url' => ['sitesina/index'],
                'icon' => 'fa-windows',
                'active' => Yii::$app->request->url === Yii::$app->homeUrl,
                'visible' => Yii::$app->user->can('sitesina/index'),
            ],
            [
                'label' => Yii::t('app', '修改个人资料'),
                'url' => ['/pass/update'],
                'icon' => 'fa-compass',
            ],
        ]
    ]
);