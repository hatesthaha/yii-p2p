<?php
use yii\helpers\Html;

/* @var $content string */

?>
<?php $this->beginPage() ?>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="UTF-8">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta name="data-spm" content="a215s">
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="fullscreen=yes,preventMove=no" name="ML-Config">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>理财王</title>
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') . '/' ?>rq-css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') . '/' ?>rq-css/reset.css">
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') . '/' ?>css/index.css">
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') . '/' ?>css/reset.css">
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') . '/' ?>rq-css/style.css">
    <?php if (isset($this->blocks['inline_styles'])): ?>
        <?= $this->blocks['inline_styles'] ?>
    <?php endif; ?>
    <?= Html::csrfMetaTags() ?>
</head>
<body>
<div id="body_a2"></div>
<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
<script src="<?= Yii::getAlias('@web') . '/' ?>rq-js/jquery.min.js"></script>
<script src="<?= Yii::getAlias('@web') . '/' ?>rq-js/touchscroll.js"></script>
<script src="<?= Yii::getAlias('@web') . '/' ?>rq-js/touchscroll.dev.js"></script>
<script src="<?= Yii::getAlias('@web') . '/' ?>js/div.js"></script>
<script>
    $(document).ready(function(){
        var UA=window.navigator.userAgent;  //使用设备
        var CLICK="click";
        if(/ipad|iphone|android/.test(UA)){   //判断使用设备
            CLICK="tap";
        }

        $("body").css("min-height",$(window).height());
        $(".wapper").css("min-height",$(window).height());
        if($(window).width()<640){
            var B = $(window).width()/320*100*0.625+"%";
            $("html").css("font-size",B)
        }
        $('.assets-divR').each(function(){
            var T = $(this).parent(".assets-div").height();
            $(this).css("margin-top",(T-$(this).height())/2)

        })
        $(".more")[CLICK](function(event){
            event.preventDefault();
            $("#wh_cover").show();
            $(".quit_login").show();
        });
        $("#body_a2").hide();
    })
</script>
<?php if (isset($this->blocks['inline_scripts'])): ?>
    <?= $this->blocks['inline_scripts'] ?>
<?php endif; ?>
</body>
</html>
<?php $this->endPage() ?>