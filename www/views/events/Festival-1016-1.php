<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '中秋红包1';
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta name="data-spm" content="a215s">
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="fullscreen=yes,preventMove=no" name="ML-Config">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta content="活期年化收益8%+,转让变现快、提现实时到,多重保障、本息保护,账户托管至新浪支付" name="Keywords">
    <meta name="description" content="理财王"/>
    <title>推广大师计划</title>
    <link rel="shortcut icon" href="<?php echo $directoryAsset; ?>/images/favicon2.ico"/>
    <link rel="stylesheet" href="<?php echo $directoryAsset; ?>/css/event_reset.css">
    <link rel="stylesheet" href="<?php echo $directoryAsset; ?>/css/event_m_style.css">
    <style>
        #mcover1 {
            background: none repeat scroll 0 0 rgba(0, 0, 0, 0.7);
            display: none;
            height: 100%;
            left: 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 20000;
        }
    </style>
</head>
<body>
<article>
    <header>
        <img class="show-block" width="100%"  src="<?php echo $directoryAsset; ?>/images/2015-10-16/jieshao.jpg" alt="">
    </header>
    <div id="mcover1" onclick=" document.getElementById('mcover1').style.display = 'none'; " style="display: none;">
    </div>
</article>
</body>
<script src="<?php echo $directoryAsset; ?>/js/jquery-1.9.1.min.js"></script>
<script>

    $(document).ready(function () {
        var UA = window.navigator.userAgent;  //使用设备
        var CLICK = "click";
        if (/ipad|iphone|android/.test(UA)) {   //判断使用设备
            CLICK = "tap";
        }
        $("body").css("min-height", $(window).height());
        if ($(window).width() < 640) {
            var B = $(window).width() / 320 * 100 * 0.625 + "%";
            $("html").css("font-size", B)
        }
        $(".M-t2").height($(".gule").height());
    })
    $(window).resize(function () {
        $("body").css("min-height", $(window).height());
        if ($(window).width() < 640) {
            var B = $(window).width() / 320 * 100 * 0.625 + "%";
            $("html").css("font-size", B)
        }
        $(".M-t2").height($(".gule").height());
    })

    function changeit() {
        document.getElementById('mcover1').style.display = 'block';
    }

</script>

</html>



