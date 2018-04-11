<?php 
	use yii\helpers\Html;
	use yii\base\ErrorException;
	use common\models\cms\Category;
	use backend\models\Article;
	
 	$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
	echo Html::cssFile('@web/myAssetsLib/css/style.css');
	
	try
	{
		$parent_id = Category::findOne(['title'=>'帮助中心','status'=>1])->id;
		$category = Category::find()->where(['parent_id'=>$parent_id,'status'=>1])->asArray()->all();
		$left = array();
		foreach ($category as $K=>$V)
		{
			$has_parent_id = Category::find()->where(['parent_id'=>$V['id'],'status'=>1])->asArray()->all();

			//var_dump($has_parent_id);exit;
			$left_second = array();
			if($has_parent_id)
			{
				foreach ($has_parent_id as $K1=>$V1)
				{
					$left_second[] = Category::find()->where(['id'=>$V1['id'],'status'=>1])->asArray()->all();
				}
			}
			if(count($left_second) > 0)
			{
				$temp = Article::find()->where(['category_id'=>$V['id'],'status'=>1])->asArray()->all();
				foreach ($left_second as $K_slice=>$V_slice)
				{
					$temp = array_merge($temp,$left_second[$K_slice]);
				}
				$left[] = $temp;
			}
			else 
			{
				$left[] = Article::find()->where(['category_id'=>$V['id'],'status'=>1])->asArray()->all();
			}
		}
	}
	catch (ErrorException $e)
	{
		$left = array();
	}
?>
<style>
	body{
	 background:#fff;
	}
	 #header {
	  border-bottom: 1px solid #dcdcdc;
    }
</style>

<div class="main1">
<div class="contentWrapper">
			<div class="maincol">
				<?php echo $content;?>
			</div>
			<div class="sub_nav fl">
			<?php if($category){foreach ($category as $K=>$V){?>
				<h3 <?php if($K == 0){?> class="first"<?php }?> ><a class="show" href="#"><?php echo $V['title'];?></a></h3>
				<ul>
				<?php if($left){foreach ($left[$K] as $_K=>$_V){?>
					<li><a href="<?php if(isset($_V['category_id'])){echo yii\helpers\Url::to(['help/news','id'=>$_V['id']]);}else{echo yii\helpers\Url::to(['help/news_second','id'=>$_V['id'],'pid'=>$_V['parent_id']]);}?>"><?php echo $_V['title'];?></a></li>
				<?php }}?>
				</ul>
				<?php }}?>
			</div>
			
			<div style="clear: both;"></div>
		</div>
		</div>