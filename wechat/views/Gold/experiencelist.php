<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = '';
?>
<style>
    .whxj-list{
        color: #9d9d9d;
        font-size: 1.3rem;
    }
     .whxj-list p{
        line-height: 2rem;
     }
    .whxj-list li{
        padding: 1rem;
    }
   .whxj-list li:nth-child(even){
        background:#f8f8f8;
    }
    .whxj-onep{
        text-align: right;
    }
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
<div class="wh-container">
<div id="body_a"></div>
    <div >
        <ul class="whxj-list">
         <?php if($list) { foreach ($list as $K=>$V) {?>
            <li class="clearFloat">
                <p class="whxj-onep clearFloat"><span class="floatLeft"><?= $V[title];?></span><span><?= number_format($V[money],2);?></span></p>
                <?php if($V[status] == -1){?>
                <p><span>已失效！</span></p>
                <?php }?>
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
        $("#body_a").hide();
		
        //需要 zepto.js支持
		var page=1;//当前页
		var pages = '<?= $list_pages;?>'; //总页数
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
                        url: 'experiencedatas?page='+page+'&num=15&'+Math.random(),
                        dataType: 'html',
                        success: function(json){
			var dataObj=eval("("+json+")");
			$.each(dataObj,function(i,val){
				$(".whxj-list").append('<li class="clearFloat"><p class="whxj-onep clearFloat"><span class="floatLeft">'+val.title+'</span><span>'+val.money+'</span></p>');
                                                   if(val.status == -1) 
                                                   {
                                                        $("li:last-child").append('<p><span>已失效！</span></p>');
                                                   }
                                                    $(".whxj-list").append('</li>');
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