<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = yii::$app->request->baseUrl.'/../../backend/web/upload/';
?>
<?=Html::cssFile('@web/css/bootstrap.css')?>
<?php $this->beginBlock('inline_styles'); ?>
<style>
    html,body,.wapper{
        height: 100%;
        background: #f7f7f7;
    }
    .cy-rg1ph input:-ms-input-placeholder,
    .cy-rg1ph textarea:-ms-input-placeholder {
        color: #a0aab4;
    }
   .cy-rg1ph input::-webkit-input-placeholder,
    .cy-rg1ph textarea::-webkit-input-placeholder {
        color: #a0aab4;
    }
    .xgmm{
        background: #fff;
        border: 1px solid #c8c8c8;
    }
    .cy-rg1ph{
        border: none;
        border-bottom: 1px solid #c3c3c3;
        -webkit-border-radius:0;
        -moz-border-radius: 0;
        border-radius: 0;
        margin-bottom: 0;
        line-height: 4.3rem;
        padding: 6px 1.1rem;
    }
    .cy-rg1ph:last-child{
        margin-bottom: 0;
    }
    .cy-rg1ph input{
        float: right;
        margin-right: 3%;
    }
    .hxyzm{
        width: 30%;
        text-align: center;
        height: 30px;
        margin-bottom: 0;
        line-height: 30px;
        margin-right: 2%;
        padding: 0;
    }
    .cy-rg1ph label{
        color: #969696;
        font-weight: normal;
    }
    .cy-rg1 .focus{
        border-top: none;
        border-left: none;
        border-right: none;
        border-color: #c8c8c8;

    }
</style>
<?php $this->endBlock(); ?>
<span class="spanti-c2"></span>
<div class="wapper bgd-f5f5f4">
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
            <form  method="post" id="signupform" action="<?php echo yii\helpers\Url::to(['site/forgotfinish']);?>">
                <input type ="hidden" name="_csrf" value="<?php echo yii::$app->request->getCsrfToken();?>" />
                <div class="xgmm clearfix">
                    <div class="cy-rg1ph clearfix">
                        <input type="text" name="real_name" id="real_name" maxlength="11" placeholder="真实姓名（未实名认证可为空）" required style="width: 100%; font-size:14px;"/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <input type="text" name="idcard" id="idcard" maxlength="18" placeholder="身份证号（未实名认证可为空）" required style="width: 100%"/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <input type="tel" name="phone" id="phone" maxlength="11" placeholder="请输入绑定手机号码" required style="width: 100%"/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <input type="tel" name="code" id="code" placeholder="6位短信验证码" required style="width: 55%;float: left;padding-left: 0"/>
                        <a id="codetext" class="border0 bgd-e44949 hxyzm c-ffffff fr send">获取验证码</a>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <input type="text" name="password" id="password" maxlength="16" placeholder="新密码" required style="width: 100%;font-size:14px;"/>
                    </div>
                    <div class="cy-rg1ph clearfix" style="border:none;">
                        <input type="text" name="repassword" id="repassword" maxlength="16" placeholder="确认密码" required style="width: 100%;font-size:14px;"/>
                    </div>
                </div>
                <br><br>
                <button type="button" id="submit1" class="border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10" style="width: 100%; font-size:16px;">确认修改</button><br><br>
                <p class="text-center">如遇问题，点击按钮拨打免费400热线解决</p>
                <br><br>
                <p class="text-center"><span class="c-e44949 f14" style="padding: 10px 13%;background:#fff;"><a style="color:#5c5cff; border-bottom: 1px solid #5c5cff;" href="tel:4006985185">4006-985-185</a></span></p>

            </form>
        </div>
        <br><br><br><br><br><br><br><br><br>
    </div>
    <!--content end-->

</div>
<?php $this->beginBlock('inline_scripts'); ?>
<?=Html::jsFile('@web/js/bootstrap.js')?>
<?=Html::jsFile('@web/js/bootbox.js')?>
<script>
$(document).ready(function(){
    $(".wapper").css("min-height",$(window).height());
    setTimeout(function(){$('#flash').fadeOut(500);},2600);
})
    var flag=[0,0,0,0];
    $('#phone').blur( function() {

        var phone = $('#phone').val();
        var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
        if(reg.test(phone)){
            $.post("<?php echo yii\helpers\Url::to(['phone/smsphone']);?>",{'CellPhone':phone,'_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'},function (data){
                if(!data){
                    $('#spanti').hide();
                    $('#invitacode').hide();
                    $('#code').removeAttr("disabled");;
                    $('#codetext').attr('class','border0 bgd-e44949 hxyzm c-ffffff fr send');
                    $('#invitation_code').removeAttr("disabled");;
                    // $('#submit').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                    flag[0]=1;
                }
                else
                {
                    $('#spanti').html('手机号码未注册');
                    $('#spanti').show();
                    $('#code').attr('disabled','disabled');
                    $('#codetext').attr('class','border0 bgd-e44949 hxyzm c-ffffff fr');
                    $('#invitation_code').attr('disabled','disabled');
                    $('#submit1').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                    flag[0]=0;
                    setTimeout(function(){$('#spanti').fadeOut(500);},2600);

                }
            });
        }
        if(!reg.test(phone)){
            $('#spanti').html('请输入正确手机号');
            $('#spanti').show();
            $('#code').attr('disabled','disabled');
            $('#codetext').attr('class','border0 bgd-e44949 hxyzm c-ffffff fr');
            $('#invitation_code').attr('disabled','disabled');
            $('#submit1').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
            flag[0]=0;
            setTimeout(function(){$('#spanti').fadeOut(500);},2600);
        }

    });
    var wait=60;
    function time() {

        if (wait == 0) {
            $('#codetext').attr('class','border0 bgd-e44949 hxyzm c-ffffff fr send');
            $('#codetext').html('发送验证码');

        } else {
            setTimeout(function() {
                $('#codetext').attr('class','border0 bgd-e44949 hxyzm c-ffffff fr');
                $('#codetext').html(wait);
            },1000);
            wait--;
            setTimeout(function() {
                time();

            },1000);
        }
    }
    $('.send').click(function(){
        var phone = $('#phone').val();
        if(flag[0]==0){
            return false;
        }
        var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
        bootbox.dialog({
            message: '请耐心等待，正在处理中...'
        });
        if(reg.test(phone)) {
            $.post("<?php echo yii\helpers\Url::to(['phone/sendcode']);?>",{'CellPhone':phone,'_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'},function (data){
                bootbox.hideAll();
                if(data == '验证码已发送，请注意查收。')
                {
                    wait=60;
                    time();
                    $(".spanti-c2").html(data);
                    $(".spanti-c2").show();
                    setTimeout(function(){$(".spanti-c2").fadeOut(500);},2600);
                }
                else
                {
                    $(".spanti-c2").html(data);
                    $(".spanti-c2").show();
                    setTimeout(function(){$(".spanti-c2").fadeOut(500);},2600);
                }
            });
        }else{
            bootbox.hideAll();
            $('#spanti').html('请输入正确手机号');
            $('#spanti').show();
            $('#code').attr('disabled','disabled');
            $('#codetext').attr('class','border0 bgd-e44949 hxyzm c-ffffff fr');
            $('#invitation_code').attr('disabled','disabled');
            $('#submit1').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
            setTimeout(function(){$('#spanti').fadeOut(500);},2600);
        }
    });
    $('#code').bind('input propertychange', function() {
        var phone = $('#phone').val();
        var code = $('#code').val();
        if(code.length==6){
            flag[1]=1;
            $('#spanti').hide();
            $('#invitacode').hide();
            $('#submit1').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
//            $.post("<?php //echo yii\helpers\Url::to(['phone/codecheck']);?>//",{'CellPhone':phone,'code':code,'_csrf':'<?php //echo yii::$app->request->getCsrfToken();?>//'},function (data){
//                if(!data)
//                {
//                    $('#spanti').html('验证码填写不正确');
//                    $('#spanti').show();
//                    flag[1]=0;
//                }else{
//                    $('#spanti').hide();
//                    $('#invitacode').hide();
//                    flag[1]=1;
//                    $('#submit').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
//                }
//            });

        }else{
            $('#spanti').html('你没有填写验证码');
            $('#spanti').show();
            flag[1]=0;
            setTimeout(function(){$('#spanti').fadeOut(500);},2600);
        }
    });
      $('#password').blur(function(){
        var password = $('#password').val();
        var reg_pass = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/;
        if(reg_pass.test(password)) {
            flag[3] = 1;
            $('#spanti').hide();
        }else{
            $('#spanti').html('6-16位数字+字母组合');
            $('#spanti').show();
            flag[3]=0;
            setTimeout(function(){$('#spanti').fadeOut(500);},2600);
        }
    });
    $('#repassword').blur(function(){
        var repassword = $('#repassword').val();
        var password = $('#password').val();
        var reg_pass = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/;
        if(repassword != password){
            $('#spanti').html('两次输入密码不一致');
            $('#spanti').show();
            flag[4]=0;
            setTimeout(function(){$('#spanti').fadeOut(500);},2600);
        }else{
            flag[4] = 1;
            $('#submit1').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
            $('#spanti').hide();
        }
    });
    $('#submit1').click(function(){alert(3);
        var phone = $('#phone').val();
        var code = $('#code').val();
        var res = true;
        for(var i=0;i<flag.length;i++){
            if(flag[i]==0){
                res=false;  break;
            }
        }
        if(res){
            $('#signupform').submit();
        }else{
            console.log(123);
        }
    });
</script>
<?php $this->endBlock(); ?>