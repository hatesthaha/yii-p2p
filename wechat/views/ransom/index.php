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
    .cy-rg1ph label{
        font-weight: normal;
    }
    .xgmm{
        padding: 0 5%;
        background: #fff;
    }
</style>
<?php $this->endBlock(); ?>

<span class="spanti-c2"></span>
<div class="wapper bgd-f5f5f4">
    <!--content-->
    <div class="content">
        <br><br>
        <div class="cy-rg1">
            <span class="spanti-c" id="spanti"></span>

                <div style="border-top: 1px solid #c8c8c8"></div>
                <div class="xgmm clearfix">

                    <div class="cy-rg1ph clearfix">
                        <label for="" style="width: 28%">可赎回金额</label>
                        <input type="text"  value="<?= number_format($invest_total,2); ?>" style="width: 60%;color: #5396ca;" disabled required/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <label for="" style="width: 28%">在投金额(元)</label>
                        <input type="text"  value="<?= number_format($collect[online_invest],2); ?>" style="width: 60%;" disabled required/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <label for="" style="width: 28%">在投收益(元)</label>
                        <input type="text"  value="<?= number_format($collect[profit],2); ?>" style="width: 60%;" disabled required/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <label for="" style="width: 28%">推荐红包(元)</label>
                        <input type="text"  value="<?= number_format($red_packet[red_sum],2); ?>" style="width: 60%;" disabled required/>
                    </div>
                </div>
                <div style="border-top: 1px solid #c8c8c8"></div>
                <br>
                <div style="border-top: 1px solid #c8c8c8"></div>
                <div class="xgmm clearfix">
                    <div class="cy-rg1ph clearfix">
                        <label for="">赎回金额</label>
                        <input type="text" name="money" id="money"  placeholder="请输入赎回金额"  required/>
                    </div>
                </div>
                <div style="border-top: 1px solid #c8c8c8"></div>
                <br>
                <div class="bdyhkxyb w90 clearfix">
                    <button type="button" id="submit1" class="border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10 cd-popup-trigger" style="width: 100%;">确认赎回</button><br><br>
                    <p class="f18"><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-wxts.png" alt=""  style="margin-right: 2%;display: block;float: left;margin-top: 3px;width: 3.5%"/>温馨提示</p><br>
                    <p class="f14 c-787878"><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-yuan.png" alt="" style="margin-right: 2%;display: block;float: left;margin-top: 6px;width: 3.5%"/>赎回免手续费</p>
                    <p class="f14 c-787878"><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-yuan.png" alt="" style="margin-right: 2%;display: block;float: left;margin-top: 6px;width: 3.5%"/>限额最低<?= $ransom_min ?>元，最大<?= $ransom_max ?>元，每日赎回限定<?= $ransom_times ?>次。</p>
                    <p class="f14 c-787878"><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-yuan.png" alt="" style="margin-right: 2%;display: block;float: left;margin-top: 6px;width: 3.5%"/>赎回成功后，赎回的金额将汇至您的账号余额中</p><br>
                </div>

        </div>
        <br><br><br><br><br><br><br><br><br>
    </div>
    <!--content end-->

</div>

<?php $this->beginBlock('inline_scripts'); ?>
<?=Html::jsFile('@web/js/bootstrap.js')?>
<?=Html::jsFile('@web/js/bootbox.js')?>
<script>
    var flag=[0];
    $('#money').blur(function(){
        var money = parseFloat($('#money').val());
        var ransom_max = '<?php echo $ransom_max; ?>';
        var ransom_min = '<?php echo $ransom_min; ?>';
        var balance ='<?php echo $invest_total; ?>';
        if(money){
            if(money>balance){
                $('#spanti').html('余额不足');
                $('#submit1').attr('class','border0 bgd-787878 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                $('#spanti').show();
                flag[0]=0;
                return false;
                setTimeout(function(){$('#spanti').fadeOut(500);},2600);
            }
            if(money<ransom_min){
                $('#submit1').attr('class','border0 bgd-787878 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                $('#spanti').html('赎回数少于最低的赎回额');
                $('#spanti').show();
                flag[0]=0;
                return false;
                setTimeout(function(){$('#spanti').fadeOut(500);},2600);
            }
            if(money>ransom_max){
                $('#submit1').attr('class','border0 bgd-787878 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                $('#spanti').html('赎回数超过了最大的赎回额');
                $('#spanti').show();
                flag[0]=0;
                return false;
                setTimeout(function(){$('#spanti').fadeOut(500);},2600);
            }

            $('#spanti').hide();
            $('#invitacode').hide();
            $('#submit1').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
            flag[0]=1;
        }else{
            $('#submit1').attr('class','border0 bgd-787878 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
            $('#spanti').html('你没有填写赎回金额');
            $('#spanti').show();
            flag[0]=0;
            setTimeout(function(){$('#spanti').fadeOut(500);},2600);
        }
    });
    $('#submit1').click(function(){
        var money = $('#money').val();
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
            $.post("<?php echo yii\helpers\Url::to(['ransom/doransom']);?>",
                {
                    'money': money,
                    '_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'
                },
                function (data) {
                    bootbox.hideAll();
                    $(".spanti-c2").html(data);
                    $(".spanti-c2").show();
                    setTimeout(function(){$(".spanti-c2").fadeOut(500);},2600);
                    if (data == '赎回成功') {
                        location.href = "<?php echo yii\helpers\Url::to(['site/member']);?>";
                    }
                    else
                    {

                    }
                }
            );
        }else{
            console.log(123);
        }
    });
</script>
<?php $this->endBlock(); ?>