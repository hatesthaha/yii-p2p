<?php
use yii\widgets\LinkPager;
/** @author: liushaohua
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 * @time 2015年7月5日 09:15:54
 * @QQ:489122117
 */

	\www\assets\CircleAppAsset::register($this);
	$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
	$directoryAsset_guarantee = yii::$app->request->baseUrl.'/../../backend/web/upload/';
	$this->title = '我要投资';
?>
<script src="<?php echo Yii::$app->homeUrl;?>myAssetsLib/js/jquery-1.7.1.js"></script>
<script src="<?php echo Yii::$app->homeUrl;?>myAssetsLib/js/raphael-min.js"></script>
<script>
    $(document).ready(function(){
        $(".xj-touzitab tr:odd").addClass("tzOdd");
        $(".page-nember3 .pagination > li.prev span").html("<i class='icon-caret-left'></i>");
        $(".page-nember3 .pagination > li.prev a").html("<i class='icon-caret-left'></i>");
        $(".page-nember3 .pagination > li.next span").html("<i class='icon-caret-right'></i>");
        $(".page-nember3 .pagination > li.next a").html("<i class='icon-caret-right'></i>");
        
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
<div id="content">
    <div class="cont_cot main xj-touzi">
        
    	<div class="left le_tzjd" style="margin:30px 0;">
        	<ul>
        	<?php if($invest_datas){ foreach ($invest_datas as $K=>$V){?>
        		<?php  $now_time=strtotime("now");?>
            	<li class="dateDetail">
                    <div class="dataBgCurrent btnNormalShadow">
                    	<h4><span>项目名称：<?php echo $V["title"];?></span></h4>
						<div class="cont">
							<!-- 修改显示功能-->
							<div class="jdt left">
								<div class="percentBox">
									<div id="bg<?php echo $V["id"]; ?>"></div>
									<div id="txt<?php echo $V["id"]; ?>" class="pertxt">
									</div>
								</div>
								<?php if( ($V['amount'] - $V['invest_sum'] < 10) || ($V['end_at'] < $now_time)) { ?>
									<p>已抢光</p>
								<?php } else {?>
									<p>投资进度</p>
								<?php }?>
							</div>
							<?php
							//项目总金额
							$amount = $V["amount"] + $V["virtual_amonnt"];
							//有投资人数--加上虚拟的再投金额
							$invest_sum = $V['invest_people'] ? $V["invest_sum"] + $V["virtual_amonnt"] : $V["invest_sum"];
							//有投资人--增加虚拟的投资人数
							$invest_people = $V['invest_people'] ? $V['invest_people'] + $V['virtual_invest_people'] : $V['invest_people'];
							?>
							<script>
								init('bg<?php echo $V['id'];?>',<?php if(($V['end_at'] - strtotime("now")) < 0)
								 {echo 1;} else {echo 1 - ($amount-$invest_sum)/$amount;}?>,'#txt<?php echo $V['id']; ?>','#3598db','<?php echo $V['end_at']; ?>');
							</script>
							<div class="ggwz left">
								<p>起投金额：<?php echo $V["each_min"]; ?>元起</p>
								<p>保障方式： 本息保护</p>
								<p>锁定期限：可随时转让变现</p>
								<!--修改显示-->
								<p>投资人数：<?php echo $invest_people;?>人</p>
							</div>
							<div class="ggxjwan left" style="width:265px; padding-right:0;padding-left:10px;">
								<p>本期项目金额</p>
								<!-- 修改显示-->
								<p class="ggxjwan2"><?php echo $amount/10000; ?>万</p>

								<p>单笔投资限额：<?php echo $V['each_min']; ?>—<?php echo $V['each_max']; ?></p>
							</div>
							<div class="ggjd left">
								<p class="ft14">预计年化收益率</p>
								<p class="ft28"><?php echo round($V["rate"],3) * 100;?>%+</p>
								<p>投资1万元每天收益<?php echo round(($V["rate"] * 10000)/365,2);?>元</p>
								<?php if($V['invest_sum'] >= $V['amount']) { ?>
									<p><a class="yiqiangguang" href="javascript:alert('已抢光，下次记得手要快哦~');"></a></p>
								<?php } elseif($V['end_at'] < $now_time) { ?>
									<p><a class="yiqiangguang" href="javascript:alert('已过期，下次记得手要快哦~');"></a></p>
								<?php } elseif($V['start_at'] > $now_time) {?>
									<p><a href="javascript:alert('稍等片刻，即将开售~');"></a></p>
								<?php } elseif($V['start_at'] <= $now_time && $V['end_at'] >$now_time) {?>
									<p><a class="zzjx" href="<?= yii\helpers\Url::to(['site/investinfos','id' => $V['id']]); ?>"></a></p>
								<?php } ?>
							</div>
							<div class="clear"></div>
						</div>
                    </div>
                </li>
              <?php }}?>
                
            </ul>
            <div class="page-nember2">
                <?php 
                    if($invest_datas){
                        echo LinkPager::widget([
                            'pagination'=>$pages
                        ]);
                     }
                ?>
            </div>
        </div>
        <div class="right rg_hus" style="margin:30px 0;">
            <div class="xj-touzibox">
            	<h5>理财王投资数据</h5>
                <div class="xj-touzidiv xj-touzidiv1">
                    <p>累计金额：<span class="teshu-xj"><?php echo number_format((double)$money_total,2);?></span>元</p>
                    <p>累计收益：<?php echo number_format((double)$income_total['smoney'],2);?>元</p>
                    <p>累计人数：<?php echo $people_total;?>人</p>
                    <p>项目数量：<?php echo $product_total;?>个</p>
                </div>
            </div>
            <div class="xj-touzibox">
                <h5>最新投资记录</h5>
                <iframe src="<?php echo yii\helpers\Url::to(['investment/investing_log']);?>" width="290" height="500" margin="0" frameborder="0" scrolling="no">
                 
                </iframe>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
