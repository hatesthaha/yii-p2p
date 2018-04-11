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
        width: 26%;
        color: #a3adb7;
        font-weight: normal;
    }
    .cy-rg1ph input{
        padding-left: 0;
    }
    #signupform{
   
    }
    .cy-rg1ph{
        margin-bottom: 0px;
        border-radius: 0px;
        border: 0;
        border-bottom: 1px solid #d8d8d8;
        background-color: #fff;
        padding: 8px 0 8px 4.5%;
    }
</style>
<?php $this->endBlock(); ?>

<div class="wapper bgd-f5f5f4">
    <!--header-->
<!--     <div class="header-top"><br><br><br>
        <div class="header-topList w83">
            <span><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-rgzc.png" alt="" width="25%"/></span>
            <span><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-rgyz2.png" alt="" width="25%"/></span>
            <span><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-rgwc2.png" alt="" width="25%"/></span>
        </div>
        <br>
        <br>

        <p class="text-center f16 c-ffffff">HI~欢迎注册理财王<br>
            请完善实名认证，提高您的安全级别
        </p>
        <br>
        <br>
        <br>
    </div> -->
    <!--header end-->
    <!--content-->

    <div class="content">
        <br><br>
        <div class="cy-rg1 w90">
            <span id="flash" class="spanti-flash">
               <?php
               if(\Yii::$app->getSession()->getFlash("errors")) {
                   echo \Yii::$app->getSession()->getFlash("errors")['info'];
               }
               ?>
            </span>
            <span class="spanti-c" id="spanti"></span>
            <form  method="post" id="signupform" action="<?php echo yii\helpers\Url::to(['site/reg']);?>">
                <input type ="hidden" name="_csrf" value="<?php echo yii::$app->request->getCsrfToken();?>" />
                <div class="cy-rg1ph">
                    <label for="">真实姓名：</label>
                    <input style="font-size:14px;" type="text" id="realname" name="realname" required/>
                </div>
                <div class="cy-rg1ph" style="border-bottom:none;">
                    <label for="">身份证号：</label>
                    <input type="text" id="cardno" name="cardno" required/>
                </div>

                <br><br>
                <button type="button" id="submit1" class="border0 bgd-5497cb c-ffffff  m-bottom-10" style="width: 100%; padding: 12px 0;">确定</button>
            </form>
        </div>
        <br><br><br><br><br><br><br><br><br>
    </div>
    <!--content end-->
</div>
<?php $this->beginBlock('inline_scripts'); ?>
<script>
$(document).ready(function(){
    $(".wapper").css("min-height",$(window).height());
    setTimeout(function(){$('#flash').fadeOut(500);},2600);
})
    var flag=[0,0];
    $('#realname').blur(function() {
        var realname = $('#realname').val();
        if(realname){
            flag[0] = 1;
            $('#submit1').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
            $('#spanti').hide();
            $('#invitacode').hide();
        }else{
            flag[0] = 0;
            $('#spanti').html('真实姓名没有填写');
            $('#spanti').show();
            setTimeout(function(){$('#spanti').fadeOut(500);},2600);
        }
    });
    $('#cardno').blur(function() {
        var cardno = $('#cardno').val();
        var reg=/^[1-9]{1}[0-9]{14}$|^[1-9]{1}[0-9]{16}([0-9]|[xX])$/;
        console.log(reg.test(cardno));
        if(!reg.test(cardno))
        {

            $('#spanti').show();
            $('#spanti').html('身份证格式不正确');
            flag[1] = 0;
            setTimeout(function(){$('#spanti').fadeOut(500);},2600);
        }else{ 
            flag[1] = 1;
            $('#submit1').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
            $('#spanti').hide();
            $('#invitacode').hide();
        }
    });
    $('#submit1').click(function(){
        var realname = $('#realname').val();
        var cardno = $('#cardno').val();
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
</script>
<?php $this->endBlock(); ?>