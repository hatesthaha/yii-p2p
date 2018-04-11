<?php
use yii\widgets\LinkPager;
use common\models\base\cms\Category;
$this->title = '最新动态';
?>
	<div class="main ">
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
                    <li class="leib4  hover"><a href="<?= yii\helpers\Url::to(['about/news']); ?>"><?= $left[3]['name'] ?></a></li>
                <?php
                } ?>
                <?php if($left[4]['status']==1){?>
                    <li class="leib5"><a href="<?= yii\helpers\Url::to(['about/guarantee']); ?>"><?= $left[4]['name'] ?></a></li>
                <?php
                } ?>
                <?php if($left[5]['status']==1){?>
                    <li class="leib6"><a href="<?= yii\helpers\Url::to(['about/join']); ?>"><?= $left[5]['name'] ?></a></li>
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
        <div class="right" id="right">
			<h2>最新动态</h2>
			<ul class="newsbd_nr">
			 <?php if($models){foreach ($models as $K=>$V){?>
            	<li>
                    <div class="left wezi">
                    	<h3>
                    		<a href="<?php echo yii\helpers\Url::to(['about/content','id'=>$V->id]);?>"><?php echo $V->title; ?></a>
                    	</h3>
                        <p>
                        	<?php echo mb_substr(strip_tags($V->content), 0,90,'utf-8'); ?>......
                        	<a href="<?php echo yii\helpers\Url::to(['about/content','id'=>$V->id]);?>">查看详情&gt;&gt;</a>
                        </p>
                    </div>
                    <div class="clear"></div>
                </li>
                <?php }}?>
                <div class="clear"></div>
            </ul>
            <?php 
            if($pages)
            {
				// 显示分页
				echo LinkPager::widget([
				    'pagination' => $pages,
					'maxButtonCount' =>5
				]);
            }
			?>
		  </div>
        <div class="clear"></div>
    </div>
    