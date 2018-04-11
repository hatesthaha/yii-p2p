<?php
use yii\helpers\Html;
use yii\helpers\Url;
use www\controllers\SmsController;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = 'http://101.200.88.175/mmoney/www/web/upload/';
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
    .bg-5497cc{
        background: #5497cc;
        color: #fff;
    }
    .WH-1{
        text-align: center;
        position: relative;
        height: 4.5rem;
        line-height: 4.5rem;
        font-size: 1.8rem;
    }
    .WH-2{
        position: absolute;
        right: 1.2rem;
        top: 1.5rem;
        color: #fff;
        font-size: 1.3rem;
        line-height: 1.3rem;
    }
    .WH-3{
        line-height: 2rem;
        font-size: 1.5rem;
        text-align: center;
    }
    .WH-4{
        line-height: 4rem;
        height: 4rem;
        font-size: 3rem;
        text-align: center;
    }
    .account-me{
        overflow: hidden;
        zoom:1;
    }
    .account-me li{
        width: 33%;
        border: none;
        padding: 0;
        text-align: center;
        float: left;
        height: auto;
        line-height: normal;
    }
    .account-me li a{
        padding: 1.5rem 0;
        display: block;
        width: 100%;
        border-right: 1px solid #f7f7f7;
    }
    .account-me li:last-child a{
        border: none;
    }
    .account-me li img{
        width: 6rem;
        margin: 0 0 0.5rem;
    }
    .WH-5 li{
        overflow: hidden;
        zoom:1;
        text-align: center;
        padding: 1rem 0;
    }
    .WH-5 a{
        color: #fff;
    }
    .WH-5 li > div{
        float: left;
        width: 50%;
    }
    .WH-5 .lieven{
        background: #508fc1;
    }
    .Wh-6{
        display: none;
    }
</style>
<section id="body_a"></section>
<section class="bg-5497cc">
    <p class="WH-1">我的账户<a class="WH-2" href="<?php echo Url::to(['member/signin']);?>">签到</a></p>
    <p class="WH-3">昨日收益(元)</p>
    <p class="WH-4"><?= number_format($income_yesterday,2);?></p>
    <ul class="WH-5">
        <li>
            <div>
                <div style="border-right:1px solid #8ab8dc;">
                    <p>账户余额(元)</p>
                    <p><?= number_format($balance,2);?></p>
                </div>
            </div>
            <div>
                <p>在投余额(元)</p>
                <p><?= number_format($invest_total,2);?></p>
            </div>
        </li>
        <li class="WH-6">
            <div>
                <div style="border-right:1px solid #8ab8dc;">
                    <p>累计收益(元)</p>
                    <p><?= number_format($income_total,2);?></p>
                </div>
            </div>
            <div>
                <a href="<?php echo Url::to(['gold/experiencelist']);?>">
                    <p>体验金(元)</p>
                    <p><?= number_format($experience_money,2);?> <i class="icon-angle-right"></i></p>
                </a>
            </div>
        </li>
        <li class="WH-6">
            <div>
                <div style="border-right:1px solid #8ab8dc;">
                    <p>体验金收益(元)</p>
                    <p><?= number_format($experience_income,2);?></p>
                </div>
            </div>
            <div>
                <a href="<?php echo Url::to(['gold/recommendlist']);?>">
                    <p>推荐红包(元)</p>
                    <p><?= number_format($railscard,2);?> <i class="icon-angle-right"></i></p>
                </a>
            </div>
        </li>
        <li id="WH_7" style="background: #508fc1;">
            <i style="line-height:2rem; font-size:2rem;" class="icon-double-angle-down"></i>
        </li>
    </ul>
</section>
<section>
    <div>
<!--         <p class="account-img"> class="account-top"
            <a href="javascript:;">
                <img style="height: 117px" src="<?php  echo $upload;?><?php echo $model["person_face"]; ?>" alt="头像">
            </a>
        </p>
        <p class="account-info">您好，
        <?php if(yii::$app->user->identity->real_name){?>
            <?php echo yii::$app->user->identity->real_name;?>
        <?php } else {?>
            <?php echo yii::$app->user->identity->username;?>
        <?php }?>
        </p>
    </div>
   <p class="account-btn clearFloat">
        <a class="floatLeft" href="<?php echo Url::to(['recharge/index']);?>"><img height="18px" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_recharge.png" alt="">充值</a>
        <a class='floatRight' href="<?php echo Url::to(['withdraw/index']);?>"><img height="24px" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_cash2.png" alt="">提现</a>
    </p> --> 
</section>
<section>
    <ul class="account-me">
        <li>
            <a href="<?php echo Url::to(['recharge/index']);?>">
                <p><span class="account-meI"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_recharge.png" alt=""></span></p>
                <p>充值</p>
            </a>
        </li>
        <li>
            <a href="<?php echo Url::to(['withdraw/index']);?>">
                <p><span class="account-meI"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_cash2.png" alt=""></span></p>
                <p>实时提现</p>
            </a>
        </li>
        <li>
            <a href="<?php echo Url::to(['money/index']);?>">
               <p><span class="account-meI"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_record.png" alt=""></span></p>
               <p>交易记录</p>
            </a>
        </li>
    </ul>
    <ul class="account-me">
        <li>
            <a href="<?php echo Url::to(['member/setting']);?>">
                <p><span class="account-meI"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_account_setting.png" alt=""></span></p>
                <p>账户设置</p>
            </a>
        </li>
        <li>
            <a href="<?php echo Url::to(['ransom/index']);?>">
                <p><span class="account-meI"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_redemption.png" alt=""></span></p>
                <p>实时赎回</p>
            </a>
        </li>
        <li>
            <a href="<?php echo Url::to(['site/safety']);?>">
                <p><span class="account-meI"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_safeguard_2.png" alt=""></span></p>
                <p>资金保障</p>
            </a>
        </li>
    </ul>
</section>
<section class="chan-H"></section>
<!--<li>
            <a href="<?php echo Url::to(['member/infoview']);?>"><span class="account-meI"><img height="24px" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/head-list1.png" alt=""></span>资产详情<i class="icon-angle-right floatRight"></i></a>
        </li>
        <li>
            <a href="<?php echo Url::to(['member/signin']);?>"><span class="account-meI"><img height="24px" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/head-list2.png" alt=""></span>签到记录<i class="icon-angle-right floatRight"></i></a>
        </li>-->
<ul class="footer-nav clearFloat">
    <li><a href="<?php echo Url::to(['site/main']);?>" class="ui-link"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_invest.png" alt="我要投资"><br>我要投资</a></li>
    <li><a href="<?php echo Url::to(['site/member']);?>" class="ui-link WH-8"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_account_press.png" alt="我的帐户"><br>我的帐户</a></li>
    <li><a href="<?php echo Url::to(['site/about']);?>" class="ui-link"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_us.png" alt="关于我们"><br>关于我们</a></li>
    <li><a href="<?php echo Url::to(['site/tuiguang']);?>" class="ui-link"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_recommend.png" alt="推广大师"><br>推广大师</a></li>
</ul>
<section class="quit_login">
    <ul class="quit-con">
        <li>
            <a href="<?php echo Url::to(['site/contact']);?>">联系客服</a>
        </li>
        <li>
            <a href="<?php echo Url::to(['member/newsign']);?>">签到</a>
        </li>
    </ul>
    <p class="quit-con"><a href="javascript:$('.quit_login').hide();$('#wh_cover').hide();">取消</a></p>
</section>
<section id="wh_cover"></section>
<?php $this->beginBlock('inline_scripts'); ?>
<script>
    $(document).ready(function(){
        var UA=window.navigator.userAgent;  //使用设备
        var CLICK="click";
        if(/ipad|iphone|android/.test(UA)){   //判断使用设备
            CLICK="tap";
        }
        $(".WH-5 li:even").addClass("lieven"); 
        $("body").css("min-height",$(window).height());
        $(".account-img").height($(".account-img").width());
        $(".account-top").css("height",$(".account-img").height()+5);
        $(".account-info").css("line-height",$(".account-img").height() + "px");
        $("#WH_7")[CLICK](function(){
            var temp=$(".WH-6").is(":hidden");
            if(temp){
                $(".WH-6").css("display","block");
                $("#WH_7").find("i").removeClass("icon-double-angle-down");
                $("#WH_7").find("i").addClass("icon-double-angle-up");
                $("#WH_7").css("background","#5497cc")
            }else{
                $(".WH-6").css("display","none");
                $("#WH_7").find("i").removeClass("icon-double-angle-up");
                $("#WH_7").find("i").addClass("icon-double-angle-down");
                $("#WH_7").css("background","#508fc1")
            }
        });
        $(".more")[CLICK](function(event){
            event.preventDefault();
            $("#wh_cover").show();
            $("body").css("position","fixed");

            $(".quit_login").show();
        });
        $("#body_a").hide();
    })
</script>
<?php $this->endBlock(); ?>