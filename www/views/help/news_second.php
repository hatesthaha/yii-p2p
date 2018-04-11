
		<div>
		        <div class="tit_box border_b">
		          <h3 class="title"><strong><?php echo $category_name;?></strong> - <strong class="f12"><?php echo $second_name;?></strong></h3>
		        </div>
		        
		        
		        <div class="ht15">
				</div>
				<ul class="news_list">
			<?php if($infos){ foreach ($infos as $K=>$V){?>
					<li><a href="<?php echo yii\helpers\Url::to(['help/news','id'=>$V['id']]);?>"><?php echo $V['title'];?></a></li>
		    <?php }}?>
				</ul>
				
 		</div>