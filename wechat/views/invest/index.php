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
        width:57%;
        padding: 0;
    }
    .cy-rg1ph label{
        font-weight: normal;
        width:36%;
    }
    .xgmm{
        padding: 0 5%;
        background: #fff;
    }
</style>
<?php $this->endBlock(); ?>
<?=Html::cssFile('@web/css/bootstrap.css')?>
<span class="spanti-c2"></span>
<div class="wapper bgd-f5f5f4">
    <!--content-->
    <div class="content">
        <br>
        <div class="cy-rg1">
            <span class="spanti-c" id="spanti"></span>

                <div style="border-top: 1px solid #c8c8c8"></div>
                <div class="xgmm clearfix">
                    <div class="cy-rg1ph clearfix">
                        <label for="">项目名称  </label>
                        <input style="font-size:14px;" type="text"  value="<?= $product->title ?>" disabled required/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <label for="">账户余额（元） </label>
                        <input style="color: #5396ca;" type="text" value="<?= number_format($info->balance,2); ?>" disabled required/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <label for="">可投金额（元） </label>
                        <input style="color: #5396ca;" type="text" value="<?= number_format($kmoney[money],2); ?>" disabled required/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <label for="" style="width: 66%;">投资1万元每天收益元（元）</label>
                        <input type="text" disabled value="<?php echo number_format($product->rate * 10000 / 365, 2); ?>" style="width: 30%" required/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <p class="clearfix">
                        <label for="">投资金额（元） </label>
                        <input type="text" name="money" id="money" placeholder="输入您想输入的金额" style=" border: 1px solid #f7f7f7;margin:0;text-align: right;width: 54%;padding:7px 0 7px 1%;height:30px; line-height:30px; margin-right: 3%;" required/>
                        </p>
                    </div>
                </div>
                <div style="border-top: 1px solid #c8c8c8"></div>
                <br><br>
                <div class="bdyhkxyb w90">
                    <button type="button" id="submit1" class="border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10" style="width: 100%;">确认投资</button><br><br>
                    <p class="f18"><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-wxts.png" alt=""  style="margin-right: 2%;display: block;float: left;margin-top: 3px;width: 3.5%"/>温馨提示</p><br>
                    <p class="f14 c-787878"><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-yuan.png" alt="" style="margin-right: 2%;display: block;float: left;margin-top: 6px;width: 3.5%"/>单笔投资不低于<?= $invest_min  ?>元，不高于<?= $invest_max  ?>元。
                    </p>
                </div>
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
    })
    var flag=[0];
    $('#money').blur(function(){
        var money = parseFloat($('#money').val());
        var balance = '<?php echo $info->balance; ?>';
        var invest_max = '<?php echo $invest_max; ?>';
        var invest_min = '<?php echo $invest_min; ?>';
        if(money){
            if(money>balance){
                $('#spanti').html('余额不足');
                $('#submit1').attr('class','border0 bgd-787878 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                $('#spanti').show();
                flag[0]=0;
                return false;
                setTimeout(function(){$('#spanti').fadeOut(500);},2600);

            }
            if(money<invest_min){
                $('#submit1').attr('class','border0 bgd-787878 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                $('#spanti').html('投资金额少于最低投资额');
                $('#spanti').show();
                flag[0]=0;
                return false;
                setTimeout(function(){$('#spanti').fadeOut(500);},2600);

            }
            if(money>invest_max){
                $('#submit1').attr('class','border0 bgd-787878 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                $('#spanti').html('投资金超出了最大投资额');
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
            $('#submit1').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
            $('#spanti').html('请填写投资金额');
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
            $.post("<?php echo yii\helpers\Url::to(['invest/doinvest']);?>",
                {
                    'money': money,
                    '_csrf':'<?php echo yii::$app->request->getCsrfToken();?>',
                    'product_id':'<?php echo $_GET['id']; ?>'
                },
                function (data) {
                    bootbox.hideAll();
                    $(".spanti-c2").html(data);
                    $(".spanti-c2").show();
                    setTimeout(function(){$(".spanti-c2").fadeOut(500);},2600);
                    if (data == '您已成功完成了投资') {
                        location.href = "<?php echo yii\helpers\Url::to(['site/main']);?>";
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