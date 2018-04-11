<?php
/** @author: liushaohua
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 * @time 2015年7月5日 09:15:54
 * @QQ:489122117
 */
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;
use common\models\post\SignIn;
	\www\assets\CircleAppAsset::register($this);
	$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
	$directoryAsset_meida = yii::$app->request->baseUrl.'/../../backend/web/upload/';
	$this->title = '首页';
?>
<script src="<?php echo Yii::$app->homeUrl;?>myAssetsLib/js/jquery-1.7.1.js"></script>
<script src="<?php echo Yii::$app->homeUrl;?>myAssetsLib/js/raphael-min.js"></script>
<script>
$(document).ready(function(){
    var xj_i = 1;
    $("#signRuleBtn").click(function(event){
        event.preventDefault();
        if($("#signRule").is(":hidden")){  
            $(this).find("a").html("点击收起");
            $(this).find("i").addClass("arrowRotate")

        }else{
            $(this).find("a").html("签到规则");
            $(this).find("i").removeClass("arrowRotate")
        };


        $("#signRule").toggle();
    })
	$("#checkin").click(function(){
		$.post(
			      "<?php echo yii\helpers\Url::to(['site/checkin']); ?>",
			    		  {'_csrf':"<?php echo Yii::$app->request->getCsrfToken(); ?>"},
			      function (data){ //回传函数
			    	var jsonobj = eval('('+data+')');
			    	if(jsonobj.errorNum == 0)
			    	{
				    	var totalPerson = <?php echo count(SignIn::find()->where('sign_in_time >='.strtotime(date("Y-m-d")))->all());?>;
			    		$("#countPeson").empty();
						   $("#countPeson").text(totalPerson * 1 + 1);
						   $("#checkin").text("签到成功");
						   $("#checkin").attr("id","_checkin");
				    }
			    	else if(jsonobj.errorNum == 1 && jsonobj.errorMsg != '在投资金小于1000')
			    	{
			    			//$("#countPeson").empty();
						   //$("#countPeson").text(data * 1 + 1);
						   //$("#checkin").text(jsonobj.errorMsg);
						   alert(jsonobj.errorMsg);
						   $("#checkin").attr("id","_checkin");
				    }
			    	if(jsonobj.errorMsg == '在投资金小于1000')
				   {
						alert('在投金额≥1000元才可以参与签到');
				    }
			       
			      }
			    );
			});
	});
</script>
<script type="text/javascript">
	function init(b,n,t,c,m){
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
		var timestamp = Date.parse(new Date())/1000;
		if(Math.round(percent * 100) == 100 || (timestamp - m)>0)
		{
			$(t).text("售罄");
		}
		else
			$(t).text(Math.round(percent * 100) + "%");
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
        	<h4>什么是理财王理财？</h4>
            <p>作为传统金融业与互联网结合的新兴领域，互联网金融具有透明度高、参与广泛、中间成本低、支付便捷、信用数据更为丰
富和信息处理效率更高等优势。未来的10年，是移动互联网金融发展的黄金10年，投资理财不再是银行贵宾室的特权。快速、
扁平化、高保障投资项目无缝连接起来的金融项目将会去掉更多的中间环节，降低资金成本，提高投资收益。<a href="<?php echo yii\helpers\Url::to(['about/company']);?>">了解更多>></a></p>
        </div>
        <div class="left le_shuzi">
        	<p>
                <?php if($reserve){echo number_format($reserve,2);}else {echo 0.00;}?><b>元</b>
                <span>招商银行风险准备金</span>
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
                	
                	<?php if( $V->invest_sum >= $V->amount ) { ?>
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

				<?php
				//项目总金额
				$amount = $V["amount"] + $V["virtual_amonnt"];
				//有投资人数--加上虚拟的再投金额
				$invest_sum = $V['invest_people'] ? $V["invest_sum"] + $V["virtual_amonnt"] : $V["invest_sum"];
				//有投资人--增加虚拟的投资人数
				$invest_people = $V['invest_people'] ? $V['invest_people'] + $V['virtual_invest_people'] : $V['invest_people'];
				?>
                    <div class="dataBgCurrent btnNormalShadow">
                    	<h4><span>&nbsp;&nbsp;<?php echo $V->title; ?>&nbsp;&nbsp;&nbsp;预计可投总金额：<b><?php echo $amount/10000;?>万元</b></span><a class="ckxq" href="<?= yii\helpers\Url::to(['site/investinfos','id' => $V->id]); ?>">查看详情</a></h4>
                        <div class="cont">
                        	<div class="jdt left">
                               <div class="percentBox">
                                    <div id="bg<?php echo $V->id; ?>"></div> 
                                    <div id="txt<?php echo $V->id; ?>" class="pertxt"></div>
                                </div>
                                <?php if( ($V->amount - $V->invest_sum < 10) || ($V->end_at < $now_time)) { ?>
                                <p>已抢光</p>
                                <?php } else {?>
                            	<p>投资进度</p>
                            	<?php }?>
                            </div>

							<script>
								init('bg<?php echo $V->id;?>',<?php
								if(($V->end_at - strtotime("now")) < 0) {echo 1;}
								else {echo 1-($amount-$invest_sum)/$amount;}?>,'#txt<?php echo $V->id; ?>','#3598db','<?php echo $V->end_at; ?>');
							</script>
                            <div class="ggwz left">
                            	<p>起投金额：<?php echo $V->each_min; ?>元起</p>
                                <p>保障方式： 本息保护</p>
                                <p>锁定期限：可随时转让变现</p>
                                <p>投资人数：<?php echo $invest_people;?>人</p>
                            </div>
                            <div class="ggjd left">
                            	<p class="ft14">预计年化收益率</p>
                                <p class="ft28"><?php echo round($V->rate,4) * 100;?>%+</p>
                                <p>投资1万元每天收益<?php echo round(($V->rate * 10000)/365,2);?>元</p>
                                
                              <?php if($V->invest_sum >= $V->amount) { ?>
                                <p><a class="yiqiangguang" href="javascript:alert('已抢光，下次记得手要快哦~');"></a></p>
                              <?php } elseif($V->end_at < $now_time) { ?>
                                <p><a class="yiqiangguang" href="javascript:alert('已过期，下次记得手要快哦~');"></a></p>
                              <?php } elseif($V->start_at > $now_time) {?>
                              <p><a href="javascript:alert('稍等片刻，即将开售~');"></a></p>
                              <?php } elseif($V->start_at <= $now_time && $V->end_at >$now_time) {?>
                              <p><a class="zzjx" href="<?= yii\helpers\Url::to(['site/investinfos','id' => $V->id]); ?>"></a></p>
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
						<?php
						$reward = 0;
						if($yesterday_sign_in['data']['count']){
							$reward = $yesterday_sign_in['data']['money'] / $yesterday_sign_in['data']['count'];
						}?>
						<?php echo sprintf("%.2f", $reward).'元';?>
                    </p>
                    <p class="invest">
						<?php
						$reward_sum = 0;
						if($reward){
							$reward_sum = ($reward * 365)/0.08;
						}?>
                       	相当于每天帮您多投资
                        <span><?php echo sprintf("%.2f", $reward_sum).'元'; ?></span>
                    </p>
                </div>
                
                <ul>
                	<li><a>昨日签到总奖励</a><span class="huanse_zt"><?php echo $yesterday_sign_in['data']['money'] ? $yesterday_sign_in['data']['money'] : 0 ;?>元</span></li>
                    <li><a>昨日签到总人数</a><span class="huanse_zt"><?php if(isset($yesterday_total)){ echo $yesterday_total;} ?>人</span></li>
                </ul>
                <p class="denl huanse btnNormal"><a href="<?php echo yii\helpers\Url::to(['site/login'])?>">登录账户签到领奖励</a></p>
             <?php } else { ?>
             
                <div class="bgTitle">
            		<h5 class="bgTitle">当前签到人数</h5>
	            </div>
	            <h4 class="countP"><a id="countPeson"><?php echo $checkin_total; ?></a>人</h4>
	            <p class="signBtn">
	                <?php if(!$isCheckin) { ?>
	                <a id="checkin" href="javascript:void(0); " class="btnSign btnNormal">点击签到</a>
	                <?php } else { ?>
	                <a id="check" href="javascript:void(0);" class="btnSign btnNormal">已签到</a>
	                <?php } ?>
	            </p>
	            <ul class="digit mt30" style="padding: 0 18px;">
	    	        <li>
	            		<pre>昨日签到总人数</pre>
	                	<span id="yestdsum"><?php if(isset($yesterday_total)){ echo $yesterday_total;} ?>人</span>
	                </li>
	                <li>
	                	<pre>平均每人奖励</pre>
						<?php
						$reward = 0;
						if($yesterday_sign_in['data']['count']){
							$reward = $yesterday_sign_in['data']['money'] / $yesterday_sign_in['data']['count'];
						}?>
	                	<span id="yesReward"><?php echo sprintf("%.2f", $reward);?></span>
	                </li>
	                <li><?php
						$reward_sum = 0;
						if($reward){
							$reward_sum = ($reward * 365)/0.08;
						}?>
	                	<pre>等于帮你多投</pre>
	                	<span id="rewardCount"><?php echo sprintf("%.2f", $reward_sum); ?>元</span>
	                </li>
	            </ul>
             <?php } ?>
                <div class="signRuDet dispNone" id="signRule" style="display: none;">
                    <p>1.在投金额≥1000元才可以参与签到。</p>
                    <p>2.每天签到后可获得当日签到奖励，系统在当日结束后统计签到人数，平分红包收益。签到人数越多，平均获得收益越少。</p>
                </div>
                <p class="signRule" id="signRuleBtn">
                    <a href="#">签到规则</a>
                    <i class="arrow" id="arrow"><img src="<?php echo $directoryAsset; ?>/images/arrouSign.png"></i>
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
                      	<li><span class="tp<?php echo $i;?> it left"><?php if($i>3) echo '0'.$i;?></span><span class="left xinm"> <?php for ($n=0;$n<mb_strlen($rank[$i-1]['real_name'],'utf-8')-1;$n++){echo '* ';} echo mb_substr($rank[$i-1]['real_name'], mb_strlen($rank[$i-1]['real_name'],'utf-8')-1,1,'utf-8');?></span><span class="left qians"><?php echo round($rank[$i-1]['money'],2);?>元</span></li>
                       <?php  $i++;?>
                      <?php }} else {?>
                      <li><span style="color: gray;text-align:center;">暂无收益记录</span></li>
                      <?php }?>
                      </ul>
                    </div>    
                    <div class="none hui">
                       <ul>
                      	<?php $j=1;if(count($rank_month) >0 ) {  foreach ($rank_month as $K => $V) {?>
                      	<li><span class="tp<?php echo $j;?> it left">
								<?php if($j>3) echo '0'.$j;?></span><span class="left xinm">
								<?php for ($n=0;$n<mb_strlen($rank_month[$j-1]['real_name'],'utf-8')-1;$n++){
									echo '* ';
								} echo mb_substr($rank_month[$j-1]['real_name'], mb_strlen($rank_month[$j-1]['real_name'],'utf-8')-1,1,'utf-8');
								?>
							</span><span class="left qians"><?php echo round($rank_month[$j-1]['money'],2);?>元</span></li>
                       <?php  $j++;?>
                      <?php }} else {?>
                      <li><span style="color: gray;text-align:center;">暂无收益记录</span></li>
                      <?php }?>
                      </ul>
                    </div>   
                  </div>
                </div>
            </div>
            <div class="tzbz btnNormal">
            	<h5><i></i>投资帮助</h5>
                <ul>
                	<li><a href="<?php echo yii\helpers\Url::to(['help/index']);?>">注册</a></li>
                	<li><a href="<?php echo yii\helpers\Url::to(['help/index']);?>">认证</a></li>
                	<li><a href="<?php echo yii\helpers\Url::to(['help/index']);?>">绑定银行卡</a></li>
                	<li><a href="<?php echo yii\helpers\Url::to(['help/index']);?>">充值</a></li>
                	<li><a href="<?php echo yii\helpers\Url::to(['help/index']);?>">投资</a></li>
                	<li><a href="<?php echo yii\helpers\Url::to(['help/index']);?>">赎回</a></li>
                </ul>
                <p><a href="<?php echo yii\helpers\Url::to(['help/index']);?>">更多>> </a></p>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="cont_hzt">
    	<div class="main">
<!--        <h3>媒体报道<a href="--><?php //echo yii\helpers\Url::to(['about/media']);?><!--" class="more">更多<i>...</i></a></h3>-->
<!--    	--><?php //if($media) {?>
<!--            <ul class="hzhb left">-->
<!--            	--><?php //foreach ($media as $K=>$V){?>
<!--            	<li><a target="_blank" href="--><?php // echo $V['link']?><!--"><img src="--><?//= $directoryAsset_meida . $V['bannar']; ?><!--" /></a></li>-->
<!--            	--><?php //} ?>
<!--                <div class="clear"></div>-->
<!--            </ul>-->
<!--            --><?php //}?>
         <?php if($partner) {?>
            <div class="mtcx left">
            	<h3>合作伙伴<a href="<?php echo yii\helpers\Url::to(['about/partner']);?>" class="more">更多<i>...</i></a></h3>
                <div class="rollBox">
                  <?php foreach ($partner as $K=>$V){ ?>
                        <div class="pic">
                        <a href="<?php echo yii\helpers\Url::to(['about/partner']);?>" ><img  src="<?= $directoryAsset_meida . $V['bannar']; ?>"/></a>
                        </div>
                  <?php }?>
                </div>
            </div>
          <?php }?>
            <div class="clear"></div>
        </div>
    </div>
</div>
