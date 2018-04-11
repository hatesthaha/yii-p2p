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
    .cy-rg1ph input{
        width: 88%;
        float: right;
        margin-right: 4%;
        color: #c8c8c8;
    }
    .cy-rg1ph label{
        color: #c8c8c8;
        font-weight: normal;
        float: left;
        display: block;
        width: 5%;

    }

</style>
<?php $this->endBlock(); ?>
<div class="wapper bgd-f5f5f4">
    <!--header-->
    <div class="header-top"><br><br>
        <div class="header-toplogo w83 text-center">
            <img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-rglogo.png" alt="" width="50%"/>
        </div>
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
            <form  method="post" id="signupform" action="<?php echo yii\helpers\Url::to(['wechat/login']);?>">
            <input type ="hidden" name="_csrf" value="<?php echo yii::$app->request->getCsrfToken();?>" />
            <input type="hidden" name="open_id" value="<?= yii::$app->request->get('open_id') ?>"/>
            <div class="cy-rg1ph clearfix">
                <label for=""><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-rgicon.png" alt=""  height="50%" class="middle block fl"style="margin-top: 7px;margin-right: 5%;"/></label>
                <input type="text" name="username" id="username" value="手机号" required/>
            </div>
            <div class="cy-rg1ph clearfix">
                <label for=""><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-rgicon2.png" alt=""  height="50%" class="middle block fl"style="margin-top: 7px;margin-right: 7%;" /></label>
                <input type="password" name="password" id="password" value="" placeholder="密码" required/>
            </div>
            <br>
            <button type="submit" id="submit" class="border0 bgd-787878 c-ffffff p-bottom-10 p-top-10 m-bottom-10" style="width: 100%;">开启免登录</button>

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
    var flag=[0,0];
    $('#username').blur(function(){
        var username = $('#username').val();
        if(username) {
            flag[0] = 1;
            $('#submit').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
            $('#spanti').hide();
        }else{
            $('#spanti').html('用户名不能为空');
            $('#spanti').show();
            flag[0]=0;
            setTimeout(function(){$('#spanti').fadeOut(500);},2600);
        }
    });
    $('#password').blur(function(){
        var password = $('#password').val();
        if(password) {
            flag[1] = 1;
            $('#submit').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
            $('#spanti').hide();
        }else{
            $('#spanti').html('密码不能为空');
            $('#spanti').show();
            flag[1]=0;
            setTimeout(function(){$('#spanti').fadeOut(500);},2600);
        }
    });
    $('#submit').click(function(){
        var res = true;
        for(var i=0;i<flag.length;i++){
            if(flag[i]==0){
                res=false;  break;
            }
        }

        if(res){
            var username = $('#username').val();
            var password = $('#password').val();
            $('#signupform').submit();
//            $.post("<?php //echo yii\helpers\Url::to(['wechat/dologin']);?>//",{'username':username,'password':password,'_csrf':'<?php //echo yii::$app->request->getCsrfToken();?>//'},function (data){
//                if(data == '登陆成功')
//                {
//                    alert('免登录功能成功开启');
//                    //console.log(data);
//                    location.href="<?php //echo yii\helpers\Url::to(['site/member']);?>//";
//                }
//                else
//                {
//                    $('#spanti').html(data);
//                    $('#spanti').show();
//                }
//            });
        }else{
            console.log(123);
        }
    });
</script>
<?php $this->endBlock(); ?>
