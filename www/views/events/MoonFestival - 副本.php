<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '中秋红包';
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
//获取邀请链接里面的邀请码
$code = Yii::$app->request->get('code', 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta content="text/html;charset=utf-8" http-equiv="content-type">
    <meta name="referrer" content="always">
    <title>理财王</title>
    <meta name="apple-itunes-app" content="app-id=660653351">
    <meta content="活期年化收益8%+,转让变现快、提现实时到,多重保障、本息保护,账户托管至新浪支付" name="Keywords">
    <meta name="description" content="理财王" />
    <link rel="shortcut icon" href="<?php echo $directoryAsset;?>/images/favicon2.ico"/>
    <link rel="stylesheet" href="<?php echo $directoryAsset;?>/css/event_reset.css">
    <link rel="stylesheet" href="<?php echo $directoryAsset;?>/css/event_style.css">
</head>
<body>
<div id="wrap">
    <div id="header" class="top">
<!--        <div style="padding-top: 317px; display:none;">-->
        <div id='register' style="padding-top: 317px;">
            <form method="post" class="form-p">
                <input type ="hidden" name="_csrf" value="<?php echo yii::$app->request->getCsrfToken();?>" />
                <input type="hidden" value="<?php echo $code;?>" name="url_code" />
                <p class="phone"><input type="text" value="" placeholder="请输入您的手机号" name="username" maxlength="11" id="username" / ></p>
                <p class="psw"><input type="password" value="" placeholder="请设置密码" name="password" /></p>
                <p class="code"><input id="validate_code" type="text" value="" placeholder="请输入验证码" name="validate_code"/><label class="red-C" for="coden" id="sendCodeBtn">获取验证码</label></p>
                <p class="check"><input type="button"  id="submit" value="马上领取"></p>
            </form>
        </div>

        <div id= 'red_packet' style="padding-top: 290px; display:none;">
            <div class="hb">
                <div>
                    <img width="100%" alt="" src="<?php echo $directoryAsset;?>/images/events/M-hb.png">
                    <div class="hb-p">
                        <p>红包：</p>
                        <p class="text-R"><span class="hb-N" id="money">1.00</span><span class="hb-D">(元)</span></p><p></p>
                    </div>
                </div>
                <a href="" class="use-hb">马上使用红包</a>
            </div>
        </div>

    </div>
    <div id="main" style="padding-top: 200px;">
        <div>
            <h2 class="tit tit-d1">活动内容：</h2>
            <ul class="active-list clearFloat">
                <li>
                    <div>
                        <p><img width="100px" height="100px" src="<?php echo $directoryAsset;?>/images/events/act-l1.png" alt="体验金"></p>
                        <p><span class="red-C">8150元</span>体验金</p>
                    </div>
                </li>
                <li>
                    <div>
                        <p><img width="100px" height="100px" src="<?php echo $directoryAsset;?>/images/events/act-l2.png" alt="利率"></p>
                        <p><span class="red-C">8.15%</span>利率</p>
                    </div>
                </li>
                <li>
                    <div>
                        <p><img width="100px" height="100px" src="<?php echo $directoryAsset;?>/images/events/act-l3.png" alt="免费拿"></p>
                        <p><span class="red-C">双向红包</span>免费拿</p>
                    </div>
                </li>
            </ul>
        </div>
        <div>
            <h2 class="tit tit-d2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;其他人手气怎么样：</h2>
            <table class="page-tab">
                <?php if(!$invite_list['errorNum']){
                    foreach($invite_list['data'] as $key=>$value){
                 ?>
                <tr>
                    <td class="text-L page-tabL">手机号：<?php echo $value['phone']?><span><?php echo $value['update_at']?></span></td>
                    <td class="red-C text-R page-tabR"><?php echo $value['red_packet']?></td>
                </tr>
                <?php }}?>
<!--                <tr>-->
<!--                    <td class="text-L page-tabL">手机号：13256987542<span>9月20日18:34</span></td>-->
<!--                    <td class="red-C text-R page-tabR">0.5元</td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td class="text-L page-tabL">手机号：13256987542<span>9月20日18:34</span></td>-->
<!--                    <td class="red-C text-R page-tabR">0.5元</td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td class="text-L page-tabL">手机号：13256987542<span>9月20日18:34</span></td>-->
<!--                    <td class="red-C text-R page-tabR">0.5元</td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td class="text-L page-tabL">手机号：13256987542<span>9月20日18:34</span></td>-->
<!--                    <td class="red-C text-R page-tabR">0.5元</td>-->
<!--                </tr>-->
            </table>
        </div>
        <div>
            <h2 class="tit tit-d3">活动介绍：</h2>
            <div class="info-W">
                <p>1.成功领取后，“8150元特权体验金”即成功派发至您的账户，请及时注册并登录查看.</p>
                <p>2.在理财王上至少充值并投资1元，才可使用8150元特权体验金投资并计息，如果自有充值投资金额低于1元，计息停止.</p>
                <p>3.在活动期间，享受8.15%年化收益.</p>
                <p>4.每成功邀请一名好友，邀请和被邀请双方都随机获得1个0.5~5元的红包.</p>
                <p>5.活动结束且自有金额充值投资过一次后，利息收益及红包可赎回提现，特权本金理财王收回.</p>
                <p>6.本次活动最终解释权归理财王所有，若经查证属实发现有作弊行为，理财王有权力取消您的活动资格.</p>
            </div>
        </div>
    </div>
    <div id="footer" class="bottom clearFloat">
        <p class="phone-dow">
            <a href=""><img width="269px" height="70px" src="<?php echo $directoryAsset;?>/images/events/footer1.jpg" alt="安卓手机下载"></a>
            <a href=""><img width="269px" height="70px" src="<?php echo $directoryAsset;?>/images/events/footer2.jpg" alt="苹果手机下载"></a>
        </p>
        <p><img src="<?php echo $directoryAsset;?>/images/events/footer3.jpg" alt="二维码"></p>
    </div>
</div>
</body>
<script src="<?php echo $directoryAsset;?>/js/jquery-1.9.1.min.js"></script>
<script src="<?php echo $directoryAsset;?>/js/jquery.cookie.js"></script>
<script>
    $(window).ready(function(){
        //获取cookie
        var cookie_phone = $.cookie('phone');
        var url_invite = $("input[name='url_code']").val();
        var money_invite = $.cookie('money');
        if(cookie_phone && url_invite){
            $('#red_packet').show();
            $('#register').hide();
            $('#money').html(money_invite);
        }

        var sendCode = true;
        $('#sendCodeBtn').click(function(){
            var phone = $('#username').val();
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
        $('#submit').click(function () {
            var phone = $("input[name='username']").val();
            var password = $("input[name='password']").val();
            var validate_code = $("input[name='validate_code']").val();
            var url_code = $("input[name='url_code']").val();
            var actibity_source = '中秋节活动';
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
                    data = JSON.parse(data);
                    if(parseInt(data['errorNum'])){
                        alert(data['errorMsg']);
                    }else{
                        var money = data['data']['money'];
                        $('#red_packet').show();
                        $('#register').hide();
                        $('#money').html(money);
                        //写入cookie
                        $.cookie('phone', data['data']['phone']);
                        $.cookie('url_code',url_code);
                        $.cookie('money',money);
                        alert('注册成功');
                    }
                });
        });
    })
</script>
</html>



