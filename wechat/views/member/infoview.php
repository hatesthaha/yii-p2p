<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = yii::$app->request->baseUrl.'/../../backend/web/upload/';
?>
<section class="bg-f7">
<section class="assets-div clearFloat">
    <div class="assets-divL floatLeft">
        <p><img width="100%;" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/assets1.png" alt=""></p>
        <p>我的收益</p>
    </div>
    <div class="assets-divR floatLeft">
        <p>累计收益(元): <span><?= number_format($info->total_revenue,'2') ?></span></p>
        <p>昨日收益(元): <span><?= number_format($info->profit,'2') ?></span></p>
    </div>
</section>
<section class="assets-div clearFloat">
    <div class="assets-divL floatLeft">
        <p><img width="100%;" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/assets2.png" alt=""></p>
        <p>我的投资</p>
    </div>
    <div class="assets-divR floatLeft">
        <p>在投金额(元): <span><?= number_format($invest_total,'2') ?></span></p>
    </div>
</section>
<section class="assets-div clearFloat">
    <div class="assets-divL floatLeft">
        <p><img width="100%;" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/assets3.png" alt=""></p>
        <p>我的余额</p>
    </div>
    <div class="assets-divR floatLeft">
        <p>帐户余额(元): <span> <?= number_format($info->balance,'2') ?></span></p>
    </div>
</section>
</section>
<?php $this->beginBlock('inline_scripts'); ?>
<script>
    $(document).ready(function(){
        $("body").css("min-height",$(window).height());
        $('.assets-divR').each(function(){
            var T = $(this).parent(".assets-div").height();
            $(this).css("margin-top",(T-$(this).height())/2)

        })
    })
</script>
<?php $this->endBlock(); ?>