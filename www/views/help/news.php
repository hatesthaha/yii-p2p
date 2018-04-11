
		<div>
		        <div class="tit_box border_b">
		          <h3 class="title"><strong><?php echo $category_name;?></strong> - <strong class="f12"><?php echo $infos->title;?></strong></h3>
		        </div>
		        <div class="ht30"></div>
		        <div class="con_box">
		          <h1 class="art_tit"><?php echo $infos->title;?></h1>
		          <div class="art_info"><a href="#"><i class="print_ico"></i>打印</a> <a class="" href="#"><i class="bookmarks_ico"></i>收藏</a> <a href="<?php echo yii\helpers\Url::to(['help/index']);?>">返回帮助首页</a></div>
		          <div class="art_con">
		            <?php echo $infos->content;?>
		          </div>
		        </div>
 		</div>