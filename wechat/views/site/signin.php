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
        background: #fff;
    }
    .WH-1{
        border-radius: 5px;
        margin-bottom: 3.4rem;
    }
    .cy-rg1ph{
        margin-bottom: 0;
        border: none;
        border-bottom: 1px solid #c8c8c8;
        border-radius: 0;
        display: -webkit-box;
        display: -moz-box;
        display: -o-box;
        display: box;
    }
    .cy-rg1ph input{
        display: -webkit-box;
        display: -moz-box;
        display: -o-box;
        display: box;
        -webkit-box-flex: 1;
        -moz-box-flex: 1;
        -o-box-flex: 1;
        box-flex: 1;
        margin-right: 4%;
    }
    .cy-rg1ph input:-ms-input-placeholder,
    .cy-rg1ph textarea:-ms-input-placeholder {
        color: #a0aab4;
        font-size: 16px;
    }
   .cy-rg1ph input::-webkit-input-placeholder,
    .cy-rg1ph textarea::-webkit-input-placeholder {
        color: #a0aab4;
        font-size: 16px;
    }
    .cy-rg1ph label{
        float: none;
        color: #c8c8c8;
        font-weight: normal;
        display: block;
        width: 5%;
    }
    .header-top{
        background: #fff;
    }
    .cy-rg1{
        padding: 0 1.5rem;
    }
    .cy-rg1 .focus{
        border-top: none;
        border-left: none;
        border-right: none;
        border-color: #c8c8c8;
    }
    .WH-6{
        height: 21px;
        line-height: 21px;
        position: relative;
        margin-bottom:2.9rem;
    }
    .WH-7{
        display: block;
        height: 1px;
        width: 100%;
        background: #c8c8c8;
        position: relative;
        top: 10px;
        left: 0;
    }
    .WH-8{
        display: block;
        position: absolute;
        top: 0;
        left: 50%;
        width: 50px;
        background: #fff;
        text-align: center;
        margin-left: -25px;
        z-index: 15;
        font-size: 18px;
        color: #a0aab4;
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
            <span class="spanti-c" id="spanti"></span>

                <input type ="hidden" name="_csrf" value="<?php echo yii::$app->request->getCsrfToken();?>" />
                <div class="WH-1">
                    <div class="cy-rg1ph clearfix">
                        <label for=""><img src="<?= Yii::getAlias('@web') . '/' ?>images/ic_user.png" alt=""  height="50%" class="middle block fl"style="margin-top: 7px;margin-right: 5%;"/></label>
                        <input type="text" name="username" id="username" maxlength="11" value="" placeholder="手机号" required/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <label for=""><img src="<?= Yii::getAlias('@web') . '/' ?>images/ic_lock.png" alt=""  height="50%" class="middle block fl"style="margin-top: 7px;margin-right: 7%;" /></label>
                        <input type="password" name="password" id="password" value="" placeholder="密码" required/>
                    </div>
                </div>
                <button type="submit" id="submit1" class="border0 bgd-e44949 c-ffffff" style="line-height:3.7rem;width: 100%;border-radius:5px;margin-bottom:1.7rem;">登录</button>
                <p style="text-align: right;margin-bottom:2.3rem;"><span><a class="c-e44949" href="<?php echo Url::to(['site/forgot']);?>">忘记密码?</a></span></p>
                <p class="WH-6"><span class="WH-8">或</span><span class="WH-7"></span></p>
                <p  style="text-align: center; font-size:18px;margin-bottom:1.2rem;"><span><a class="c-e44949" href="<?php echo Url::to(['site/signup']);?>" >新用户注册</a></span></p>


        </div>
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
            $('#spanti').hide();
        }else{
            $('#spanti').html('密码不能为空');
            $('#spanti').show();
            flag[1]=0;
            setTimeout(function(){$('#spanti').fadeOut(500);},2600);
        }
    });
    $('#submit1').click(function(){
        var res = true;
        for(var i=0;i<flag.length;i++){
            if(flag[i]==0){
                res=false;  break;
            }
        }

        if(res){
            var username = $('#username').val();
            var password = $('#password').val();
            bootbox.dialog({
                message: '请耐心等待，正在登陆中...'
            });
            // $('#submit1').attr('class','border0 bgd-787878 c-ffffff m-bottom-10');
            $.post("<?php echo yii\helpers\Url::to(['site/dosignin']);?>",{'username':username,'password':password,'_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'},function (data){
                bootbox.hideAll();
                // $('#submit1').attr('class','border0 bgd-e44949 c-ffffff m-bottom-10');
                if(data == '登陆成功')
                {

                    console.log(data);
                    location.href="<?php echo yii\helpers\Url::to(['site/member']);?>";
                }
                else
                {
                    $('#spanti').html(data);
                    $('#spanti').show();
                    setTimeout(function(){$('#spanti').fadeOut(500);},2600);
                }
            });
        }else{

        }
    });
</script>
<?php $this->endBlock(); ?>
