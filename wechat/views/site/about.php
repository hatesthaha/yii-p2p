<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = yii::$app->request->baseUrl.'/../../backend/web/upload/';
?>
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
</style>
<section id="body_a"></section>
<section class="bg-f7">
<section>
    <p><img class="d-block" width="100%" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/wyh.jpg" alt="为什么选择我们"></p>
</section>
<section class="way-div clearFloat WH-8">
    <div class="way-divL floatLeft">
        <p><img width="100%;" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_earnings.png" alt=""></p>
    </div>
    <div class="way-divR  floatLeft WH-8">
        <p><span>年化收益高至8%+</span></p>
        <p>余额宝的两倍多，</p>
        <p>年化高至8%+</p>
        <p>畅享收益快感</p>
    </div>
</section>
<section class="way-div clearFloat WH-8">
    <div class="way-divR floatLeft">
        <p><span>随时随地提现</span></p>
        <p>只需悦动指尖，</p>
        <p>便可随心充值提现！</p>
    </div>
    <div class="way-divL floatLeft" >
        <p><img width="100%;" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_withdraw.png" alt=""></p>
    </div>
</section>
<section class="way-div clearFloat">
    <div class="way-divL floatLeft">
        <p><img width="100%;" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_guarantee.png" alt=""></p>
    </div>
    <div class="way-divR  floatLeft">
        <p><span>100%本息保障</span></p>
        <p>新浪支付资金托管，</p>
        <p>千万风险保障金，</p>
        <p>资金安全双重保障！</p>
    </div>
</section>
<section class="zhan-H"></section>
</section>
<ul class="footer-nav clearFloat">
    <li><a href="<?php echo Url::to(['site/main']);?>" class="ui-link"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_invest.png" alt="我要投资"><br>我要投资</a></li>
    <li><a href="<?php echo Url::to(['site/member']);?>" class="ui-link"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_account.png" alt="我的帐户"><br>我的帐户</a></li>
    <li><a href="<?php echo Url::to(['site/about']);?>" class="ui-link WH-8"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_us_press.png" alt="关于我们"><br>关于我们</a></li>
    <li><a href="<?php echo Url::to(['site/tuiguang']);?>" class="ui-link"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_recommend.png" alt="推广大师"><br>推广大师</a></li>

</ul>
<?php $this->beginBlock('inline_scripts'); ?>
    <script>
        var bodyFoo = function(){
            $('.way-divR').each(function(){
                var T = $(this).parent(".way-div").height();
                $(this).css("margin-top",(T-$(this).height())/2)

            })
            $('.way-divR').each(function(){
                var T = $(this).parent(".way-div").width();
                var T_l = $(this).siblings(".way-divL").width();
                $(this).width(T-T_l-28);
            });
        }
        $(document).ready(function(){
            $("body").css("min-height",$(window).height());
            bodyFoo();
            $("#body_a").hide();
        })
        $(window).resize(function(){
            $("body").css("min-height",$(window).height());
            bodyFoo();
            $("#body_a").hide();
        })
    </script>
<?php $this->endBlock(); ?>
