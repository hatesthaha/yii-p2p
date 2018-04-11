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
                    <div class="cy-rg1ph clearfix" id="abc">
                        <label for="">银行卡</label>
                        <input type="text" value="<?php echo substr($info->bank_card, 0, 4) . '************' . substr($info->bank_card, strlen($info->bank_card) - 3, 3); ?>" disabled required/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <label for="" style="width: 28%">可提现金额</label>
                        <input type="text"  value="<?= $info->balance ?>" style="width: 60%;color: #5396ca;" disabled required/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <label for="">提现金额</label>
                        <input type="text" name="money" id="money"  placeholder="请输入提现金额"  required/>
                    </div>
                </div>
                <div style="border-top: 1px solid #c8c8c8"></div>
                <br><br>
                <div class="bdyhkxyb w90 clearfix">
                    <button type="button" id="submit1" class="border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10 cd-popup-trigger" style="width: 100%;">确认提现</button><br><br>
                    <p class="f18"><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-wxts.png" alt=""  style="margin-right: 2%;display: block;float: left;margin-top: 3px;width: 3.5%"/>温馨提示</p><br>
                    <p class="f14 c-787878"><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-yuan.png" alt="" style="margin-right: 2%;display: block;float: left;margin-top: 6px;width: 3.5%"/>每日提现限定<?= $withdraw_times ?>次</p><br>
                    <p class="f14 c-787878"><img src="<?= Yii::getAlias('@web') . '/' ?>images/cy-yuan.png" alt="" style="margin-right: 2%;display: block;float: left;margin-top: 6px;width: 3.5%"/>最小限额<?= $withdraw_min ?>元，最大限额<?= $withdraw_max ?>元。</p><br>
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
            var withdraw_max = '<?php echo $withdraw_max; ?>';
            var withdraw_min = '<?php echo $withdraw_min; ?>';
            var balance ='<?php echo $info->balance ?>';
            if(money){
                if(money>balance){
                    $('#spanti').html('余额不足');
                    $('#submit1').attr('class','border0 bgd-787878 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                    $('#spanti').show();
                    flag[0]=0;
                    return false;
                    setTimeout(function(){$('#spanti').fadeOut(500);},2600);
                }
                if(money<withdraw_min){
                    $('#submit1').attr('class','border0 bgd-787878 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                    $('#spanti').html('提现数少于最低的提现额');
                    $('#spanti').show();
                    flag[0]=0;
                    return false;
                    setTimeout(function(){$('#spanti').fadeOut(500);},2600);
                }
                if(money>withdraw_max){
                    $('#submit1').attr('class','border0 bgd-787878 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                    $('#spanti').html('提现数超过了最大的提现额');
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
                $('#spanti').html('请填写体现金额');
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
                $.post("<?php echo yii\helpers\Url::to(['withdraw/dowithdraw']);?>",
                    {
                        'money': money,
                        '_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'
                    },
                    function (data) {
                        bootbox.hideAll();
                        $(".spanti-c2").html(data);
                        $(".spanti-c2").show();
                        setTimeout(function(){$(".spanti-c2").fadeOut(500);},2600);
                        if (data == '提现成功') {
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