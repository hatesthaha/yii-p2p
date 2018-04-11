<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = '加入我们';
?>

	<style>
	#content ul.expmenu li .menu p{
		padding:0;
		color: inherit;
	}
	
	</style>
	
<div id="content">
	
	<div class="main gywm">
    	<div class="left" id="left">
        	<ul>
                <?php if($left[0]['status']==1){?>
                    <li class="leib1"><a href="<?= yii\helpers\Url::to(['about/company']); ?>"><?= $left[0]['name'] ?></a></li>
                <?php
                } ?>
                <?php if($left[1]['status']==1){?>
                    <li class="leib2 "><a href="<?= yii\helpers\Url::to(['about/media']); ?>"><?= $left[1]['name'] ?></a></li>
                <?php
                } ?>
                <?php if($left[2]['status']==1){?>
                    <li class="leib3"><a href="<?= yii\helpers\Url::to(['about/partner']); ?>"><?= $left[2]['name'] ?></a></li>
                <?php
                } ?>
                <?php if($left[3]['status']==1){?>
                    <li class="leib4"><a href="<?= yii\helpers\Url::to(['about/news']); ?>"><?= $left[3]['name'] ?></a></li>
                <?php
                } ?>
                <?php if($left[4]['status']==1){?>
                    <li class="leib5"><a href="<?= yii\helpers\Url::to(['about/guarantee']); ?>"><?= $left[4]['name'] ?></a></li>
                <?php
                } ?>
                <?php if($left[5]['status']==1){?>
                    <li class="leib6  hover"><a href="<?= yii\helpers\Url::to(['about/join']); ?>"><?= $left[5]['name'] ?></a></li>
                <?php
                } ?>
                <?php if($left[6]['status']==1){?>
                    <li class="leib7"><a href="<?= yii\helpers\Url::to(['about/contact']); ?>"><?= $left[6]['name'] ?></a></li>
                <?php
                } ?>
                <?php if($left[7]['status']==1){?>
                    <li class="leib8"><a href="<?= yii\helpers\Url::to(['help/index']); ?>"><?= $left[7]['name'] ?></a></li>
                <?php
                } ?>
            </ul>
        </div>
        <div id="right" class="right">
        	<h2>加入我们</h2>
            <ul class="newsbz_nr expmenu">
            <?php if($models) {foreach ($models as $K=>$V){?>
            	<li>
                	<div style="display:block;" class="header"> 
                        <span class="label"><?php echo $V->title;?></span>
                        <span class="arrow up"></span>
                    </div>
                    <div class="menu" style="display:none;"><?php echo $V->content;?></div>
                </li>
             <?php }}?>
            </ul>
            <?php 
            if($pages)
            {
				// 显示分页
				echo LinkPager::widget([
				    'pagination' => $pages,
				]);
            }
			?>
        </div>
        <div class="clear"></div>
    </div>
    
</div>
<script src="<?php echo Yii::$app->homeUrl;?>myAssetsLib/js/jquery-1.7.1.js"></script>
<script type="text/javascript">
$(document).ready(function(){
						   
	/* 滑动/展开 */
	$("ul.expmenu li > div.header").click(function(){
												   
		var arrow = $(this).find("span.arrow");
	
		if(arrow.hasClass("up")){
			arrow.removeClass("up");
			arrow.addClass("down");
		}else if(arrow.hasClass("down")){
			arrow.removeClass("down");
			arrow.addClass("up");
		}
	
		$(this).parent().find("div.menu").slideToggle();
		
	});
	
});
</script>
