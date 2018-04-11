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
	.WH-10271{
		background: #fafafa;
		text-align: center;
		color: #5497cc;
		line-height: 30px;
		padding: 10px 0;
		font-size: 18px;
		border-bottom: 1px solid #cecece;
	}
	.WH-10271 img{
		height: 30px;
		vertical-align: middle;
		margin-right: 5px;
	}
	.WH-10272{
		padding: 0 10px;
	}
	.WH-10273{
		border-bottom: 1px solid #cecece;
		padding: 10px 3px 10px 8px;
	}
	.WH-10273 p{
		border-left: 2px solid #5497cc;
		padding: 0 0 0 10px;
		color: #e64c4c;
		text-align: right;
		overflow: hidden;
		zoom:1;
	}
	.WH-10273 p span{
		color: #686868;
		float: left;
	}
	.WH-10274{
		padding: 10px 8px 10px 8px;
		border-bottom: 1px solid #cecece;
		overflow: hidden;
		zoom:1;
		display: -webkit-box;
		display: -moz-box;
		display: -o-box;
		display: box;
	}
	.WH-10274 .WH-10275{
		display: -webkit-box;
		display: -moz-box;
		display: -o-box;
		display: box;
		-webkit-box-flex: 1;
		-moz-box-flex: 1;
		-o-box-flex: 1;
		box-flex: 1;
		color: #000;
	}
	.WH-10276{
		width:20px; 
		margin-right:5px;
	}
	.WH-10276 img{
		position: relative;
		top: 3px;
	}
	.WH-10277{
		background: #5497cc;
		border-radius: 5px;
		line-height: 3.7rem;
		height: 3.7rem;
		width: 80%;
		margin: 1rem auto;
		display: block;
		text-align: center;
		color: #fff;
		font-size: 1.6rem;
	}
</style>
<section id="body_a"></section>
<section>
	<p class="WH-10271"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/level_1.png" alt="直接推荐关系"><span>直接推荐关系</span></p>
	<ul class="WH-10272">
		<li class="WH-10273"><p><span>直接推荐用户</span>0人</p></li>
		<li class="WH-10273"><p><span>满足红包条件用户</span>0人</p></li>
		<li class="WH-10273"><p><span>红包累计金额</span>0.00元</p></li>
	</ul>
	<div class="WH-10274">
		<p class="WH-10276"><img width="20px;" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/notice.png" aligh="left" alt="直接推荐关系"></p>
		<p class="WH-10275">红包条件：邀请人和被邀请人同时满足至少连续30天在投金额3000元</p>
	</div>
</section>
<section>
	<p class="WH-10271"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/level_2.png" alt="间接推荐关系"><span>间接推荐关系</span></p>
	<ul class="WH-10272">
		<li class="WH-10273"><p><span>直接推荐用户</span>21人</p></li>
		<li class="WH-10273"><p><span>满足红包条件用户</span>0人</p></li>
		<li class="WH-10273"><p><span>红包累计金额</span>0.00元</p></li>
	</ul>
	<div class="WH-10274">
		<p class="WH-10276"><img width="20px;" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/notice.png" aligh="left" alt="直接推荐关系"></p>
		<p class="WH-10275">红包条件：邀请人满足连续60天在投金额6000元，同时间接被邀请人满足连续30天在投金额3000元。</p>
	</div>
</section>
<p><a id="share" class="WH-10277" href="#">分享</a></p>
<section class="chan-H"></section>
<ul class="footer-nav clearFloat">
    <li><a href="<?php echo Url::to(['site/main']);?>" class="ui-link"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_invest.png" alt="我要投资"><br>我要投资</a></li>
    <li><a href="<?php echo Url::to(['site/member']);?>" class="ui-link"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_account.png" alt="我的帐户"><br>我的帐户</a></li>
    <li><a href="<?php echo Url::to(['site/about']);?>" class="ui-link"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_us.png" alt="关于我们"><br>关于我们</a></li>
    <li><a href="<?php echo Url::to(['site/tuiguang']);?>" class="ui-link WH-8"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/ic_recommend_pressed.png" alt="推广大师"><br>推广大师</a></li>
</ul>
<section id="wh_cover"></section>
<p class="share"><img width="100%" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/share.png" alt="分享"></p>
<?php $this->beginBlock('inline_scripts'); ?>
<script>
    $(document).ready(function(){
	    var UA=window.navigator.userAgent;  //使用设备
	    var CLICK="click";
	    if(/ipad|iphone|android/.test(UA)){   //判断使用设备
	        CLICK="tap";
	    }
	    $("body").css("min-height",$(window).height());
	    $("#share")[CLICK](function(event){
	        event.preventDefault();
	        $("body,html").animate({
	            scrollTop: 0
	        }, 500);
	        $("body").css("position","fixed");
	        $("#wh_cover").show();
	        $(".share").show();
	    });
	    $("#wh_cover")[CLICK](function(event){
	        $("body").css("position","relative");
	        $(".share").hide();
	        $(this).hide();
	    });
	    $("#body_a").hide();
    })
</script>
<script src="<?= Yii::getAlias('@web') . '/' ?>rq-js/run.js"></script>
<?php $this->endBlock(); ?>