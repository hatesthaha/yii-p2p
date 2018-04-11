<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = yii::$app->request->baseUrl.'/../../backend/web/upload/';

?>
<style>
    .record-T{
        background: none;
    }
    .record-T a{
        
        color: #a1aab5;
    }
    .record-T a.active-01{
        color: #5496cb;
		border-bottom: 1px solid;
    }
    .record-Tabcon{

    }
    .record-T a:last-child{
        background: none;
    }
    .record-Tcon li{
        border-bottom: none;
        padding: 0.5rem;
        margin: 0 0rem;
        color: #fff;
    }
	.record-Tcon li:nth-child(even){
	    background:#f8f8f8;
	}
	.record-Tcdiv{
	      padding: 0 0.9rem;
	}
    
</style>
<div class="wh-container">
<section>
    <p class="record-T clearFloat">
        <a class="active-01" data-page='<?= $logs_pages;?>' data-cato="sy" data-url='loglist' href="javascript:;">全部</a>
        <a data-cato="tz" data-page='<?= $recharge_pages;?>' data-url='rechargelist' href="javascript:;">充值</a>
        <a data-cato="cz" data-page='<?= $invest_pages;?>' data-url='investlist' href="javascript:;">投资</a>
        <a data-cato="tx" data-page='<?= $ransom_pages;?>' data-url='ransomlist' href="javascript:;">赎回</a>
        <a data-cato="sh" data-page='<?= $withdraw_pages;?>' data-url='withdrawlist' href="javascript:;">提现</a>
    </p>
</section>
<section>
    <div class="record-Tabcon" id="sy">
        <!-- <div class="record-I">
            <p class="font-15">累计收益（元）</p>
            <p class="font-35"><?=$allincome['allmoney']?></p>
        </div> -->
        <ul class="record-Tcon">
            <?php if($logs[data][data]) { foreach ($logs[data][data] as $K=>$V) {?>
				<li>
                    <div  class="record-Tcdiv">
					    <p class="record-TcT" style="color:#000;"><?php if($V['status'] == 1){echo '充值';}elseif($V['status'] == 2){echo '投资';}elseif($V['status'] == 3){echo '赎回';}elseif($V['status'] == 4){echo '提现';} ?>
                        &nbsp;<span class="record-TcT" style="color:#a0aab4;"><?php echo date('Y-m-d H:i:s',$V['create_at']); ?></span></p>
                        <p class="record-TcF" style="color:#000;"><?php echo number_format($V['step'],2); ?></p>
                    </div>
                </li>
            <?php } } else {?>
            <li>
                <div  class="record-Tcdiv">
                    暂无记录
                </div>
            </li>
            <?php }?>
        </ul>
    </div>
    <div class="record-Tabcon" id="tz">
<!--         <div class="record-I">
            <p class="font-35"><?=$allinvest['allmoney']?></p>
        </div> -->
        <ul class="record-Tcon">
            <?php if($recharge[data][data]) { foreach ($recharge[data][data] as $K=>$V) {?>
				<li>
                    <div  class="record-Tcdiv">
					    <p class="record-TcT" style="color:#000;">充值
                        &nbsp;<span class="record-TcT" style="color:#a0aab4;"><?php echo date('Y-m-d H:i:s',$V['create_at']); ?></span></p>
                        <p class="record-TcF" style="color:#000;"><?php echo number_format($V['step'],2); ?></p>
                    </div>
                </li>
            <?php } } else {?>
                <li>
                    <div  class="record-Tcdiv" >
                        暂无充值记录
                    </div>
                </li>
            <?php }?>
        </ul>
    </div>
    <div class="record-Tabcon" id="cz">
       <!--  <div class="record-I">
            <p class="font-35"><?=$allrecharge['allmoney']?></p>
        </div> -->
        <ul class="record-Tcon">
            <?php if($invest[data][data]) { foreach ($invest[data][data] as $K=>$V) {?>
				<li>
                    <div  class="record-Tcdiv">
					    <p class="record-TcT" style="color:#000;">投资
                        &nbsp;<span class="record-TcT" style="color:#a0aab4;"><?php echo date('Y-m-d H:i:s',$V['create_at']); ?></span></p>
                        <p class="record-TcF" style="color:#000;"><?php echo number_format($V['step'],2); ?></p>
                    </div>
                </li>
            <?php } } else {?>
                <li>
                    <div  class="record-Tcdiv" style=" padding-left: 2rem;">
                        暂无投资记录
                    </div>
                </li>
            <?php }?>
        </ul>
    </div>
    <div class="record-Tabcon" id="tx">
        <!-- <div class="record-I">
            <p class="font-35"><?=$allwithdraw['allmoney']?></p>
        </div> -->
        <ul class="record-Tcon">
            <?php if($ransom[data][data]) { foreach ($ransom[data][data] as $K=>$V) {?>
				<li>
                    <div  class="record-Tcdiv">
					    <p class="record-TcT" style="color:#000;">赎回
                        &nbsp;<span class="record-TcT" style="color:#a0aab4;"><?php echo date('Y-m-d H:i:s',$V['create_at']); ?></span></p>
                        <p class="record-TcF" style="color:#000;"><?php echo number_format($V['step'],2); ?></p>
                    </div>
                </li>
            <?php } } else {?>
                <li>
                    <div  class="record-Tcdiv">
                        暂无赎回记录
                    </div>
                </li>
            <?php }?>
        </ul>
    </div>
    <div class="record-Tabcon" id="sh">
       <!--  <div class="record-I">
            <p class="font-35"><?=$allransom['allmoney']?></p>
        </div> -->
        <ul class="record-Tcon">
            <?php if($withdraw[data][data]) { foreach ($withdraw[data][data] as $K=>$V) {?>
				<li>
                    <div  class="record-Tcdiv">
					    <p class="record-TcT" style="color:#000;">提现
                        &nbsp;<span class="record-TcT" style="color:#a0aab4;"><?php echo date('Y-m-d H:i:s',$V['create_at']); ?></span></p>
                        <p class="record-TcF" style="color:#000;"><?php echo number_format($V['step'],2); ?></p>
                    </div>
                </li>
            <?php } } else {?>
                <li>
                    <div  class="record-Tcdiv">
                        暂无提现记录
                    </div>
                </li>
            <?php }?>
        </ul>
    </div>
</section>
<div class="m-page-right">
    <p class="m-page-top">
        <i class="icon-angle-up"></i>
    </p>
</div>
</div>

<?php $this->beginBlock('inline_scripts'); ?>
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
		var id='sy';
		var url = 'loglist';
        var page=1;//当前页
		var pages = '<?= $logs_pages;?>'; //总页数
        var ul_obj = $('#'+id+' .record-Tcon'); //数据加载的位置
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
        var catoFram=$(".record-Tabcon");
        var subNav=$(".record-T a");
        catoFram[0].style.display="block";
        subNav[0].className += " active-01";
        subNav[CLICK](function(){
            var _this=$(this);
            id=_this.data("cato");
			ul_obj = $('#'+id+' .record-Tcon');
			url = _this.data("url");
			page=1;
			pages = _this.data("page");
            var cur=$("#"+id);
            subNav.removeClass("active-01");
            _this.addClass("active-01");
            catoFram.hide();
            cur.scrollTop(0);
            cur.show();
			$('#'+id).find("ul").find("li").remove();
			$.ajax({
                        type: 'GET',
                        url: url+'?page=1'+'&num=15&'+Math.random(),
                        dataType: 'html',
                        success: function(json1){
							var dataObj1=eval("("+json1+")");
							var name;
							$.each(dataObj1,function(i,val){
								if(val.status == 1){name='充值'}else if(val.status == 2){name='投资'}else if(val.status == 3){name='赎回'}else if(val.status == 4){name='提现'}
								ul_obj.append('<li><div  class="record-Tcdiv"><p class="record-TcT" style="color:#242424;">'+name+'&nbsp;<span class="record-TcT" style="color:#a0aab4;">'+ unix_to_datetime(val.create_at)+'</span></p><p class="record-TcF" style="color:#000;">'+val.step +'</p></div></li>');
								
							});
                            
                            $(".loading").remove();//删除加载图片
                            ajax=!1;//注明已经完成ajax加载
                        },
                        error: function(xhr, type){
                            $(".loading").html("暂无内容！");
                        }
                    });
					
					
        });
		
		
        //需要 zepto.js支持
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
                        url: url+'?page='+page+'&num=15&'+Math.random(),
                        dataType: 'JSON',
                        success: function(json){
							var dataObj=eval("("+json+")");
							var name;
							$.each(dataObj,function(i,val){
								if(val.status == 1){name='充值'}else if(val.status == 2){name='投资'}else if(val.status == 3){name='赎回'}else if(val.status == 4){name='提现'}
								ul_obj.append('<li><div  class="record-Tcdiv"><p class="record-TcT" style="color:#242424;">'+name+'&nbsp;<span class="record-TcT" style="color:#a0aab4;">'+unix_to_datetime(val.create_at) +'</span></p><p class="record-TcF" style="color:#000;">'+val.step +'</p></div></li>');
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
<?php $this->endBlock(); ?>
<script src='<?php echo yii::$app->homeUrl; ?>js/jquery-1.7.2.min.js'></script>
<script src='<?php echo yii::$app->homeUrl; ?>js/zepto.min.js'></script>