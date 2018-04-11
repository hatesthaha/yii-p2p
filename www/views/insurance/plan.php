<?php
$this->title = '安全保障';
?>
<div class="main aqbz">
    	<div class="left" id="left">
        	<ul class="aqbz-tabbtn">
        	<?php if($infos) { foreach ($infos as $K=>$V){?>
            	<li class="leib<?php echo $K+1;?><?php if($K == 0){echo ' hover';}?>" data-cato="aqbz<?php echo $K;?>"><a href=""><?php echo $V['title'];?></a></li>
            <?php }}?>
            </ul>
        </div>
        <div class="right" id="right">
          <?php if($infos) { foreach ($infos as $K=>$V){?>
            <div class="aqbz-tabcon" id="aqbz<?php echo $K;?>">
            	<h2><?php echo $V['title'];?></h2>
                <div class="anbz_nr">
                	<?php echo $V['content'];?>
                </div>
            </div>
          <?php }}?>
        </div>
        <div class="clear"></div>
    </div>

<script src="<?php echo Yii::$app->homeUrl;?>myAssetsLib/js/jquery-1.9.1.min.js"></script>     
<script>
    $(document).ready(function(){
        var catoFram=$(".aqbz-tabcon");
        var subNav=$(".aqbz-tabbtn li");
        catoFram[0].style.display="block";
        subNav[0].className += " hover";
        subNav.click(function(event){
            event.preventDefault();
            var _this=$(this);
            var id=_this.data("cato"); 
            var cur=$("#"+id);
            subNav.removeClass("hover");
            _this.addClass("hover");
            catoFram.hide();
            cur.scrollTop(0);        
            cur.show();
        });
    });
</script>