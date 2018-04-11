<?php 
	use yii\helpers\Html;
		
	$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
	echo Html::cssFile('@web/myAssetsLib/css/style.css');
	$this->title='帮助中心';
?>


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
					<li class="leib6"><a href="<?= yii\helpers\Url::to(['about/join']); ?>"><?= $left[5]['name'] ?></a></li>
					<?php
				} ?>
				<?php if($left[6]['status']==1){?>
					<li class="leib7"><a href="<?= yii\helpers\Url::to(['about/contact']); ?>"><?= $left[6]['name'] ?></a></li>
					<?php
				} ?>
				<?php if($left[7]['status']==1){?>
					<li class="leib8  hover"><a href="<?= yii\helpers\Url::to(['help/index']); ?>"><?= $left[7]['name'] ?></a></li>
					<?php
				} ?>
			</ul>
		</div>
		<div id="right" class="right">
			<div class="rqq-maincol_in">
				<div>
					<img src="<?php echo $directoryAsset;?>/images/banner_help.png" alt="">
				</div>
				<div class="ht15"></div>
				<div class="tit_box border_b" style="padding-left: 15px;">
					<h3 class="title font14"><strong>帮助导航</strong></h3>
				</div>
				<div class="help_dh" style="padding-left: 15px;" id="new_help">
					<div class="mbox">
						<div class="toptit">
							操作流程
						</div>
						<div class="cbox">
							<a href="<?php echo yii\helpers\Url::to(['help/news','id'=>75]);?>">注册</a> | <a href="<?php echo yii\helpers\Url::to(['help/news','id'=>76]);?>">认证</a> | <a href="<?php echo yii\helpers\Url::to(['help/news','id'=>77]);?>">绑定银行卡</a> | <a href="<?php echo yii\helpers\Url::to(['help/news','id'=>80]);?>">充值</a> | <a href="<?php echo yii\helpers\Url::to(['help/news','id'=>81]);?>">投资</a> | <a href="<?php echo yii\helpers\Url::to(['help/news','id'=>82]);?>">赎回</a> | <a href="<?php echo yii\helpers\Url::to(['help/news','id'=>83]);?>">提现</a>
						</div>
						<div class="bottom">
							&nbsp;
						</div>
					</div>
					<div class="mbox">
						<div class="toptit">
							常见问答
						</div>
						<div class="cbox">
							<a href="<?php echo yii\helpers\Url::to(['help/news','id'=>84]);?>">忘记密码</a> | <a href="<?php echo yii\helpers\Url::to(['help/news','id'=>85]);?>">邀请码</a>
						</div>
						<div class="bottom">
							&nbsp;
						</div>
					</div>
					<div class="mbox">
						<div class="toptit">
							联系客服
						</div>
						<div class="cbox">
							客服邮箱：<a href="mailto:kefu@licaiwang.com">kefu@licaiwang.com</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;客服热线：4006 985 185&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;营业地址：北京市朝阳区万达广场6号楼606
						</div>
						<div class="bottom">
							&nbsp;
						</div>
					</div>
					<?php if(!empty($infos)){?>
						<div class="mbox" style="margin-top: 30px;">
							<?php if (isset($infos)) {
								echo $infos->content;
							} ?>
						</div>
						<?php
					} ?>

				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>

</div>

