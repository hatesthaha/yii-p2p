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
    }
    .xgmm{
        border: 1px solid #c8c8c8;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        background: #fff;
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
        width: 57%;
        text-align: right;
        color: #787878;
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
        width: 37%;
    }
</style>
<?php $this->endBlock(); ?>
<span class="spanti-c2"></span>
<div class="wapper bgd-f5f5f4">
    <!--content-->
    <div class="content">
        <br><br>
        <div class="cy-rg1 w90">
            <span class="spanti-c" id="spanti"></span>
            <input type="hidden" id="out_trade_no" value=""/>
            <input type="hidden" id="ticket" value=""/>
            <input type="hidden" name="_csrf" value="<?php echo yii::$app->request->getCsrfToken(); ?>"/>
                <div class="xgmm clearfix">
                    <div class="cy-rg1ph clearfix">
                        <label for="">手机号</label>
                        <input type="text"  value="<?= mb_substr($phone,0,3).'*******'. mb_substr($phone,7,4,'utf-8')?>" disabled required/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <label for="">银行卡 </label>
                        <input type="text" name="bankcard" name="bankcard" value="<?php echo substr($info->bank_card, 0, 4) . '************' . substr($info->bank_card, strlen($info->bank_card) - 3, 3); ?>" disabled required/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <label for="">账户余额（元）</label>
                        <input type="text"  value="<?= $info->balance ?>" disabled required/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <label for="">充值金额（元）</label>
                        <input type="text" name="money" id="money" placeholder="充值的金额为整数" required/>
                    </div>
                    <div class="cy-rg1ph clearfix" style="border:none;">
                        <input type="text" name="code" id="code" placeholder="输入手机验证码" required style="width: 55%;float: left;padding-left: 0;text-align: left"/>
                        <a class="border0 bgd-e44949 hxyzm c-ffffff send" id="codetext">获取验证码</a>
                    </div>


                </div>
                <br><br>
                <button type="button" id="submit1" class="border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10" style="width: 100%;">确认充值</button><br><br>
                <p class="f18"><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-wxts.png" alt=""  style="margin-right: 2%;display: block;float: left;margin-top: 3px;width: 3.5%"/>温馨提示</p><br>
                <p class="f14 c-787878"><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-yuan.png" alt="" style="margin-right: 2%;display: block;float: left;margin-top: 6px;width: 3.5%"/>单笔充值金额不低于<?= $deposit_min ?>元，不高于<?= $deposit_max ?>元。</p>
                <p class="f14 c-787878"><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-yuan.png" alt="" style="margin-right: 2%;display: block;float: left;margin-top: 6px;width: 3.5%"/>充值不收取任何手续费</p>
                <p class="f14 c-787878"><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-yuan.png" alt="" style="margin-right: 2%;display: block;float: left;margin-top: 6px;width: 3.5%"/> 每日限制次数<?= $deposit_times ?>次</p>


        </div>
        <br><br><br><br><br><br><br><br><br>
    </div>
    <!--content end-->

</div>
<?php $this->beginBlock('inline_scripts'); ?>
<?=Html::jsFile('@web/js/bootstrap.js')?>
<?=Html::jsFile('@web/js/bootbox.js')?>
<script>
    var flag=[0,0];
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
    $('#code').blur(function(){
        var phone = '<?php echo $info->bank_card_phone;?>';
        var code = $('#code').val();
        console.log(code.length);
        if(code.length==6){
            flag[0]=1;
            $('#spanti').hide();
                    $('#invitacode').hide();
        }else{
                $('#spanti').html('你填写的验证码不正确');
                $('#spanti').show();
                flag[0]=0;
                setTimeout(function(){$('#spanti').fadeOut(500);},2600);
        }
    });

    $('#money').bind('input propertychange', function() {
        var money = parseFloat($('#money').val());
        var reg = /^[0-9]+$/;
        var deposit_max = '<?php echo $deposit_max; ?>';
        var deposit_min = '<?php echo $deposit_min; ?>';
        if(money){
            if(money<deposit_min){
                $('#submit1').attr('class','border0 bgd-787878 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                $('#spanti').html('充值金额少于最低的投资额');
                $('#spanti').show();
                flag[1]=0;
                return false;
                setTimeout(function(){$('#spanti').fadeOut(500);},2600);
            }
            if(money>deposit_max){
                $('#submit1').attr('class','border0 bgd-787878 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                $('#spanti').html('充值金额超过了最大的投资额');
                $('#spanti').show();
                flag[1]=0;
                return false;
                setTimeout(function(){$('#spanti').fadeOut(500);},2600);
            }else if (reg.test(money)) {
                $('#codetext').attr('class','border0 bgd-e44949 hxyzm c-ffffff fr');
                $('#submit1').attr('class', 'border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                $('#spanti').hide();
                $('#invitacode').hide();
                flag[1] = 1;
            }
        }else{
            $('#spanti').html('请填写充值金额');
            $('#spanti').show();
            flag[1]=0;
            setTimeout(function(){$('#spanti').fadeOut(500);},2600);
        }
    });
    $('.send').click(function(){
        var money = $('#money').val();
        bootbox.dialog({
            message: '请耐心等待，正在向服务器提交任务...'
        });
        $.post("<?php echo yii\helpers\Url::to(['recharge/dorecharge']);?>", {
            'money': money,
            '_csrf': '<?php echo yii::$app->request->getCsrfToken();?>'
        }, function (data) {
            bootbox.hideAll();
            var jsonobj = eval('(' + data + ')');
            if (jsonobj.errorNum == 0) {
                wait=60;
                time();
                $("#ticket").val(jsonobj.data.ticket);
                $("#out_trade_no").val(jsonobj.data.out_trade_no);
                $(".spanti-c2").html("验证码发送成功，请查收短信");
                $(".spanti-c2").show();
                setTimeout(function(){$(".spanti-c2").fadeOut(500);},2600);
            }
            else if (jsonobj.errorNum == 1) {
                $(".spanti-c2").html(jsonobj.errorMsg);
                $(".spanti-c2").show();
                setTimeout(function(){$(".spanti-c2").fadeOut(500);},2600);
                console.log(jsonobj.errorMsg);
                if (jsonobj.errorMsg == '请先进行绑定银行卡') {
                    wait=0;
                }
            }
            else {
                wait=0;
                $(".spanti-c2").html(data);
                $(".spanti-c2").show();
                setTimeout(function(){$(".spanti-c2").fadeOut(500);},2600);
            }
        });
    });
    $('#submit1').click(function(){
        var code = $("#code").val();
        var ticket = $("#ticket").val();
        var out_trade_no = $("#out_trade_no").val();
        var res = true;
        for(var i=0;i<flag.length;i++){
            if(flag[i]==0){
                res=false;  break;
            }
        }
        if(res){
            bootbox.dialog({
                message: '请耐心等待，正在向服务器提交任务...'
            });
            $.post("<?php echo yii\helpers\Url::to(['recharge/dorecharge']);?>",
                {
                    'code': code,
                    'ticket': ticket,
                    'out_trade_no': out_trade_no,
                    '_csrf': '<?php echo yii::$app->request->getCsrfToken();?>'
                },
                function (data) {
                    bootbox.hideAll();
                    $(".spanti-c2").html(data);
                    $(".spanti-c2").show();
                    setTimeout(function(){$(".spanti-c2").fadeOut(500);},2600);
                    if (data == '充值成功') {
                        location.href = "<?php echo yii\helpers\Url::to(['site/member']);?>";
                    }
                    else
                    {
                        wait = 0;
                    }
                }
            );
        }else{
            $(".spanti-c2").html("你的填写不完整");
            $(".spanti-c2").show();
            setTimeout(function(){$(".spanti-c2").fadeOut(500);},2600);
            console.log(123);
        }
    });
</script>
<?php $this->endBlock(); ?>