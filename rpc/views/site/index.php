<?php
/** @author: liushaohua
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 * @time 2015年7月5日 09:15:54
 * @QQ:489122117
 */
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;

	\www\assets\CircleAppAsset::register($this);
	$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
	$this->title='首页-小微金融';
	
?>

<div class="cont_cyzs">
    	<div class="main">
        	<ul>
            	<li>
                	<i class="if1"></i>
                    <p>
                    	收益高
                   	 	<br>
                    	<span>活期年化收益8%+</span>
                    </p>
                    <div class="clear"></div>
                </li>
                <li>
                    <i class="if2"></i>
                    <p>
                        随时取
                        <br>
                        <span>转让变现快、提现实时到</span>
                    </p>
                    <div class="clear"></div>
                </li>
                <li>
                    <i class="if3"></i>
                    <p>
                        有保障
                        <br>
                        <span>多重保障、本息保护</span>
                    </p>
                    <div class="clear"></div>
                </li>
                <li>
                    <i class="if4"></i>
                    <p>
                        第三方账户托管
                        <br>
                        <span>账户托管至新浪支付</span>
                    </p>
                    <div class="clear"></div>
                </li>
                <div class="clear"></div>
            </ul>
        </div>
    </div>
    <div class="cont_smslc main">
    	<div class="left le_wenz">
        	<h4>什么是XX理财？</h4>
            <p>作为传统金融业与互联网结合的新兴领域，互联网金融具有透明度高、参与广泛、中间成本低、支付便捷、信用数据更为丰
富和信息处理效率更高等优势。未来的10年，是移动互联网金融发展的黄金10年，投资理财不再是银行贵宾室的特权。快速、
扁平化、高保障投资项目无缝连接起来的金融项目将会去掉更多的中间环节，降低资金成本，提高投资收益。<a href="lxwm.html">了解更多>></a></p>
        </div>
        <div class="left le_shuzi">
        	<p>
                8,888,888.88<b>元</b>
                <span>XX银行监管风险准备金</span>
            </p>
        </div>
        <div class="clear"></div>
    </div>
    <div class="cont_cot main">
    	<div class="left le_tzjd">
        	<ul>
        	<?php
        	if(isset($model)){
	        	 foreach ($model as $K => $V)
	        	 {
	       	?>
	       	
	       	<li class="dateDetail">
                	<div class="dataTipTopL">
                	<?php  $now_time=strtotime("now");?>
                	
                	<?php if( $V->invest_sum == $V->amount ) { ?>
                        <span class="tagGray">已抢光</span>
                        <span class="circleGray"></span>
                    <?php } elseif($V->end_at < $now_time) {?>
                    	<span class="tagGray">已过期</span>
                        <span class="circleGray"></span>
                    <?php } elseif($V->start_at > $now_time) {?>
                		<span class="tagGray">即将开售</span>
                        <span class="circleGray"></span>
                    <?php } elseif($V->start_at <= $now_time && $V->end_at >$now_time) {?>
                        <span class="tag">已开售</span>
                        <span class="circle"></span>
                    <?php } ?>
                        <i class="triangle">
                        	<img src="<?= $directoryAsset; ?>/images/angle.png" alt="债权详情">
                        </i>
                    </div>
                    <div class="dataBgCurrent btnNormalShadow">
                    	<h4><span><?php echo $V->title; ?>预计可投总金额：<b><?php echo $V->amount/10000;?>万元</b></span><a class="ckxq" href="<?= yii\helpers\Url::to(['site/investinfos','id' => $V->id]); ?>">查看详情</a></h4>
                        <div class="cont">
                        	<div class="jdt left">
                               <div class="percentBox">
                                    <div id="bg<?php echo $V->id; ?>"></div> 
                                    <div id="txt<?php echo $V->id; ?>" class="pertxt"><?php echo ceil(($V->amount-$V->invest_sum)/$V->amount * 100);?>%</div> 
                                </div>
                            	<p>剩余可投额度</p>
                            </div>
                            <div class="ggwz left">
                            	<p>起投金额：<?php echo $V->each_min; ?>元起</p>
                                <p>保障方式： 本息保护</p>
                                <p>锁定期限：可随时转让变现</p>
                                <p>投资人数：<?php echo $V->invest_people;?>人</p>
                            </div>
                            <div class="ggjd left">
                            	<p class="ft14">预计年化收益率</p>
                                <p class="ft28"><?php echo $V->rate * 100;?>%+</p>
                                <p>投资1万元每天收益2.2元</p>
                                
                              <?php if($V->invest_sum == $V->amount) { ?>
                                <p><a href="javascript:alert('已抢光，下次记得手要快哦~');"></a></p>
                              <?php } elseif($V->end_at < $now_time) { ?>
                                <p><a href="javascript:alert('已过期，下次记得手要快哦~');"></a></p>
                              <?php } elseif($V->start_at > $now_time) {?>
                              <p><a href="javascript:alert('稍等片刻，即将开售~');"></a></p>
                              <?php } elseif($V->start_at <= $now_time && $V->end_at >$now_time) {?>
                              <p><a class="zzjx" href="<?= yii\helpers\Url::to(['site/invest','id' => $V->id]); ?>"></a></p>
                              <?php } ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </li>
                
	        <?php 
	        	 }	
        			}
        	?>
            	
            </ul>
        </div>
        <div class="right rg_hus">
        	<div class="qdlj btnNormal">
        	
            <?php if(yii::$app->user->isGuest) {?>
            	<div class="bgTitle">
                    <h5>昨日签到用户每人奖励</h5>
                    <p>
                    	0.15元
                    </p>
                    <p class="invest">
                       	相当于每天帮您多投资
                        <span>660.60元</span>
                    </p>
                </div>
                
                <ul>
                	<li><a>昨日签到总奖励</a><span class="huanse_zt">8888.88元</span></li>
                    <li><a>昨日签到总人数</a><span class="huanse_zt">8888人</span></li>
                </ul>
                <p class="denl huanse btnNormal"><a href="<?php echo yii\helpers\Url::to(['site/login'])?>">登录账户签到领奖励</a></p>
             <?php } else { ?>
             
                <div class="bgTitle">
            		<h5 class="bgTitle">当前签到人数</h5>
	            </div>
	            <h4 class="countP"><span id="countPeson"><?php echo $checkin_total; ?></span>人</h4>
	            <p class="signBtn">
	                <?php if(!$isCheckin) { ?>
	                <a id="checkin" href="javascript:void(0); " class="btnSign btnNormal">点击签到</a>
	                <?php } else { ?>
	                <a id="check" href="javascript:void(0);" class="btnSign btnNormal">已签到</a>
	                <?php } ?>
	            </p>
	            <ul class="digit mt30" style="padding:0px;">
	    	        <li>
	            		<pre>昨日签到总人数</pre>
	                	<span id="yestdsum"><?php echo $yesterday_total; ?>人</span>
	                </li>
	                <li>
	                	<pre>平均每人奖励</pre>
	                	<span id="yesReward">0.14元</span>
	                </li>
	                <li>
	                	<pre>等于帮你多投</pre>
	                	<span id="rewardCount">634.05元</span>
	                </li>
	            </ul>
             <?php } ?>
                <p class="signRule">
                    <a href="#">签到规则</a>
                </p>
            </div>
            <div class="cyphb btnNormal">
            	<h5><i></i>收益排行榜</h5>
            	<div id="tab">
                  <div class="tab_title">
                    <div class="u">
                      <ul class="scrol">
                        <li class="selected">昨日在投收益</li>
                        <li>最近30天收益</li>
                      </ul>
                    </div>
                  </div>
                  <div class="tab_content">
                    <div class="hui">
                      <ul>
                      <?php $i=1;if(count($rank) >0 ) { foreach ($rank as $K => $V) {?>
                      	<li><span class="tp<?php echo $i;?> it left"><?php if($i>3) echo '0'.$i;?></span><span class="left xinm"><?php echo $rank[$i-1]['username'];?></span><span class="left qians"><?php echo $rank[$i-1]['money'];?>元</span></li>
                       <?php  $i++;?>
                      <?php }}?>
                      </ul>
                    </div>    
                    <div class="none hui">
                       <ul>
                      	<?php $j=1;if(count($rank_month) >0 ) {  foreach ($rank_month as $K => $V) {?>
                      	<li><span class="tp<?php echo $j;?> it left"><?php if($j>3) echo '0'.$j;?></span><span class="left xinm"><?php echo $rank_month[$j-1]['username'];?></span><span class="left qians"><?php echo $rank_month[$j-1]['money'];?>元</span></li>
                       <?php  $j++;?>
                      <?php }} ?>
                      </ul>
                    </div>   
                  </div>
                </div>
            </div>
            <div class="tzbz btnNormal">
            	<h5><i></i>投资帮助</h5>
                <ul>
                	<li><a href="bzzx.html">1. 什么是XXX理财？</a></li>
                    <li><a href="bzzx.html">2. 什么是XXX理财？</a></li>
                    <li><a href="bzzx.html">3. 什么是XXX理财？</a></li>
                    <li><a href="bzzx.html">4. 什么是XXX理财？</a></li>
                    <li><a href="bzzx.html">5. 什么是XXX理财？</a></li>
                    <li><a href="bzzx.html">6. 什么是XXX理财？</a></li>
                </ul>
                <p><a href="">更多>> </a></p>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="cont_hzt">
    	<div class="main">
            <ul class="hzhb left">
            	<h3>合作伙伴</h3>
            	<li><a href="hzhb.html"><img src="<?= $directoryAsset; ?>/images/partne_03.png" /></a></li>
                <li><a href="hzhb.html"><img src="<?= $directoryAsset; ?>/images/partne_03.png" /></a></li>
                <li><a href="hzhb.html"><img src="<?= $directoryAsset; ?>/images/partne_03.png" /></a></li>
                <li><a href="hzhb.html"><img src="<?= $directoryAsset; ?>/images/partne_03.png" /></a></li>
                <li><a href="hzhb.html"><img src="<?= $directoryAsset; ?>/images/partne_03.png" /></a></li>
                <li><a href="hzhb.html"><img src="<?= $directoryAsset; ?>/images/partne_03.png" /></a></li>
                <li><a href="hzhb.html"><img src="<?= $directoryAsset; ?>/images/partne_03.png" /></a></li>
                <li><a href="hzhb.html"><img src="<?= $directoryAsset; ?>/images/partne_03.png" /></a></li>
                <div class="clear"></div>
            </ul>
            <div class="mtcx left">
            	<h3>媒体报道</h3>
                <div class="rollBox"> 
                    <div class="LeftBotton ggtp" onmousedown="ISL_GoUp()" onmouseup="ISL_StopUp()" onmouseout="ISL_StopUp()"></div> 
                        <div class="Cont" id="ISL_Cont"> 
                            <div class="ScrCont"> 
                                <div id="List1"> 
                                
                                <!-- 图片列表 begin --> 
                                <div class="pic"> 
                                <a href="newsbd.html" target="_blank"><img  src="<?= $directoryAsset; ?>/images/media.png"/></a> 
                                </div> 
                                
                                <div class="pic"> 
                                <a href="newsbd.html" target="_blank"><img  src="<?= $directoryAsset; ?>/images/media.png"/></a> 
                                </div> 
                                <div class="pic"> 
                                <a href="newsbd.html" target="_blank"><img  src="<?= $directoryAsset; ?>/images/media.png"/></a> 
                                </div>
                                <div class="pic"> 
                                <a href="newsbd.html" target="_blank"><img  src="<?= $directoryAsset; ?>/images/media.png"/></a> 
                                </div>
                                <div class="pic"> 
                                <a href="newsbd.html" target="_blank"><img  src="<?= $directoryAsset; ?>/images/media.png"/></a> 
                                </div>  
                                <!-- 图片列表 end --> 
                                </div> 
                                <div id="List2"></div> 
                            </div> 
                        </div> 
                    <div class="RightBotton ggtp" onmousedown="ISL_GoDown()" onmouseup="ISL_StopDown()" onmouseout="ISL_StopDown()"></div> 
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<script src="<?php echo Yii::$app->homeUrl;?>myAssetsLib/js/jquery-1.7.1.js"></script>
<script>
$(document).ready(function(){
	$("#checkin").click(function(){
		$.post(
			      "<?php echo yii\helpers\Url::to(['site/checkin']); ?>",
			    		  {'_csrf':"<?php echo Yii::$app->request->getCsrfToken(); ?>"},
			      function (data) //回传函数
			      {
			       $("#checkin").text(data);
			       $("#checkin").attr("id","_checkin");
			      }
			    );
			});
	});
</script>
<script type="text/javascript">
        	$(document).ready(function(){
        		circle();
            	});
/*圆形百分比*/
 function circle(){
var  paper =  null;
function init(b,n,t,c){
	//初始化Raphael画布 
	this.paper = Raphael(b, 98, 98); 
	
	//把底图先画上去 
	this.paper.image("<?php echo $directoryAsset;?>/images/progressBg.png", 0, 0, 98, 98); 
	
	//进度比例，0到1，在本例中我们画65% 
	//需要注意，下面的算法不支持画100%，要按99.99%来画 
	var percent = n	, 
		drawPercent = percent >= 1 ? 0.9999 : percent; 
	
	//开始计算各点的位置，见后图 
	//r1是内圆半径，r2是外圆半径 
	var r1 = 42.5, r2 = 48, PI = Math.PI, 
		p1 = { 
			x:48,  
			y:96 
		}, 
		p4 = { 
			x:p1.x, 
			y:p1.y - r2 + r1 
		}, 
		p2 = {  
			x:p1.x + r2 * Math.sin(2 * PI * (1 - drawPercent)), 
			y:p1.y - r2 + r2 * Math.cos(2 * PI * (1 - drawPercent)) 
		}, 
		p3 = { 
			x:p4.x + r1 * Math.sin(2 * PI * (1 - drawPercent)), 
			y:p4.y - r1 + r1 * Math.cos(2 * PI * (1 - drawPercent)) 
		}, 
		path = [ 
			'M', p1.x, ' ', p1.y, 
			'A', r2, ' ', r2, ' 0 ', percent > 0.5 ? 1 : 0, ' 1 ', p2.x, ' ', p2.y, 
			'L', p3.x, ' ', p3.y, 
			'A', r1, ' ', r1, ' 0 ', percent > 0.5 ? 1 : 0, ' 0 ', p4.x, ' ', p4.y, 
			'Z' 
		].join(''); 
	
	//用path方法画图形，由两段圆弧和两条直线组成，画弧线的算法见后 
	this.paper.path(path) 
		//填充渐变色，从#3f0b3f到#ff66ff 
		.attr({"stroke-width":0.5, "stroke":"#d2d4d8", "fill":"90-" + c}); 
	
	//显示进度文字 
	if(Math.round(percent * 100) == 0)
	{
		$(t).text("售罄"); 
	}
	else
	$(t).text(Math.round(percent * 100) + "%"); 
}
		 <?php
		 if(isset($model)) {
	        foreach ($model as $K => $V)
	        {
	      ?>
init('bg<?php echo $V->id;?>',<?php echo ($V->amount-$V->invest_sum)/$V->amount;?>,'#txt<?php echo $V->id; ?>','#3598db'); 
		<?php } } ?>
}


</script>
		
<script language="javascript">
	$(function(){
		var index = 0;  
		$(".tab_title ul li").click(function(){
			index = $(".tab_title ul li").index(this);
			$(this).addClass("selected").siblings().removeClass("selected");
			$(".tab_content .hui").eq(index).show().siblings().hide();
		});
		var i = 2;  //定义每个面板显示8个菜单
		var len = $(".u .scrol li").length;  //获得LI元素的个数
		var page = 1;
		var maxpage = Math.ceil(len/i);
		var scrollWidth = $(".u").width();
		$(".vright").click(function(e){
			if(!$(".u .scrol").is(":animated")){
			if(page == maxpage ){
				$(".u .scrol").stop();
				$("#div1").css({
					"top": (e.pageY + 20) +"px",
					"left": (e.pageX + 20) +"px",
					"opacity": "0.9"
				}).stop(true,false).fadeIn(800).fadeOut(800);
			}else{
				$(".u .scrol").animate({left : "-=" + scrollWidth +"px"},2000);
				page++;
			}
			}
		});
		$(".vleft").click(function(){
		if(!$(".u .scrol").is(":animated")){
			if(page == 1){
			$(".u .scrol").stop();
			}else{
			$(".u .scrol").animate({left : "+=" + scrollWidth +"px"},2000);
			page--;
			}
			}
		});
	});
</script>
<script language="javascript" type="text/javascript"> 
<!-- 
//图片滚动列表 5icool.org 
var Speed = 1; //速度(毫秒) 
var Space = 1; //每次移动(px) 
var PageWidth = 330; //翻页宽度 
var fill = 0; //整体移位 
var MoveLock = false; 
var MoveTimeObj; 
var Comp = 0; 
var AutoPlayObj = null; 
GetObj("List2").innerHTML = GetObj("List1").innerHTML; 
GetObj('ISL_Cont').scrollLeft = fill; 
GetObj("ISL_Cont").onmouseover = function(){clearInterval(AutoPlayObj);} 
GetObj("ISL_Cont").onmouseout = function(){AutoPlay();} 
AutoPlay(); 
function GetObj(objName){if(document.getElementById){return eval('document.getElementById("'+objName+'")')}else{return eval('document.all.'+objName)}} 
function AutoPlay(){ //自动滚动 
clearInterval(AutoPlayObj); 
AutoPlayObj = setInterval('ISL_GoDown();ISL_StopDown();',3000); //间隔时间 
} 
function ISL_GoUp(){ //上翻开始 
if(MoveLock) return; 
clearInterval(AutoPlayObj); 
MoveLock = true; 
MoveTimeObj = setInterval('ISL_ScrUp();',Speed); 
} 
function ISL_StopUp(){ //上翻停止 
clearInterval(MoveTimeObj); 
if(GetObj('ISL_Cont').scrollLeft % PageWidth - fill != 0){ 
Comp = fill - (GetObj('ISL_Cont').scrollLeft % PageWidth); 
CompScr(); 
}else{ 
MoveLock = false; 
} 
AutoPlay(); 
} 
function ISL_ScrUp(){ //上翻动作 
if(GetObj('ISL_Cont').scrollLeft <= 0){GetObj('ISL_Cont').scrollLeft = GetObj('ISL_Cont').scrollLeft + GetObj('List1').offsetWidth} 
GetObj('ISL_Cont').scrollLeft -= Space ; 
} 
function ISL_GoDown(){ //下翻 
clearInterval(MoveTimeObj); 
if(MoveLock) return; 
clearInterval(AutoPlayObj); 
MoveLock = true; 
ISL_ScrDown(); 
MoveTimeObj = setInterval('ISL_ScrDown()',Speed); 
} 
function ISL_StopDown(){ //下翻停止 
clearInterval(MoveTimeObj); 
if(GetObj('ISL_Cont').scrollLeft % PageWidth - fill != 0 ){ 
Comp = PageWidth - GetObj('ISL_Cont').scrollLeft % PageWidth + fill; 
CompScr(); 
}else{ 
MoveLock = false; 
} 
AutoPlay(); 
} 
function ISL_ScrDown(){ //下翻动作 
if(GetObj('ISL_Cont').scrollLeft >= GetObj('List1').scrollWidth){GetObj('ISL_Cont').scrollLeft = GetObj('ISL_Cont').scrollLeft - GetObj('List1').scrollWidth;} 
GetObj('ISL_Cont').scrollLeft += Space ; 
} 
function CompScr(){ 
var num; 
if(Comp == 0){MoveLock = false;return;} 
if(Comp < 0){ //上翻 
if(Comp < -Space){ 
Comp += Space; 
num = Space; 
}else{ 
num = -Comp; 
Comp = 0; 
} 
GetObj('ISL_Cont').scrollLeft -= num; 
setTimeout('CompScr()',Speed); 
}else{ //下翻 
if(Comp > Space){ 
Comp -= Space; 
num = Space; 
}else{ 
num = Comp; 
Comp = 0; 
} 
GetObj('ISL_Cont').scrollLeft += num; 
setTimeout('CompScr()',Speed); 
} 
} 
//--> 
</script> 