<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>理财王推广大师活动</title>
    <link rel="shortcut icon" href="<?php echo $directoryAsset; ?>/images/favicon2.ico"/>
    <link href="<?php echo $directoryAsset; ?>/css/20151015/base.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo $directoryAsset; ?>/css/20151015/pc_index.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo $directoryAsset; ?>/css/hqwfloat.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="<?php echo $directoryAsset; ?>/js/jquery-1.9.1.min.js" ></script>
    <script type="text/javascript" src="<?php echo $directoryAsset; ?>/js/hqwfloat.js" ></script>
</head>

<body>
<div class="sub">
<!--    <div class="sub01"></div>-->
<!--    <div class="sub01"></div>-->
    <div class="fixed">
        <img src="<?php echo $directoryAsset; ?>/images/phone.png" />
        <p>扫码下载理财王APP</p>
    </div>
</div>
<div id="header">
</div>
<div id="content">
    <div class="mainpc">
        <h2><img src="<?php echo $directoryAsset; ?>/images/2015-10-15/jiangliguize.png"/></h2>

        <div class="con_nr_lc">
            <img src="<?php echo $directoryAsset; ?>/images/2015-10-15/guangt.png">
        </div>
        <h2><img src="<?php echo $directoryAsset; ?>/images/2015-10-15/huodongxiangqing.png"></h2>

        <div class="con_nr_jj">
            <h3 class="h31">一、时间</h3>

            <p>2015年10月15日~2016年1月15日</p>

            <h3 class="h32">二、奖励</h3>

            <p>1、邀请者奖励：最多可获得203000元（此次活动每人邀请前100名计入奖励）。</p>

            <p>2、被邀请者奖励：8888元体验金，有效期7天。</p>

            <h3 class="h33">三、规则</h3>

            <p>1、关于“推广大师/推广新手”</p>

            <p class="erji">
                （1）“推广大师”可享受两级邀请奖励，“推广新手”可享受一级奖励。（第一级奖励：即邀请者和直接被邀请人同时达到投资金额及投资时间要求，邀请者可获得30元；第二级奖励：即邀请者和间接被邀请人同时达到投资金额及投资时间邀请，邀请者可获得20元；），具体见表格。</p>

            <p class="erji">（2）只要邀请好友，系统就会自动计数并记录两级邀请，但邀请奖励需在邀请者和被邀请者同时满足条件时方可进行提现及其他操作。</p>

            <p class="erji">（3）可通过理财王APP“推广大师”查看相应的邀请记录及奖励详情。</p>

            <p class="erji"><img src="<?php echo $directoryAsset; ?>/images/2015-10-15/pc_biage.png"></p>

            <p>2、关于被邀请者</p>

            <p class="erji">被推荐的新注册用户可获得8888元体验金，有效期7天。</p>

            <h3 class="h34">四、注释</h3>

            <p>1、被邀请者需充值并投资，才可使用8888元体验金并计息。</p>

            <p>2、在投金额及在投时长是连续性累计的，期间出现间断，都需要重新累计。</p>

            <p>3、本活动最终解释权归理财王所有，对于任何有作弊行为的用户，理财王有权取消其推广资格并扣除全部奖金。</p>
        </div>
    </div>
</div>


</body>
</html>
