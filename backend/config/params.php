<?php
return [
    'adminEmail' => 'admin@example.com',

    'adminNav'   => [
        ['name' => '内容管理', 'action' => 'cms/index', 'show' => true, 'params' => '','children' => [
            ['name' => '分类管理', 'action' => 'category/index', 'show' => true, 'params' => ''],
            ['name' => '添加分类', 'action' => 'category/create', 'show' => false, 'params' => ''],
            ['name' => '文章管理', 'action' => 'article/index', 'show' => true, 'params' => ''],
            ['name' => '网站信息分类', 'action' => 'cat/index', 'show' => true, 'params' => ''],
            ['name' => '网站信息分类', 'action' => 'link/index', 'show' => true, 'params' => ''],
        ]],
        ['name' => '系统', 'action' => 'system/index', 'show' => true, 'params' => '','children' => [
            ['name' => '用户管理', 'action' => 'user/index', 'show' => true, 'params' => ''],
            ['name' => '角色管理', 'action' => 'role/index', 'show' => true, 'params' => ''],
        ]],
        ['name' => '网站设置', 'action' => 'setting/index', 'show' => true, 'params' => ''],
        ['name' => '财务报表', 'action' => 'finance/index', 'show' => true, 'params' => '','children' => [
            ['name' => '资金交易记录', 'action' => 'log/index', 'show' => true, 'params' => ''],
            ['name' => '充值记录', 'action' => 'payment/index', 'show' => true, 'params' => ''],
            ['name' => '提现记录', 'action' => 'withdraw/index', 'show' => true, 'params' => ''],
            ['name' => '会员订单记录', 'action' => 'order/index', 'show' => true, 'params' => ''],

        ]],
        ['name' => '会员管理', 'action' => 'member/index', 'show' => true, 'params' => '','children' => [
            ['name' => '会员信息', 'action' => 'ucentermember/index', 'show' => true, 'params' => ''],
            ['name' => '会员资金', 'action' => 'info/index', 'show' => true, 'params' => ''],
            ['name' => '会员身份认证', 'action' => 'idcardlog/index', 'show' => true, 'params' => ''],
            ['name' => '会员操作日志', 'action' => 'operating/index', 'show' => true, 'params' => ''],
            ['name' => '登陆日志', 'action' => 'memberlog/index', 'show' => true, 'params' => ''],
        ]],
        ['name' => '活动管理', 'action' => 'activity/index', 'show' => true, 'params' => '','children' => [
            ['name' => '增息列表', 'action' => 'raisecard/index', 'show' => true, 'params' => ''],
            ['name' => '增息卡管理', 'action' => 'card/index', 'show' => true, 'params' => ''],
            ['name' => '兑换码管理', 'action' => 'code/index', 'show' => true, 'params' => ''],
        ]],
        ['name' => '债权管理', 'action' => 'tproduct/index', 'show' => true, 'params' => '','children' => [
            ['name' => '已审核债权', 'action' => 'thirdproduct/uncheck', 'show' => true, 'params' => ''],
            ['name' => '未审核债权', 'action' => 'thirdproduct/check', 'show' => true, 'params' => ''],
        ]],
        ['name' => '第三方债权管理', 'action' => 'thirdproduct/index', 'show' => true, 'params' => ''],
        ['name' => '发布项目', 'action' => 'product/index', 'show' => true, 'params' => ''],
    ],
];
