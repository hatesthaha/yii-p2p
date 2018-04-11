	
<div id="content">
	
	<div class="main gywm">
    	<div class="left" id="left">
        	<ul>
            	<li class="leib1 hover"><a href="<?= yii\helpers\Url::to(['about/company']); ?>">企业介绍</a></li>
            	<li class="leib2"><a href="<?= yii\helpers\Url::to(['about/media']); ?>">媒体报道</a></li>
                <li class="leib3"><a href="<?= yii\helpers\Url::to(['about/partner']); ?>">合作伙伴</a></li>
            	<li class="leib4"><a href="<?= yii\helpers\Url::to(['about/news']); ?>">最新动态</a></li>
                <li class="leib5"><a href="<?= yii\helpers\Url::to(['about/join']); ?>">加入我们</a></li>
            	<li class="leib6 "><a href="<?= yii\helpers\Url::to(['about/contact']); ?>">联系我们</a></li>
                <li class="leib7"><a href="<?= yii\helpers\Url::to(['about/help']); ?>">帮助中心</a></li>
            </ul>
        </div>
        <div class="right" id="right">
        	<?= $content; ?>
        </div>
        <div class="clear"></div>
    </div>
    
</div>