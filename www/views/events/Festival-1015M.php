<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');

?>
<!DOCTYPE HTML>
<html>
<head>
    <title>理财王推广大师活动</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link rel="shortcut icon" href="<?php echo $directoryAsset;?>/images/favicon2.ico"/>
    <link type="text/css" href="<?php echo $directoryAsset; ?>/css/20151015/base.css" rel="stylesheet">
    <link type="text/css" href="<?php echo $directoryAsset; ?>/css/20151015/index.css" rel="stylesheet">
</head>
<body>
<div id="wape">
    <div class="top">
        <img src="<?php echo $directoryAsset; ?>/images/2015-10-15/top.png">
        <!--   <a href=""><img src="images/logo.png"></a> -->
    </div>
    <div class="content">
        <div class="bjtp">
            <img src="<?php echo $directoryAsset; ?>/images/2015-10-15/botit.png">
        </div>
        <div class="cot_nr">
            <div class="main">
                <h2><img src="<?php echo $directoryAsset; ?>/images/2015-10-15/jiangliguize.png"></h2>
                <div class="con_nr_lc">
                    <img src="<?php echo $directoryAsset; ?>/images/2015-10-15/guangt.png">
                </div>
                <h2><img src="<?php echo $directoryAsset; ?>/images/2015-10-15/huodongxiangqing.png"></h2>
                <div class="con_nr_jj">
                    <h3>一、时间<span><img src="<?php echo $directoryAsset; ?>/images/2015-10-15/1.png"></span></h3>
                    <p>2015年10月15日~2016年1月15日</p>
                    <h3>二、奖励<span><img src="<?php echo $directoryAsset; ?>/images/2015-10-15/2.png"></span></h3>
                    <p>1、邀请者奖励：最多可获得203000元（此次活动每人邀请前100名计入奖励）。</p>
                    <p>2、被邀请者奖励：8888元体验金，有效期7天。</p>
                    <h3>三、规则<span><img src="<?php echo $directoryAsset; ?>/images/2015-10-15/3.png"></span></h3>
                    <p>1、关于“推广大师/推广新手”</p>
                    <p class="erji">（1）“推广大师”可享受两级邀请奖励，“推广新手”可享受一级奖励。（第一级奖励：即邀请者和直接被邀请人同时达到投资金额及投资时间要求，邀请者可获得30元；第二级奖励：即邀请者和间接被邀请人同时达到投资金额及投资时间邀请，邀请者可获得20元；），具体见表格。</p>
                    <p class="erji">（2）只要邀请好友，系统就会自动计数并记录两级邀请，但邀请奖励需在邀请者和被邀请者同时满足条件时方可进行提现及其他操作。</p>
                    <p class="erji">（3）可通过理财王APP“推广大师”查看相应的邀请记录及奖励详情。</p>
                    <img src="<?php echo $directoryAsset; ?>/images/2015-10-15/biage.png">
                    <p>2、关于被邀请者</p>
                    <p class="erji">被推荐的新注册用户可获得8888元体验金，有效期7天。</p>
                    <h3>四、注释<span><img src="<?php echo $directoryAsset; ?>/images/2015-10-15/4.png"></span></h3>
                    <p>1、被邀请者需充值并投资，才可使用8888元体验金并计息。</p>
                    <p>2、在投金额及在投时长是连续性累计的，期间出现间断，都需要重新累计。</p>
                    <p>3、本活动最终解释权归理财王所有，对于任何有作弊行为的用户，理财王有权取消其推广资格并扣除全部奖金。</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>



