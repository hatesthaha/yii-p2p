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
    <style>
    html,body,.wapper{
        height: 100%;
    }
    .cy-rg1ph{
        border: none;
        border-bottom: 1px solid #c8c8c8;
        -webkit-border-radius:0;
        -moz-border-radius: 0;
        border-radius: 0;
        margin-bottom: 0;
        line-height: 34px;
        padding: 10px 0 10px 0;
    }
    .cy-rg1ph:last-child{
        border-bottom: none;
        margin-bottom: 0;
    }
    .cy-rg1ph input{
        float: right;
        margin-right: 3%;
        text-align: right;
    }
    .hxyzm{
        width: 30%;
        text-align: center;
        height: 31px;
        margin-bottom: 0;
        line-height: 32px;
        margin-right: 2%;
        padding: 0;
    }
    .cy-rg1ph label{
        font-weight: normal;
    }
    .xgmm {
        background: #fff;
        padding: 0 5%;
    }

</style>
<?php $this->endBlock(); ?>
<span class="spanti-c2"></span>
<div class="wapper bgd-f5f5f4">
    <!--content-->
    <div class="content">
        <br><br>
        <div class="cy-rg1">
            <span id="invitacode" class="spanti-flash">
               <?php
               if(\Yii::$app->getSession()->getFlash("errors")) {
                   echo \Yii::$app->getSession()->getFlash("errors")['info'];
               }
               ?>
            </span>
            <span class="spanti-c" id="spanti"></span>
            <form  method="post" id="signupform" action="<?php echo yii\helpers\Url::to(['site/dobindcard']);?>">
                <input type ="hidden" name="_csrf" value="<?php echo yii::$app->request->getCsrfToken();?>" />
                <input type="hidden" id="request_no" name="request_no" value="" />
                <input type="hidden" id="ticket" name="ticket" value="" />
                <div style="border-top: 1px solid #c8c8c8"></div>
                <div class="xgmm clearfix">
                    <div class="cy-rg1ph clearfix">
                        <label for="">姓名</label>
                        <input style="font-size:14px;" type="text" name="username" id="username" value="<?= '*'.mb_substr($model->real_name,1,5,'utf-8');  ?>" disabled/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <label for="">身份证号</label>
                        <input type="text" name="idcard" id="idcard" value="<?php $length=mb_strlen($model->idcard, 'utf-8'); if($length == 15) {$end = mb_substr($model->idcard, 11,4, 'utf-8') ; }else{$end = mb_substr($model->idcard, 14,4, 'utf-8');} echo mb_substr($model->idcard, 0,6, 'utf-8') .'********'.$end; ?>" disabled/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <label for="" style="width: 27%">绑定储蓄卡</label>
                        <input type="text" name="bankcard" id="bandcard" style="width:67%" placeholder="请输入本人储蓄卡号" required/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <label for="">手机号码</label>
                        <input type="tel" name="phone" id="phone" placeholder="请输入该卡的预留手机号" required/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <input type="text" name="code" id="code" placeholder="请输入短信验证码" required style="width: 55%;float: left;padding-left: 0;text-align: left"/>
                        <a class="border0 bgd-e44949 hxyzm c-ffffff fr send" id="codetext" >获取验证码</a>
                    </div>


                </div>
                <div style="border-top: 1px solid #c8c8c8"></div>
                <br>
                <div style="display:none;" id="bankcard">
                    <div class="diy_select">

                        <div class="select-text">

                            <input type="hidden" name="" id="b" class="diy_select_input">

                            <div class="diy_select_txt">--请选择银行--</div>

                            <div class="diy_select_btn"></div>

                        </div>

                        <ul class="diy_select_list">

                            

                        </ul>

                    </div>

                    <br>
                    <div class="info">
                        <div>
                        <span>选择地区：</span>
                        <select id="s_province" name="s_province"></select>  
                        <select id="s_city" name="s_city" ></select>  
                        <script src="<?= Yii::getAlias('@web') . '/' ?>rq-js/area.js"></script>
                        
                        <script type="text/javascript">_init_area();</script>
                        </div>
                        <div id="show"></div>
                    </div>
                </div>
                <br>
                <div class="bdyhkxyb w90">
                    <button type="button" id="submit1" class="border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10" style="width: 100%;">确认绑定银行卡</button><br><br>
                    <p class="f18"><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-wxts.png" alt=""  style="margin-right: 2%;display: block;float: left;margin-top: 3px;width: 3.5%"/>温馨提示</p><br>
                    <p class="f14 c-787878">为了您帐户安全，绑定储蓄卡需要完成一笔充值交易验
                        证身份。</p>
                    <br>
                </div>
            </form>
        </div>
    </div>
    <!--content end-->
<script type="text/javascript">
var Gid  = document.getElementById ;
var showArea = function(){
    Gid('show').innerHTML = "<h3>省" + Gid('s_province').value + " - 市" +    
    Gid('s_city').value + " - 县/区" + 
    Gid('s_county').value + "</h3>"
                            }
</script>
</div>
<?php $this->beginBlock('inline_scripts'); ?>
<?=Html::jsFile('@web/js/bootstrap.js')?>
<?=Html::jsFile('@web/js/bootbox.js')?>
<script src="<?= Yii::getAlias('@web') . '/' ?>rq-js/demo.js"></script>
<script>
    $(document).ready(function(){
        $(".wapper").css("min-height",$(window).height());
        setTimeout(function(){$('#flash').fadeOut(500);},2600);
    })
    var flag=[0,0,0];
    $('#bandcard').blur(function(){
        var bandcard = $('#bandcard').val();
        if(bandcard){
            $('#spanti').hide();
            $('#invitacode').hide();
            flag[1]=1;
        }else{
            $('#spanti').html('你没有填写银行卡');
            $('#spanti').show();
            flag[1]=0;
            setTimeout(function(){$('#spanti').fadeOut(500);},2600);
        }
    });
    $('#code').blur(function(){
        var code = $('#code').val();
        if(code.length == 6){
            $('#spanti').hide();
            $('#invitacode').hide();
            $('#submit1').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
            flag[2]=1;
        }else{
            $('#spanti').html('你填写的验证码不等于6位');
            $('#spanti').show();
            flag[2]=0;
            setTimeout(function(){$('#spanti').fadeOut(500);},2600);
        }
    });
    $('#phone').blur(function(){
        var phone = $('#phone').val();
        var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
        if(reg.test(phone)){
                    $('#spanti').hide();
                    $('#invitacode').hide();
                    $('#code').removeAttr("disabled");;
                    $('#codetext').attr('class','border0 bgd-e44949 hxyzm c-ffffff fr send');
                    $('#invitation_code').removeAttr("disabled");;
                    // $('#submit').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                    flag[0]=1;

        }
        if(!reg.test(phone)){
            $('#spanti').html('你输入的手机号码不正确');
            $('#spanti').show();
            $('#code').attr('disabled','disabled');
            $('#codetext').attr('class','border0 bgd-787878 hxyzm c-ffffff fr');
            $('#invitation_code').attr('disabled','disabled');
            $('#submit1').attr('class','border0 bgd-787878 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
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
                $('#codetext').attr('class','border0 bgd-787878 hxyzm c-ffffff fr');
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
        var idcard = $('#idcard').val();
        var bankcard = $('#bandcard').val();
        var username = $('#username').val();
        var card_code = $('#card_code').text();
        var province  = $('#s_province').val();
        var city  = $('#s_city').val();
        console.log(flag[0]);
        if(flag[0]==0){
            return false;
        }
        var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
        bootbox.dialog({
            message: '请耐心等待，正在处理中...'
        });
        if(reg.test(phone)) {
            $.post("<?php echo yii\helpers\Url::to(['site/doresbind']);?>",{'phone':phone,'idcard':idcard,'bankcard':bankcard,'username':username,'card_code':card_code,'city':city,'province':province,'_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'},function (data){
                bootbox.hideAll();
                var jsonobj = eval('('+data+')');
                console.log(jsonobj);
                if(jsonobj.errorNum == 0)
                {
                    console.log(jsonobj.data.ticket);
                    wait = 60;
                    time();
                    $("#ticket").val(jsonobj.data.ticket);
                    $("#request_no").val(jsonobj.data.request_no);
                    $(".spanti-c2").html("验证码发送成功，请查收短信");
                    $(".spanti-c2").show();
                    setTimeout(function(){$(".spanti-c2").fadeOut(500);},2600);
                }
                else if(jsonobj.errorNum == 1)
                {
                    $(".spanti-c2").html(jsonobj['errorMsg']);
                    $(".spanti-c2").show();
                    setTimeout(function(){$(".spanti-c2").fadeOut(500);},2600);
                }
                else if(jsonobj.errorNum == 3)
                {
                    $('#bankcard').show();
                    $.each(jsonobj.data.banklist,function(i,val){
                             $(".diy_select_list").append('<li><img src="'+val.bank_logo+'" alt="">'+val.bank_name+'<span id="card_code" style="display:none;">'+val.bank_code+'</span></li>');
                      });
                }
                else
                {
                    $(".spanti-c2").html(jsonobj['errorMsg']);
                    $(".spanti-c2").show();
                    setTimeout(function(){$(".spanti-c2").fadeOut(500);},2600);
                }
            }).error(function() { 
             bootbox.hideAll();
             $(".spanti-c2").html('已经发送验证码，请勿重复发送');
             $(".spanti-c2").show();
             setTimeout(function(){$(".spanti-c2").fadeOut(500);},2600);
         });
        }else{

            $('#spanti').html('你输入的手机号码不正确');
            $('#spanti').show();
            $('#code').attr('disabled','disabled');
            $('#codetext').attr('class','border0 bgd-787878 hxyzm c-ffffff fr');
            $('#invitation_code').attr('disabled','disabled');
            $('#submit1').attr('class','border0 bgd-787878 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
            setTimeout(function(){$('#spanti').fadeOut(500);},2600);
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

        if(res){
            $('#signupform').submit();
        }else{
            console.log(123);
        }
    });
</script>
<?php $this->endBlock(); ?>

