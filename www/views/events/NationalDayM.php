<?php
/**
 * Created by PhpStorm.
 * User: 利亚
 * Date: 2015/9/29
 * Time: 13:29
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '国庆红包';
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
//获取邀请链接里面的邀请码
$code = Yii::$app->request->get('code', 0);
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
    <meta name="description" content="理财王" />
    <title>国庆红包</title>
    <link rel="shortcut icon" href="<?php echo $directoryAsset;?>/images/events-nd/favicon2.ico"/>
    <link rel="stylesheet" href="<?php echo $directoryAsset;?>/css/event_nd_reset.css">
    <link rel="stylesheet" href="<?php echo $directoryAsset;?>/css/event_nd_M_style.css">
</head>
<body>
<article>
    <header>
        <img class="show-block" width="100%" src="<?php echo $directoryAsset;?>/images/events-nd/M-top.jpg" alt="">
    </header>
    <section>
        <div class="M-t1">
            <div class="hb hb2" id="regi">
                <p class="text-C m-t8">您的好友<?php echo $invite_phone;?>送给了您一个红包</p>
                <form action="#" method="post" class="form-p2">
                    <input type="hidden" value="<?php echo $code;?>" name="url_code" />
                    <p class="phone"><input type="text"  name="username" id= 'username' value="" placeholder="请输入您的手机号" / ></p>
                    <p class="psw"><input type="password" value=""  name="password" placeholder="请设置密码" /></p>
                    <p class="code">
                        <input id="coden" type="text" value="" name="validate_code" placeholder="请输入验证码" />
                        <label class="red-C" for="coden" style="100px;" id="sendCodeBtn">获取验证码</label></p>
                    <p class="check"><input type="button"  id="submit" value="马上领取"></p>
                </form>
            </div>
                <div class="hb" id="red_packet2" style="display:none;">
                    <div>
                        <img class="hb-imgxj" width="64%" src="<?php echo $directoryAsset;?>/images/events-nd/M-hb.png" alt="">
                        <div class="hb-p">
                            <p>红包：</p>
                            <p class="text-R"><span class="hb-N" id="money">1.00</span><span class="hb-D">(元)</span></p></p>
                        </div>
                    </div>
                </div>
                <p class="use-hb" id="red_packet" style="display:none;"><a href="http://www.pgyer.com/licaiwang">马上使用红包</a></p>
        </div>
        <div class="M-t2">
            <div class="M-div gule">
                <h2 class="M-tit">活动规则</h2>
                <div class="gule-con">
                    <div class="gule-conword">
                        <p>1.成功领取后，“6000元体验金”即成功派发至您的账户，请及时注册并登录查看。</p>
                        <p>2.在理财王上至少充值并投资1元，才可使用6000元体验金投资并计息，如果自有充值投资金额低于1元，计息停止。</p>
                        <p>3.在活动期间，享受10.1%年化收益。</p>
                        <p>4.每成功邀请一名好友，邀请和被邀请双方都随机获得1个1.0~10.1元的红包。</p>
                        <p>5.活动结束且自有金额充值投资过一次后，利息收益及红包可赎回提现，体验金理财王收回。</p>
                        <p>6.本次活动，限前500名注册投资用户享受活动奖励，其他用户敬请期待理财王正式上线活动。</p>
                        <p>7.本次活动仅限Android用户参加，ios版本正在奋力开发中，敬请期待。</p>
                        <p>8.本次活动最终解释权归理财王所有，若经查证属实发现有作弊行为，理财王有权力取消您的活动资格。</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="M-t3">
            <div class="M-div gule">
                <h2 class="M-tit">看其他人手气怎样</h2>
                <ul class="tab-con">
                    <?php if(!$invite_list['errorNum']){
                        foreach($invite_list['data'] as $key=>$value){
                            ?>
                            <li>
                                <div class="clearFloat"><p class="float-L">手机号：<?php echo $value['phone']?></p><p class="float-R"><?php echo $value['red_packet'].'元'?></p></div>
                                <p class="ta-time"><?php echo $value['update_at']?></p>
                            </li>
                        <?php }}?>
                </ul>
            </div>
        </div>
    </section>
    <footer >

    </footer>
</article>
</body>
<script src="<?php echo $directoryAsset;?>/js/jquery-1.7.1.js"></script>
<script src="<?php echo $directoryAsset;?>/js/jquery.cookie.js"></script>
<script>

    $(document).ready(function(){
        var UA=window.navigator.userAgent;  //使用设备
        var CLICK="click";
        if(/ipad|iphone|android/.test(UA)){   //判断使用设备
            CLICK="tap";
        }
        $("body").css("min-height",$(window).height());
        if($(window).width()<640){
            var B = $(window).width()/320*100*0.625+"%";
            $("html").css("font-size",B)
        }
        $(".M-t2").height($(".gule").height());
        $(".psw input").width($(".psw").width()-42);
    })
    $(window).resize(function(){
        $("body").css("min-height",$(window).height());
        if($(window).width()<640){
            var B = $(window).width()/320*100*0.625+"%";
            $("html").css("font-size",B)
        }
        $(".M-t2").height($(".gule").height());
    })
    $(window).ready(function(){
        //获取cookie
        var cookie_phone = $.cookie('phone');
        var url_invite = $("input[name='url_code']").val();
        var money_invite = $.cookie('money');
        if(cookie_phone && url_invite){
            $('#red_packet').show();
            $('#red_packet2').show();
            $('#regi').hide();
            $('#money').html(money_invite);
        }

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
            var actibity_source = '国庆节活动';

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

            $.post("<?php echo yii\helpers\Url::to(['events/signup']);?>",
                {
                    'phone' : phone,
                    '_csrf' : '<?php echo yii::$app->request->getCsrfToken();?>',
                    'password' : password,
                    'validate_code' : validate_code,
                    'invite_code' : url_code,
                    'actibity_source' : actibity_source
                },
                function (data){
                    hasSubmit = false;
                    data = JSON.parse(data);
                    if(parseInt(data['errorNum'])){
                        alert(data['errorMsg']);
                    }else{
                        var money = data['data']['money'];
                        $('.red_packet').show();
                        $('#regi').hide();
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
