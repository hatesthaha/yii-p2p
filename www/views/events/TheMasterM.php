<?php
/**
 * Created by PhpStorm.
 * User: 利亚
 * Date: 2015/10/12
 * Time: 19:53
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
//获取邀请链接里面的邀请码
$code = Yii::$app->request->get('code', 0);
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>理财王推广大师计划</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link type="text/css" href="<?php echo $directoryAsset; ?>/css/20151015/base.css" rel="stylesheet">
    <link type="text/css" href="<?php echo $directoryAsset; ?>/css/20151015/index.css" rel="stylesheet">
</head>
<body>
<div id="wape">
    <div class="top">
        <img src="<?php echo $directoryAsset; ?>/images/2015-10-15/top.png">
        <a href=""><img src="<?php echo $directoryAsset; ?>/images/2015-10-15/logo.png"></a>
    </div>
    <div class="content">
        <div class="bjtp">
            <img src="<?php echo $directoryAsset; ?>/images/2015-10-15/zcbj.jpg">
        </div>
        <div class="cot_nr">
            <div class="main">
                <h2><img src="<?php echo $directoryAsset; ?>/images/2015-10-15/jiangliguize.png"></h2>
                <div class="con_nr_lc">
                    <img src="<?php echo $directoryAsset; ?>/images/2015-10-15/guangt.png">
                </div>
                <div id='register'>
                    <b class="zc_bd" >
                        <p><?php echo $invite_phone;?>送您<b style="color: red">8888</b>元红包</p>
                        <input type ="hidden" name="_csrf" value="<?php echo yii::$app->request->getCsrfToken();?>" />
                        <input type="hidden" value="<?php echo $code;?>" name="url_code" />
                        <div class="ggys">
                            <span class="left"><img src="<?php echo $directoryAsset; ?>/images/2015-10-15/zc_1.png"></span>
                            <input class="left" type="text" placeholder="请输入您的手机号" name="username" maxlength="11" name="username" id="username">
                            <div class="clear"></div>
                        </div>
                        <div class="ggys">
                            <span class="left"><img src="<?php echo $directoryAsset; ?>/images/2015-10-15/zc_2.png"></span>
                            <input class="left" type="password" placeholder="请设置密码(6-16位数字字母组合)" name="password" >
                            <div class="clear"></div>
                        </div>
                        <div class="ggys yzm">
                        <span class="left">
                            <img src="<?php echo $directoryAsset; ?>/images/2015-10-15/zc_3.png"></span>
                            <input class="left" type="text" name="validate_code" placeholder="请输入验证码">
                            <a class="left" id="sendCodeBtn">获取验证码</a>
                            <div class="clear"></div>
                        </div>
                        <button name="" id="submit">马上领取</button>
                        <br/>

                        <p style="text-align: right;padding-right:10px;padding-top: 10px"><a href="<?php echo yii\helpers\Url::to(['events/festival20151016intro']);?>" style="color: red ;">活动介绍</a></p>

                </div>

                </div>
                <div class="zc_bd" id= 'red_packet' style="display:none;">
                     <button name="" id="test1">马上使用红包</button>
                   <div align="center" style="margin-top:10px;">
                        <img src="http://static.licaiwang.com/imgs/download.png" style="height: 157px;width: 158px">
                        <p>长按识别图中二维码</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="<?php echo $directoryAsset;?>/js/jquery-1.7.1.js"></script>
<script src="<?php echo $directoryAsset;?>/js/jquery.cookie.js"></script>
<script>
    $(window).ready(function(){
        $(".page-tab tr:even").addClass("trOdd");
    })
    $(window).ready(function(){
           $('#test1').click(function(){
            window.location.href="http://www.pgyer.com/licaiwang";
        });
        //获取cookie
        var cookie_phone = $.cookie('phone');
        var url_invite = $("input[name='url_code']").val();
        var money_invite = $.cookie('money');
        if(cookie_phone && url_invite){
            $('#red_packet').show();
            $('#register').hide();
            $('#money').html(money_invite);
        }
        $('#activity').click(function(){
            alert('111');
        });
        var sendCode = true;
        $('#sendCodeBtn').click(function(){
            var phone = $('#username').val();
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
                        data = JSON.parse(data);
                        if(parseInt(data['errorNum'])){
                              if(data['errorMsg'] =='此手机号已注册，请直接登录'){
                                $('#red_packet').show();
                                $('#register').hide();
                                $('#test1').html('马上去登录');
                            }
                            alert(data['errorMsg']);
                        }else{
                            sendCode = false;
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

            var phone = $("input[name='username']").val();
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

            $.post("<?php echo yii\helpers\Url::to(['events/signupofmaster']);?>",
                {
                    'phone' : phone,
                    '_csrf' : '<?php echo yii::$app->request->getCsrfToken();?>',
                    'password' : password,
                    'validate_code' : validate_code,
                    'invite_code' : url_code
                },
                function (data){
                    hasSubmit = false;
                    data = JSON.parse(data);
                    if(parseInt(data['errorNum'])){
                           if(data['errorMsg'] =='此手机号已注册，请直接登录'){
                                $('#red_packet').show();
                                $('#register').hide();
                                $('#test1').html('马上去登录');
                            }
                        alert(data['errorMsg']);
                    }else{
                        //注册成功
                        var money = data['data']['money'];
                        $('#red_packet').show();
                        $('#register').hide();
                        $('#money').html(money);
                        //写入cookie
                        $.cookie('phone', data['data']['phone']);
                        $.cookie('url_code',url_code);
                        $.cookie('money',money);
                        alert('注册成功');
                        window.location.reload();
                    }
                });
        });
    })
</script>
</html>

