

<div class="main news_zd" style="margin:20px auto;">
    	<div class="news_title">
            <h3><?php echo $model->title;?></h3>
            <p><?php echo date('Y-m-d',$model->create_at);?></p>
            <a href="<?= yii\helpers\Url::to(['about/news'])?>">返回</a>
        </div>
        <div class="news_zdnr">
        	<?php echo $model->content;?>
        </div>
    </div>