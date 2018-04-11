<?php
use yii\helpers\Html;
use yii\helpers\Url;
use www\controllers\SmsController;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = yii::$app->request->baseUrl.'/../../backend/web/upload/';
?>
<?php $this->beginBlock('inline_styles'); ?>
<style>
    html,body,.wapper{
        height: 100%;
    }
    .cy-rg1ph label{
        width: 17%;
    }
    .cy-rg1ph input{
        width: 80%;
        padding-left: 0;
    }
</style>
<?php $this->endBlock(); ?>

<div class="wapper bgd-f5f5f4">
    <!--header-->
    <div class="header-top"><br><br><br>
        <div class="header-topList w83">
            <span><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-rgzc.png" alt="" width="25%"/></span>
            <span><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-rgyz2.png" alt="" width="25%"/></span>
            <span><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-rgwc.png" alt="" width="25%"/></span>
        </div>
        <br>
        <br>

        <p class="text-center f16 c-ffffff">HI~欢迎注册理财王<br>
            活期理财，我们更专业！
        </p>
        <br>
        <br>
        <br>
    </div>
    <!--header end-->
    <!--content-->
    <div class="content">
        <br><br>
        <div class="cy-rg1 w90">
             <span id="invitacode" class="spanti-flash">
               <?php
               if(\Yii::$app->getSession()->getFlash("errors")) {
                   echo \Yii::$app->getSession()->getFlash("errors")['info'];
               }
               ?>
            </span>
            <span class="spanti-c" id="spanti"></span>
            <form  method="post" id="signupform" action="<?php echo yii\helpers\Url::to(['site/step2']);?>">
                <input type ="hidden" name="_csrf" value="<?php echo yii::$app->request->getCsrfToken();?>" />
                <div class="cy-rg1ph">
                    <label for="">密   码：</label>
                    <input type="password" id="password" name="password" placeholder="设置登录密码，6-32位字符" required/>
                </div>
                <div class="cy-rg1ph">
                    <label for="">密   码：</label>
                    <input type="password" id="repassword" name="repassword" placeholder="确认登录密码，6-32位字符" required/>
                </div>
                <p class="text-center">注册即默认同意 <a href="javascript:;" id="protocol" class="c-e44949">《理财王服务协议》</a></p>
                <br><br>
                <button type="button" id="submit1" class="border0 bgd-787878 c-ffffff m-bottom-10" style="width: 100%;padding: 12px 0;">下一步</button>


            </form>
        </div>
        <br><br><br><br><br><br><br><br><br>
    </div>
    <!--content end-->

</div>
    <section id="wh_cover"></section>
    <section class="protocol-chose">
        <div class="protocol-con">
            <h2 class="tit"><span>理财王网站服务协议</span><i id="Close" class="icon-remove"></i></h2>
            <div class="protocol-word">
                <p>本网站由北京理财王投资有限公司成立并运营。本服务协议双方为北京理财王投资有限公司与理财王用户，本服务协议具有合同效力。</p>
                <p>　　在您注册成为本网站用户前请务必仔细阅读以下条款。若您一旦注册，则表示您同意接受本网站的服务并接受以下条款的约束。若不接受以下条款，请停止注册本网站。</p>
                <p>　　本服务协议内容包括以下条款及已经发布的或将来可能发布的各类规则。所有规则为协议不可分割的一部分，与协议正文具有同等法律效力。本协议是由用户与本网站共同签订的，适用于用户在理财王网站全部的活动。在用户注册时，用户已经阅读、理解并接受本协议的全部条款及各类规则，并承诺遵守中国现行的法律、法规、规章及其他政府规定，如有违反而导致任何法律后果的发生，用户将以自己的名义独立承担所有相应的法律责任。</p>
                <p>　　本网站有权根据需要不时地制定、修改本协议或各类规则，如本协议及规则有任何变更，一切变更以本网站最新公布条例为准，经修订的协议、规则一经公布后，则立即自动生效或在本网站指定的时间生效。用户应不时地注意本协议地变更，若用户不同意相关变更，本网站有权不经任何告知终止、中止本服务协议或者限制用户进入本网站的全部或者部分板块且不承担任何法律责任。同时，该终止、中止或限制行为并不能豁免用户在本网站已进行的交易下所应承担的义务。</p>
            </div>
        </div>
    </section>
    <style>
        .protocol-chose{
            position: fixed;
            width: 100%;
            top: 10px;
            left: 0;
            z-index: 9999;
            display: none;
        }
        .protocol-con{
            margin: 10px;
            padding: 10px;
            background: #fff;
            border-radius: 5px;
        }
        .tit{
            text-align: right;
            overflow: hidden;
            zoom:1;
            line-height: 25px;
            font-size: 16px;
            text-align: center;
            margin: 0 0 1rem;
        }
        .tit i{
            float: right;
            font-size: 20px;
        }
        .protocol-word{
            height: 200px;
            overflow-y: scroll;
        }
    </style>
<?php $this->beginBlock('inline_scripts'); ?>
<script>
    $("body").css("min-height",$(window).height());
    var UA=window.navigator.userAgent;  //使用设备
    var CLICK="click";
    if(/ipad|iphone|android/.test(UA)){   //判断使用设备
        CLICK="tap";
    }
    $(".protocol-word").height($(window).height()-95);
    $("#protocol")[CLICK](function(event){
        event.preventDefault();
        $("body").css("position","fixed");
        $("body").css("width","100%");
        $("#wh_cover").show();
        $(".protocol-chose").show();
    });
    $("#Close")[CLICK](function(){
        $("body").css("position","relative");
        $('#wh_cover').hide();
        $(".protocol-chose").hide();
    });

    var flag=[0,0];
    $('#password').blur(function(){
        var password = $('#password').val();
        var reg_pass = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/;
        if(reg_pass.test(password)) {
            flag[0] = 1;
            $('#spanti').hide();
        }else{
            $('#spanti').html('6-16位数字+字母组合');
            $('#spanti').show();
            flag[0]=0;
        }
    });
    $('#repassword').blur(function(){
        var repassword = $('#repassword').val();
        var password = $('#password').val();
        var reg_pass = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/;
        if(repassword != password){
            $('#spanti').html('两次输入密码不一致');
            $('#spanti').show();
            flag[1]=0;
        }else{
            flag[1] = 1;
            $('#submit1').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
            $('#spanti').hide();
        }
    });
    $('#submit1').click(function(){
        var repassword = $('#repassword').val();
        var password = $('#password').val();
        var res = true;
        for(var i=0;i<flag.length;i++){
            if(flag[i]==0){
                res=false;  break;
            }
        }
        if(res){
            $('#signupform').submit();
        }else{

        }
    });
    $(document).ready(function(){
        setTimeout(function(){$('#flash').fadeOut(500);},2600);
    })
</script>
<?php $this->endBlock(); ?>