<?php
use yii\helpers\Html;
use yii\helpers\Url;
use www\controllers\SmsController;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = yii::$app->request->baseUrl.'/../../backend/web/upload/';
?>
<?=Html::cssFile('@web/css/bootstrap.css')?>
<?php $this->beginBlock('inline_styles'); ?>
<style>
    input,a{
        outline: none;
    }
    html,body,.wapper{
        height: 100%;
        background: #fff;
    }
    .header-top{
        background: #fff;
    }
    .cy-rg1{
        padding: 0 1.5rem;
    }
    .cy-rg1ph{
        margin-bottom: 0;
        border: none;
        border-radius: 0;
        display: -webkit-box;
        display: -moz-box;
        display: -o-box;
        display: box;
        padding: 6px 2px 6px 2.5%;
        border-bottom: 1px solid #c8c8c8;
    }
    .WH-2 input , .WH-4{
        display: -webkit-box;
        display: -moz-box;
        display: -o-box;
        display: box;
        -webkit-box-flex: 1;
        -moz-box-flex: 1;
        -o-box-flex: 1;
        box-flex: 1;
        padding-left: 0;
    }
    .WH-1{
        margin-bottom: 3.4rem;
    }
    .cy-rg1ph label{
        width: 75px;
        letter-spacing:1px;
        float: none;
        color: #a0aab4;
        font-weight: normal;
        font-size: 14px;
        line-height: 30px;
    }
    .hxyzm{
        padding: 7px  1%;
        margin-right: 3%;
        float: none;
    }
    .cy-rg1ph input:-ms-input-placeholder,
    .cy-rg1ph textarea:-ms-input-placeholder {
        color: #a0aab4;
        font-size: 14px;
    }
   .cy-rg1ph input::-webkit-input-placeholder,
    .cy-rg1ph textarea::-webkit-input-placeholder {
        color: #a0aab4;
        font-size: 14px;
    }
    .WH-5{
        width: 100%;
        display: -webkit-box;
        display: -moz-box;
        display: -o-box;
        display: box;
    }
    .WH-5 a{
        width: 80px;
        font-size: 15px;
    }
    .WH-5 input{
        display: -webkit-box;
        display: -moz-box;
        display: -o-box;
        display: box;
        -webkit-box-flex: 1;
        -moz-box-flex: 1;
        -o-box-flex: 1;
        box-flex: 1;
        padding-left: 0;
        font-size: 14px;
    }
    .cy-rg1 .focus{
        border-top: none;
        border-left: none;
        border-right: none;
        border-color: #c7ced9;
    }
</style>
<?php $this->endBlock(); ?>
<div class="wapper">
    <!--header-->
    <div class="header-top">
        <div class="header-toplogo w83 text-center" style="padding:3.7rem 0 2.8rem;">
            <img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-logo.png" alt="" width="40%"/>
        </div>
    </div>
    <!--header end-->
    <!--content-->
    <div class="content">
        <div class="cy-rg1">
            <span id="invitacode" class="spanti-flash">
               <?php
               if(\Yii::$app->getSession()->getFlash("errors")) {
                   echo \Yii::$app->getSession()->getFlash("errors")['info'];
               }
               ?>
            </span>
            <span class="spanti-flash" id="spanti"></span>
            <span class="spanti-c"></span>
            <form  method="post" id="signupform" action="<?php echo yii\helpers\Url::to(['site/step1']);?>">
            <div class="WH-1">
                <input type ="hidden" name="_csrf" value="<?php echo yii::$app->request->getCsrfToken();?>" />
                <input type="hidden" value="<?php if(isset($code)) {echo $code;}?>" name="url_code" />
                <div class="cy-rg1ph WH-2">
                    <label for="">手机号：</label>
                    <input type="tel" maxlength="11" placeholder="11位手机号" name="phone" id="phone" required/>
                </div>
                <div class="cy-rg1ph WH-2">
                    <label for="">密  码：</label>
                    <input type="password" maxlength="16" placeholder="设置登录密码，6-16位字符" name="password" id="password" required/>
                </div>
                <div class="cy-rg1ph WH-2">
                    <label for="">确认密码：</label>
                    <input type="password" maxlength="16" placeholder="确认登录密码，6-16位字符" name="password_repeat" id="password_repeat" required/>
                </div>
                <div class="cy-rg1ph WH-3">
                    <label for="">验证码：</label>
                    <div class="WH-4">
                        <div class="WH-5">
                            <input type="text" name="code" placeholder="6位短信验证码" id="code" required/>
                            <a class="border0 bgd-e44949 hxyzm c-ffffff send" style="margin:0;" href="javascript:;" id="codetext">获取验证码</a>                        
                        </div>                    
                    </div>
                </div>
                <div class="cy-rg1ph WH-2">
                    <label for="">邀请码：</label>
                    <input type="text" name="invitation_code" id="invitation_code" placeholder="邀请码" required/>
                </div>
                </div>
                <p style="text-align:right; margin-top:15px;">已有账号?请<a href="<?php echo yii\helpers\Url::to(['site/signin']);?>" class="c-e44949">登录</a></p>
                <p style="text-align:center; margin:15px 0 25px;">我已经阅读<a href="<?php echo yii\helpers\Url::to(['xieyi/index']); ?>" class="c-e44949">《网站注册及服务协议》</a></p>
                <button type="button" id="submit1" class="border0 bgd-e44949 c-ffffff  m-bottom-10" style="width: 100%;padding: 12px 0;">下一步</button>
            
            </form>
            
        </div>
    </div>
    <!--content end-->

</div>
<?php $this->beginBlock('inline_scripts'); ?>
<?=Html::jsFile('@web/js/bootstrap.js')?>
<?=Html::jsFile('@web/js/bootbox.js')?>
<script>
$(document).ready(function(){
    setTimeout(function(){$('#flash').fadeOut(500);},2600);
})
        var flag=[0,0,0,0];
        flag[2]=1;
        $('#phone').blur( function() {
            var phone = $('#phone').val();
            var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
            if(reg.test(phone)){
                $.post("<?php echo yii\helpers\Url::to(['phone/smsphone']);?>",{'CellPhone':phone,'_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'},function (data){
                    if(!data){
                        $('#spanti').html('此手机号已注册，请登录');
                        $('#spanti').show();
                        $('#code').attr('disabled','disabled');
                        $('#codetext').attr('class','border0 bgd-e44949 hxyzm c-ffffff fr');
                        $('#invitation_code').attr('disabled','disabled');
                        $('#submit1').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                        flag[0]=0;
                        setTimeout(function(){$('#spanti').fadeOut(500);},2600);
                    }
                    else
                    {
                        $('#spanti').hide();
                        $('#invitacode').hide();
                        $('#code').removeAttr("disabled");;
                        $('#codetext').attr('class','border0 bgd-e44949 hxyzm c-ffffff fr send');
                        $('#invitation_code').removeAttr("disabled");;
                       // $('#submit').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                        flag[0]=1;
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
                    $('#codetext').attr('class','border0 bgd-e44949 hxyzm c-ffffff fr send');
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
            console.log(flag[0]);
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
                        $(".spanti-c").html(data);
                        $(".spanti-c").show();
                        setTimeout(function(){$(".spanti-c").fadeOut(500);},2600);
                    }
                    else
                    {
                        $(".spanti-c").html(data);
                        $(".spanti-c").show();
                        setTimeout(function(){$(".spanti-c").fadeOut(500);},2600);
                    }
                }).error(function() {  
                    bootbox.hideAll();                        
                    $(".spanti-c").html(data);
                    $(".spanti-c").show();
                    setTimeout(function(){$(".spanti-c").fadeOut(500);},2600);
 });
            }else{
                bootbox.hideAll();
                $('#spanti').html('你输入的手机号码不正确');
                $('#spanti').show();
                $('#code').attr('disabled','disabled');
                $('#codetext').attr('class','border0 bgd-e44949 hxyzm c-ffffff fr');
                $('#invitation_code').attr('disabled','disabled');
                $('#submit1').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                setTimeout(function(){$('#spanti').fadeOut(500);},2600);
            }
        });
        $('#code').blur(function(){
            var phone = $('#phone').val();
            var code = $('#code').val();
            if(code){
                flag[1]=1;
//                $.post("<?php //echo yii\helpers\Url::to(['phone/codecheck']);?>//",{'CellPhone':phone,'code':code,'_csrf':'<?php //echo yii::$app->request->getCsrfToken();?>//'},function (data){
//                    if(!data)
//                    {
//                        $('#spanti').html('验证码填写不正确');
//                        $('#spanti').show();
//                        flag[1]=0;
//                    }else{
//                        $('#spanti').hide();
//                        $('#invitacode').hide();
//                        flag[1]=1;
//                    }
//                });
            }else{
                flag[1]=0;
//                $('#spanti').html('你没有填写验证码');
//                $('#spanti').show();
//                flag[1]=0;
            }
        });
        $('#invitation_code').blur(function(){
            var icode = $('#invitation_code').val();
            if(icode){
                $.post
                (
                    '<?php echo yii\helpers\Url::to(['phone/invitation_code']);?>',
                    {'icode':icode,'_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'},
                    function(data){
                        if(data == '验证失败')
                        {
                            $('#spanti').html('邀请码输入不正确');
                            $('#spanti').show();
                            $('#submit1').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                            flag[2]=1;
                            setTimeout(function(){$('#spanti').fadeOut(500);},2600);
                        }
                        else if(data == '验证通过')
                        {
                            $('#spanti').hide();
                            $('#invitacode').hide();
                            $('#submit1').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                            flag[2]=1;
                        }
                    }
                );
            }else{
                $('#spanti').html('你没有填写邀请码');
                $('#submit1').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                $('#spanti').show();
                flag[2]=0;
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
        $('#password_repeat').blur(function(){
            var repassword = $('#password_repeat').val();
            var password = $('#password').val();
            var reg_pass = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/;
            if(repassword != password){
                $('#spanti').html('两次输入密码不一致');
                $('#spanti').show();
                flag[3]=0;
                setTimeout(function(){$('#spanti').fadeOut(500);},2600);
            }else{
                flag[3] = 1;
                $('#submit1').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                $('#spanti').hide();
            }
        });

        $('#submit1').click(function(){
            var phone = $('#phone').val();
            var code = $('#code').val();
            var res = true;
            for(var i=0;i<flag.length;i++){

                if(flag[i]==0){
                    res=false;  break;
                }
            }
            console.log(res);
            if(res){
                $('#signupform').submit();
            }else{

            }
        });
    //$("#phone").val()
</script>
<?php $this->endBlock(); ?>