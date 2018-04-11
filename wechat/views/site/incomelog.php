<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = '';
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
        display: block;
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
	    background:#f9f9f9;
	}
	.record-Tcdiv{
	      padding: 0;
	}

</style>
<div class="wh-container">
<section id="body_a"></section>

<section>
    <div class="record-Tabcon" id="sy">
        <ul class="record-Tcon">
            <?php if($incomelog) { foreach ($incomelog as $K=>$V) {?>
				<li>
                    <div  class="record-Tcdiv">
                        <p class="record-TcT" style="color:#a0aab4;"><?php echo date('Y-m-d H:i:s',$V['created_at']); ?></p>
                        <p class="record-TcF" style="color:#000; font-weight: bold;"><?php echo number_format($V['smoney'],2); ?></p>
                    </div>
                </li>
            <?php } } else {?>
            <li>
                <div  class="record-Tcdiv" style="color:black;">
                    暂无记录
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
		var pages = '<?= $incomelog_pages;?>'; //总页数
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
                        url: 'incomelist?page='+page+'&num=15&'+Math.random(),
                        dataType: 'html',
                        success: function(json){
							var dataObj=eval("("+json+")");
							$.each(dataObj,function(i,val){
								$(".record-Tcon").append('<li><div  class="record-Tcdiv"><p class="record-TcT" style="color:#a0aab4;">'+unix_to_datetime(val.created_at) +'</p><p class="record-TcF" style="color:#000; font-weight: bold;">'+val.smoney +'</p></div></li>');
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