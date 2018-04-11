<?php
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
?>
<div class="main">
	<div class="zhuce" style="margin-top:30px;margin-bottom:30px;">
		<div class="xj-xjwan"> 
			<div class="clearFloat">
				<p class="xj-xjwanl"><img width="85px" height="82px" src="<?php echo $directoryAsset; ?>/images/xj-success.png" alt=""></p>
				<div class="xj-xjwanW">
					<p class="xj-xjwanWbig">密码重置成功!</p>
					<p class="xj-xjwanWsmall">请牢记您的新密码~</p>
				</div>
			</div>
			<p class="xj-xjwanBtn">
				<a href="<?php echo yii\helpers\Url::to(['site/login']); ?>">前往登录</a>
			</p>
		</div>
	</div>
</div>