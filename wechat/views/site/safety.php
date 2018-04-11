<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = yii::$app->request->baseUrl.'/../../backend/web/upload/';
?>
<section style="height: 100%">
    <style>
    #body_a{
        position: absolute;
        top:0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #FFF;
        z-index: 9999;
    }
    .Wh-9{
        padding: 1rem;
        color: #a0aab4;
    }
    </style>
<div id="body_a"></div>
<div class="Wh-9">
    <p style="color:#5497cc;">本息保障计划</p><br>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;理财王所投资债权均来自有“充分还款保证”的优质平台。充分还款保证包括但不限于：质押物、保证物、抵押物担保，即借款人需要提供超额的（应还金额的20%）担保，当债务人不履行债务，债权人有权依照担保法的规定以该财产折价或者以拍卖、变卖该财产的价款优先受偿。且用于担保的“质押物、保证物、抵押物”实际受理财王平台控制。</p>
    <br><br>
</div>
<hr>
</section>
<?php $this->beginBlock('inline_scripts'); ?>
<script>
        $(document).ready(function(){
            $("body").css("min-height",$(window).height());
            $("#body_a").hide();
        })
</script>
<?php $this->endBlock(); ?>