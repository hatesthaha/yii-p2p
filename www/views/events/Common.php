<?php
/**
 * Created by PhpStorm.
 * User: Pele
 * Date: 2015/10/19
 * Time: 13:29
 */

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
//$directoryAsset = '/www/web/myAssetsLib';
$code = Yii::$app->request->get('code', 0);
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no,minimal-ui" name="viewport">

    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta content="telephone=no" name="format-detection">
    <!-- UC默认竖屏 ，UC强制全屏 -->
    <meta name="full-screen" content="yes">
    <meta name="browsermode" content="application">
    <!-- QQ强制竖屏 QQ强制全屏 -->
    <meta name="x5-orientation" content="portrait">
    <meta name="x5-fullscreen" content="true">
    <meta name="x5-page-mode" content="app">
    <link rel="apple-touch-icon-precomposed" href="<?php echo $directoryAsset; ?>/images/favicon2.ico">
    <title>理财王-活期理财 我们更专业！</title>

    <link rel="stylesheet" href="<?php echo $directoryAsset; ?>/css/events/common.css" type="text/css">
</head>
<body>
<div style="height:60px;font-size:0;background-color:#000" id="jq-appendDIV">
    <div id="jq-download"
         style="-webkit-backdrop-filter: blur(10px);position:fixed;top:0;z-index:5;height:60px;width:100%;background-color:rgba(0, 0, 0, 0.6);font-size:12px;padding:10px 0;box-sizing: border-box">
        <div style="width:60px;text-align:center;position:absolute;">
            <img style="width:40px;height:40px" src="//static.licaiwang.com/imgs/logo7878.png">
        </div>
        <div style="position:absolute;left:60px">
            <div style="font-size:22px;color: #006fbb;font-weight:bold;line-height:22px">理财王理财</div>
            <div style="color: #c4c4c4;line-height:20px">活期理财，我们更专业！
            </div>
        </div>
        <div style="padding:5px 10px 0 0;font-size:0;position:relative">
            <div id="jq-content"
                 style="border-bottom:3px solid #e58700;border-radius:5px;background-color:#e58700;top:2px;right:45px;position:absolute">
                <div
                    style="width:80px;height:33px;background-color:#f4b000;font-size:14px;text-align:center;border-radius:5px;color:#fff;line-height:33px;font-weight:bold"
                    id="downloadHTML">下载客户端
                </div>
            </div>
        </div>
        <div id="closeDiv" style="position:absolute;top:5px;right:5px;width:20px;height:20px"><img
                style="width:100%;height:100%"
                src="//static.licaiwang.com/imgs/modifydelete.png">
        </div>
    </div>
</div>
<div class="hqw_main">
    <section class="header">
        <div class="banner">
            <img
                src="<?php echo $directoryAsset; ?>/images/events/reg_tiyanjin.jpg"
                alt="">
        </div>
    </section>

    <section class="getRedPacket">
        <div class="login">
            <div class="account_wp">
                <input type ="hidden" name="_csrf" value="<?php echo yii::$app->request->getCsrfToken();?>" />
                <input type="hidden" value="<?php echo $code;?>" name="url_code" />
                <input class="account" data-type="phone" maxlength="11" placeholder="输入您的手机号" type="tel" name='phone' id="phone">
            </div>
            <div class="account_wp">
                <input class="account" data-type="password" maxlength="16" placeholder="设置密码(6-16位数字字母组合)"
                       type="password" name='password'>
            </div>
            <div class="veri_code">
                <div class="code_wp">
                    <input class="code" data-type="vcode" maxlength="6" placeholder="填写短信验证码" type="tel" name='validate_code'>
                </div>
                <div class="get_pass_wp">
                    <a href="javascript:void(0)" data-type="getVcode" class="get_pass" id="sendCodeBtn">获取验证码</a>
                </div>
            </div>
            <a class="submit loginByMobile" data-type="loginByMobile_disable" id="submit">立即领取</a>
        </div>
    </section>

    <section class="regular">
        <div class="rule_wp">
            <h5>活动规则</h5>
            <ol class="rule">
                <li>领取流程：通过输入手机号验证即可领取理财王6666元体验金。</li>
                <li>体验金使用：在去哪儿旅行客户端首页下方”我的”中查看领取到的礼包，礼包可适用于酒店和其他旅行产品，可在去哪儿旅行客户端购买产品时使用，礼包具体包含内容以个人实际领取情况为准。</li>
                <li>领取说明：每位用户限领取一次，同一手机号、同一账号、同一设备、视为同一用户，只可领取一次噢。</li>
            </ol>
        </div>
    </section>
</div>
<!-- end qn_main -->
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
    $('#jq-content').click(function(){
            window.location.href="http://a.app.qq.com/o/simple.jsp?pkgname=com.licaiwang";
    });
    $('#closeDiv').click(function(){
      $('#jq-appendDIV').hide();
    });
    var sendCode = true;
    $('#sendCodeBtn').click(function(){
        var phone = $('#phone').val();
        var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
        if(phone == '')
        {
            alert('请输入手机号码');
            return;
        }
        else if(!reg.test(phone)){
            alert('手机号码格式不正确');
            return;
        }
        if(sendCode){
            $.post("<?php echo yii\helpers\Url::to(['events/sendcode']);?>",
                {
                    'Phone' : phone,
                    '_csrf' : '<?php echo yii::$app->request->getCsrfToken();?>'
                },
                function (data){
                    sendCode = false;
                    data = JSON.parse(data);
                    if(parseInt(data['errorNum'])){
                        sendCode = true;
                        alert(data['errorMsg']);
                    }else{
                        $('#sendCodeBtn').html('已发送');
                        alert('短信发送成功');

                    }
                });
        }
    });

    var hasSubmit = false;

    $('#submit').click(function () {

        if(hasSubmit) return;

        hasSubmit = true;

        var phone = $("input[name='phone']").val();
        var password = $("input[name='password']").val();
        var validate_code = $("input[name='validate_code']").val();
        var url_code = $("input[name='url_code']").val();
        var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
        if(phone == '')
        {
            alert('请输入手机号码');
            hasSubmit = false;
            return;
        }
        else if(!reg.test(phone)){
            alert('手机号码格式不正确');
            hasSubmit = false;
            return;
        }

        if(password.length <6) {
            alert('密码不能少于6位（字母+数字更安全）');
            hasSubmit = false;
            return;
        }

        if(validate_code == ''){
            alert('请输入短信验证码');
            hasSubmit = false;
            return;
        }

        $.post("<?php echo yii\helpers\Url::to(['events/signupofpromotion']);?>",
            {
                'phone' : phone,
                '_csrf' : '<?php echo yii::$app->request->getCsrfToken();?>',
                'password' : password,
                'validate_code' : validate_code,
                'invite_code' : url_code,
            },
            function (data){
                hasSubmit = false;
                data = JSON.parse(data);
                if(parseInt(data['errorNum'])){
                    hasSubmit = true;
                    alert(data['errorMsg']);
                }else{
                    $('#red_packet').show();
                    $('#register').hide();
                    alert('注册成功');
                    window.location.reload();
                }
            });
    });

</script>
</html>
