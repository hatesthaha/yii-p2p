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
        padding: 10px;
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
        margin: 0 2%;
        border: 1px solid #c8c8c8;
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
                <div class="xgmm clearfix">
                    <div class="cy-rg1ph clearfix">
                        <label for="" style="width: 28%">所属银行</label>
                        <input type="text"  value="<?php if($datas[bank_name]){echo $datas[bank_name];}?>" style="width: 60%;" disabled required/>
                    </div>
                    <div class="cy-rg1ph clearfix">
                        <label for="" style="width: 28%">银行卡号</label>
                        <input type="text"  value="<?php if($datas[bank_account_no]){$length = mb_strlen($datas[bank_account_no], 'utf-8');   echo mb_substr($datas[bank_account_no], 0, 4, 'utf-8'). '***********'.mb_substr($datas[bank_account_no], $length-4, 4, 'utf-8') ;}?>" style="width: 60%;" disabled required/>
                    </div>
                </div>
        </div>
    </div>
    <!--content end-->

</div>
<section class="zhan-H"></section>
<ul class="footer-nav clearFloat">
    <li><a href="<?php echo Url::to(['site/main']);?>" class="ui-link"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_invest.png" alt="我要投资"><br>我要投资</a></li>
    <li><a href="<?php echo Url::to(['site/member']);?>" class="ui-link WH-8"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_account_press.png" alt="我的帐户"><br>我的帐户</a></li>
    <li><a href="<?php echo Url::to(['site/about']);?>" class="ui-link"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_us.png" alt="关于我们"><br>关于我们</a></li>
    <li><a href="<?php echo Url::to(['site/tuiguang']);?>" class="ui-link"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_recommend.png" alt="推广大师"><br>推广大师</a></li>

</ul>
<?php $this->beginBlock('inline_scripts'); ?>
<?=Html::jsFile('@web/js/bootstrap.js')?>
<?=Html::jsFile('@web/js/bootbox.js')?>
<?php $this->endBlock(); ?>