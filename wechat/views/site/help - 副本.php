<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = yii::$app->request->baseUrl.'/../../backend/web/upload/';
?>
<style>
    .help-twop{
        padding: 0 1rem;
    }
   .yhxy{
    text-align: center;
    height: 4rem;
    line-height: 4rem;
    color: #111111;
    border-bottom: 1px solid #cacaca;
    font-size: 1.5rem;
   }

</style>
<section>
    <!-- <p><img class="d-block" width="100%" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/wyh.jpg" alt="为什么选择我们"></p> -->
    <div class="help-twop">
            <h3 class="yhxy">用户投资协议</h3><br>
            <p>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;北京理财王投资有限公司（简称“理财王”）是一家专注于活期理财的互联网金融公司，为广大用户提供安全、便捷、灵活的活期理财产品，是互联网+时代高效实用稳健的互联网金融服务平台。<br><br>

                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;公司通过与现已成熟度高、安全性好、规模大的P2P平台及其他投资担保公司合作，对其理财产品筛选与管理，运用大数据平台，将各标的进行重新分配组合，整理成新型的活期理财产品，供用户选择。有着安全系数高、收益率高、灵活性强等特点。<br><br>

                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;超安全：我们会挑选业内综合实力强，风控措施到位，有先行赔付服务的P2P平台进行优先合作，即使发生借贷方逾期或坏账情况，我们也能得到即时有效安全保障。另外，我方平台本身与新浪支付、招商银行等优秀的支付及金融机构合作，运用托管型支付、风险备用金制度等多重手段，全力保障用户的资金安全。<br><br>

                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;高收益：我们的产品平均年化收益率达到8%以上，是银行活期收益的40多倍，是宝宝类产品的2-3倍。<br><br>

                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;高灵活：全面实现T+0提现服务。用户提现当天到账，提高用户便利性。<br>
            </p>

    </div>
</section>
<?php $this->beginBlock('inline_scripts'); ?>
<script>
    $(document).ready(function(){
        $("body").css("min-height",$(window).height());
        var UA=window.navigator.userAgent;  //使用设备
        var CLICK="click";
        if(/ipad|iphone|android/.test(UA)){   //判断使用设备
            CLICK="tap";
        }
        // $(".help-twoT")[CLICK](function(event){
        //     event.preventDefault();
        //     var T_S = $(this).siblings(".help-twocon");
        //     // $(".help-twocon:not(this)").slideUp();
        //     if(T_S.css('display') == 'none'){
        //         $(this).find("i").addClass("icon-angle-down");
        //         T_S.css('display',"block");
        //     }else{
        //         $(this).find("i").removeClass("icon-angle-down");
        //         T_S.css('display',"none");
        //     }
        // });
    })
    $(window).resize(function(){
        $("body").css("min-height",$(window).height());
    })
</script>
<?php $this->endBlock(); ?>

