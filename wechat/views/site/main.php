<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = 'http://101.200.88.175/mmoney/backend/web/upload/';
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
        .index-topT{
            color: #5396ca;
            text-align: center;
            border-bottom: 1px solid  #e5e5e5;
            font-size: 1.2rem;
            padding: 0.8rem 0;
        }
        .index-topT p  {
            line-height: 1.5em;
        }
        .index-listL li p{
            line-height: 2em;
        }
        .index-topT a{
            color: #5396ca;
        }
        .index-topT .fon-25{
            font-size: 3.8rem;
        }
        .index-listL li{
            border-right: 1px solid #e5e5e5;
        }
        .cor-a0{
            color: #a0aab4;
        }
        .cor-54 {
            color: #5497cc;
        }
        .wh-1271{
            font-size: 1.5rem;
            text-align: center;
            margin: 1rem 0 0;
        }
        .wh-1272{
            color: #3c3c3c;
            font-size: 2rem;
            text-align: center;
            margin: 0.5rem 0 1rem;
        }
        .wh-1273{
            float: left;
            width: 33%;
            text-align: center;
            font-size: 1.5rem;
        }
        .wh-1273 a{
            display: block;
            border-right: 1px solid #e5e5e5; 
            padding: 0.8rem 0;
        }
        .wh-1273:last-child a{
            border: none;
        }
        .wh-1273 img{
            width: 60%;
            margin: 0 0 0.8rem;
            max-width: 112px;
        }
    </style>
    <div id="body_a"></div>
<section>
    <?php if($sliders){ ?>
    <div class="bannerPane">
        <div class="swipe">

            <ul id="slider">
        <?php foreach ($sliders as $key=>$slider): ?>
                <li style="<?php if($key ==0){ echo 'display:block'; }?>">
                    <img width="100%" src="<?= $upload . $slider['bannar']; ?>" alt="" />
                </li>
        <?php endforeach; ?>
            </ul>
        <?php } ?>
            <div id="pagenavi">
        <?php foreach ($sliders as $key=>$slider): ?>
                <a href="javascript:void(0);" class="<?php if($key ==0){ echo 'active'; }?>"><?= $key+1 ?></a>
        <?php endforeach; ?>
            </div>
        </div>
        <?php if(yii::$app->user->isGuest)  {?>
        <div>
            <p class="wh-1271 cor-a0">招商银行风险准备金</p>
            <p class="wh-1272">1,000,000元</p>
        </div>
        <ul class="clearFloat">
            <li class="wh-1273">
                <a href="<?php echo Url::to(['site/help']);?>">
                    <img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_safeguard.png" alt="本息保障"><br>本息保障
                </a>
            </li>
            <li class="wh-1273">
                <a href="<?php echo Url::to(['site/help']);?>">
                    <img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_cash.png" alt="随时提现"><br>随时提现
                </a>
            </li>
            <li class="wh-1273">
                <a href="<?php echo Url::to(['site/help']);?>">
                    <img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_investment.png" alt="如何投资"><br>如何投资
                </a>
            </li>
        </ul>
        <?php }else { ?>
         <div class="index-topT">
            <p>昨日年化收益率</p>
            <p class="fon-25"><?= number_format($income_rate * 100, 2); ?>%</p>
            <p><a href="<?= yii\helpers\Url::to('incomelog'); ?>" class="clearFloat">昨日收益：<?= number_format($income_yesterday,2); ?> <i class="icon-angle-right"></i></a></p>
        </div>

        <div class="index-listL">
            <ul class="clearFloat">
                <li>
                    <p class="cor-a0">在投金额</p>
                    <p class="cor-54"><?= number_format($invest_total,2); ?></p>
                </li>
                <li>
                    <p class="cor-a0">在投收益</p>
                    <p class="cor-54"><?= number_format($invest_income,2); ?></p>
                </li>
                <li>
                    <p class="cor-a0">账户余额</p>
                    <p class="cor-54"><?= number_format($balance,2); ?></p>
                </li>
            </ul>
        </div>       
    </div>
    <?php } ?>
</section>
<div class="wh-container">
<section class='main-area bg-f7'>
    <ul class="index-list">
        <?php foreach ($products[data] as $key=>$product): ?>
        <li>
            <?php  $now_time=strtotime("now");?>
            <?php if( ($product[invest_sum] >= $product[amount]) || ($product[end_at] < $now_time)) { ?>
                <p class="index-listS"><a href="javascript:;" class="clearFloat"><img width="100%" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/sale-no.png" alt=""></a></p>
                <p class="index-listT"><a href="javascript:;" class="clearFloat"><?= $product[title] ?></a></p>
            
            <?php }else{ ?>
             <p class="index-listT"><a href="<?php echo Url::to(['site/detaile','id' => $product[id]]);?>" class="clearFloat"><?= $product[title] ?><i class="icon-angle-right"></i></a></p>
            <?php } ?>
           
            <div class="index-listL">
                <ul class="clearFloat">
                    <li>
                        <p class="font-11 cor-78" style="line-height: 1.1rem;margin-bottom: 1.6rem;">项目总金额</p>
                        <p class="font-14 cor-3c" style="line-height: 1.4rem"><?php echo $product[amount];?></p>
                    </li>
                    <li>
                        <p class="font-11 cor-78" style="line-height: 1.1rem;margin-bottom: 1.6rem;">活期收益率</p>
                        <p class="font-14 cor-3c" style="line-height: 1.4rem"><?php echo round($product[rate],3) * 100;?>%+</p>
                    </li>
                    <li>
                        <p class="font-11 cor-78" style="line-height: 1.1rem;margin-bottom: 1.6rem;">已投人数</p>
                        <p class="font-14 cor-3c" style="line-height: 1.4rem"><?php echo $product[invest_people];?></p>
                    </li>
                </ul>
            </div>

            <?php if( ($product[invest_sum] >= $product[amount]) || ($product[end_at] < $now_time)) { ?>
                <div class="index-listIng clearFloat">
                    <p class="index-listIW">投资进度</p>
                    <p class="progress-bar"><span class="progress-num" style="width:100%;" ></span></p>
                    <p class="index-listIW" style="width:3.2rem;">100%</p>
                </div>

            <?php }else{ ?>
            <div class="index-listIng clearFloat">
                <p class="index-listIW">投资进度</p>
                <p class="progress-bar"><span class="progress-num" style="width:<?php echo ceil(($product[invest_sum])/$product[amount ]* 100);?>%" ></span></p>
                <p class="index-listIW" style="width:3.2rem;"><?php echo ceil(($product[invest_sum])/$product[amount] * 100);?>%</p>
            </div>
            <p class="index-listBtn"><a href="<?php echo Url::to(['invest/index','id' => $product[id]]);?>">开始投资</a></p>
            <?php } ?>
        </li>
        <?php endforeach; ?>

    </ul>
</section>
</div>
<section class="zhan-H"></section>
<ul class="footer-nav clearFloat">
    <li><a href="<?php echo Url::to(['site/main']);?>" class="ui-link WH-8"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_invest_press.png" alt="我要投资"><br>我要投资</a></li>
    <li><a href="<?php echo Url::to(['site/member']);?>" class="ui-link"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_account.png" alt="我的帐户"><br>我的帐户</a></li>
    <li><a href="<?php echo Url::to(['site/about']);?>" class="ui-link"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_us.png" alt="关于我们"><br>关于我们</a></li>
    <li><a href="<?php echo Url::to(['site/tuiguang']);?>" class="ui-link"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_recommend.png" alt="推广大师"><br>推广大师</a></li>

</ul>
<section id="wh_cover"></section>
<section class="quit_login">
    <ul class="quit-con">
        <li>
            <a href="<?php echo Url::to(['site/about']);?>">联系客服</a>
        </li>
        <li>
            <a href="<?php echo Url::to(['member/newsign']);?>">签到</a>
        </li>
    </ul>
    <p class="quit-con"><a href="javascript:$('.quit_login').hide();$('#wh_cover').hide();">取消</a></p>
</section>
<?php $this->beginBlock('inline_scripts'); ?>
<script>
    $(document).ready(function(){
    $("body").css("min-height",$(window).height());
    $("#body_a").hide();
    })
</script>
<script>
Date.prototype.Format = function (fmt) { //author: meizz 
        var o = {
            "M+": this.getMonth() + 1, //月份 
            "d+": this.getDate(), //日 
            "h+": this.getHours(), //小时 
            "m+": this.getMinutes(), //分 
            "s+": this.getSeconds(), //秒 
            "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
            "S": this.getMilliseconds() //毫秒 
        };
        if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
        for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        return fmt;
    }
    function unix_to_datetime(unix) {
        var now = new Date(parseInt(unix) * 1000).Format("yyyy-MM-dd hh:mm:ss");
        return now;
    }
    $(document).ready(function(){
        $(window).scroll(function() {
            if ($(this).scrollTop() != 0) {
                $(".m-page-top").slideDown();
            } else {
                $(".m-page-top").slideUp();
            };
        });
        $(".m-page-top").click(function() {
            $("body,html").animate({
                scrollTop: 0
            }, 500)
        });
        var UA=window.navigator.userAgent;  //使用设备
        var CLICK="click";
        if(/ipad|iphone|android/.test(UA)){   //判断使用设备
            CLICK="tap";
        }
        
        
        //需要 zepto.js支持
        var page=1;//当前页
        var pages = '<?= $products_pages;?>'; //总页数
        var ajax=!1;//是否加载中
        Zepto(function($){
            $(window).scroll(function(){
                if(($(window).scrollTop() + $(window).height() > $(document).height()-40) && !ajax && pages>page){
                    //滚动条拉到离底40像素内，而且没ajax中，而且没超过总页数

                    page++;//当前页增加1
                    ajax=!0;//注明开始ajax加载中
                    $(".wh-container").append('<div class="loading" style="text-align:center;  font-size:16px; color:#999; "><img  style="vertical-align: middle; margin-right:10px;" src="<?php echo yii::$app->homeUrl; ?>images/loading1.gif" alt="" />上拉加载</div> ');//出现加载图片
                    
                    $.ajax({
                        type: 'GET',
                        url: 'productlist?page='+page+'&num=4&'+Math.random(),
                        dataType: 'html',
                        success: function(json){
                            var dataObj=eval("("+json+")");
                            var now_time = '<?= strtotime("now");?> ';
                            $.each(dataObj,function(i,val){
                                $(".index-list").append('<li>');
                                if((val.invest_sum >= val.amount) || val.end_at < now_time)
                                {
                                    
                                    $(".index-list").append('<p class="index-listS"><a href="javascript:;" class="clearFloat"><img width="100%" src='+'<?= Yii::getAlias("@web") ; ?>/rq-images/sale-no.png alt=""></a></p><p class="index-listT"><a href="javascript:;" class="clearFloat">'+val.title+'</a></p>');
                                }
                                else
                                {
                                     $(".index-list").append('<p class="index-listT"><a href='+'<?php echo Url::to(["site/detaile"]);?>?id='+val.id+' class="clearFloat">'+val.title+'<i class="icon-angle-right"></i></a></p>');
                                }
                                $(".index-list").append('<div class="index-listL"><ul class="clearFloat"><li><p class="font-11 cor-78" style="line-height: 1.1rem;margin-bottom: 1.6rem;">项目总金额</p><p class="font-14 cor-3c" style="line-height: 1.4rem">'+val.amount+'</p></li><li><p class="font-11 cor-78" style="line-height: 1.1rem;margin-bottom: 1.6rem;">活期收益率</p><p class="font-14 cor-3c" style="line-height: 1.4rem">'+val.rate+'%+</p></li><li><p class="font-11 cor-78" style="line-height: 1.1rem;margin-bottom: 1.6rem;">已投人数</p><p class="font-14 cor-3c" style="line-height: 1.4rem">'+val.invest_people+'</p></li></ul></div>');
                                if((val.invest_sum >= val.amount) || val.end_at < now_time)
                                {
                                    
                                    $(".index-list").append('<div class="index-listIng clearFloat"><p class="index-listIW">投资进度</p><p class="progress-bar"><span class="progress-num" style="width:100%;" ></span></p><p class="index-listIW" style="width:3.2rem;">100%</p></div>')
                                }
                                else
                                {
                                    $(".index-list").append('<div class="index-listIng clearFloat"><p class="index-listIW">投资进度</p><p class="progress-bar"><span class="progress-num" style="width:'+Math.ceil(val.invest_sum/val.amount * 100)+'%" ></span></p><p class="index-listIW" style="width:3.2rem;">'+Math.ceil(val.invest_sum/val.amount * 100)+'%</p></div><p class="index-listBtn"><a href='+'<?php echo Url::to(["invest/index"]);?>?id='+val.id+'>开始投资</a></p>');
                                }
                                $(".index-list").append('</li>');
                                
                            });
                            
                            $(".loading").remove();//删除加载图片
                            ajax=!1;//注明已经完成ajax加载
                        },
                        error: function(xhr, type){
                            $(".loading").html("暂无内容！");
                        }
                    });
                }
            });
        });
        
    })
    window.onresize = function() {
    };
</script>
<script src='<?php echo yii::$app->homeUrl; ?>js/jquery-1.7.2.min.js'></script>
<script src='<?php echo yii::$app->homeUrl; ?>js/zepto.min.js'></script>
<script src="<?= Yii::getAlias('@web') . '/' ?>rq-js/run.js"></script>
<?php $this->endBlock(); ?>