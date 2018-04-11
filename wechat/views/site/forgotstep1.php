<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = yii::$app->request->baseUrl.'/../../backend/web/upload/';
?>
<?php $this->beginBlock('inline_styles'); ?>
<style>
    html,body,.wapper{
        height: 100%;
    }
    .xgmm{
        border: 1px solid #c8c8c8;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
    }
    .cy-rg1ph{
        border: none;
        border-bottom: 1px solid #c8c8c8;
        -webkit-border-radius:0;
        -moz-border-radius: 0;
        border-radius: 0;
        margin-bottom: 0;
        line-height: 34px;
        padding: 10px 0 10px 4.5%;
    }
    .cy-rg1ph:last-child{
        margin-bottom: 0;
    }
    .cy-rg1ph input{
        float: right;
        margin-right: 3%;
        width: 97%;
    }

</style>
<?php $this->endBlock(); ?>
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
                        <input type="password" id="password" name="password" placeholder="新密码" required/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <input type="password" id="repassword" name="repassword" placeholder="确认密码" required/>
                    </div>

                </div>
                <br><br>
                <button type="button" id="submit1" class="border0 bgd-787878 c-ffffff p-bottom-10 p-top-10 m-bottom-10" style="width: 100%;">下一步</button><br><br>
                <p class="text-center">如遇问题，点击按钮拨打免费400热线解决</p>
                <br><br>
                <p class="text-center"><span class="c-e44949 f14" style="border: 1px solid #c8c8c8;padding: 10px 13%"><a href="tel:4006985185">4006-985-185</a></span></p>

            </form>
        </div>
        <br><br><br><br><br><br><br><br><br>
    </div>
    <!--content end-->

</div>
<?php $this->beginBlock('inline_scripts'); ?>
<script>
$(document).ready(function(){
    setTimeout(function(){$('#flash').fadeOut(500);},2600);
})
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
            setTimeout(function(){$('#spanti').fadeOut(500);},2600);
        }
    });
    $('#repassword').bind('input propertychange', function() {
        var repassword = $('#repassword').val();
        var password = $('#password').val();
        var reg_pass = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/;
        if(repassword != password){
            $('#spanti').html('两次输入密码不一致');
            $('#spanti').show();
            flag[1]=0;
            setTimeout(function(){$('#spanti').fadeOut(500);},2600);
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
            console.log(123);
        }
    });
</script>
<?php $this->endBlock(); ?>