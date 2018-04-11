

<div class="main news_zd">
    	<div class="news_title">
            <h3><?php echo $model->title;?></h3>
            <p><?php echo date('Y-m-d',$model->create_at);?><span>万虎网络</span></p>
            <a href="<?= yii\helpers\Url::to(['about/news'])?>">返回</a>
        </div>
        <div class="news_zdnr">
        	<?php echo $model->content;?>
        </div>
    </div>