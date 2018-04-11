<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
/* @var $this \yii\web\View */
/* @var $content string */
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
?>
<div id="header">
	<div class="top">
    	<div class="main">
        	<ul class="left top_le">
            	<li class="top_dianh">
                	<a></a>
                	<div class="phoneEwm">
                    	<img src="<?= $directoryAsset; ?>/images/phone.png" alt="客服二维码" />
                    </div>
                </li>
                <li class="top_xinl">
                	<a href=""></a>
                </li>
                <li class="top_weix">
                	<a></a>
                	<div class="ewmSinaDown">
                    	<img src="<?= $directoryAsset; ?>/images/erwm.png" alt="微信">
                    </div>
                </li>
                <div class="clear"></div>
            </ul>
            <ul class="right top_lr">         
                <li class="wxkf ggy">
                	<a class="kuanz">微信客服<b></b></a>
                    <div class="navDownCon">
                    	<img src="<?= $directoryAsset; ?>/images/erwm.png" />
                    </div>
                </li>
                <li class="kzdxz ggy">
                	<a class="kuanz">客户端下载<b></b></a>
                    <div class="kfewm">
                		<img src="<?= $directoryAsset; ?>/images/phone.png" />
                    </div>
                </li>
<!--                --><?php //if(Yii::$app->user->isGuest){?>
<!--                <li><a href="--><?//= yii\helpers\Url::to(['site/login'])?><!--">登录</a></li> -->
<!--                <li><a href="--><?//= yii\helpers\Url::to(['site/signup'])?><!--">注册</a></li>-->
<!--                --><?php //} elseif(Yii::$app->user->idParam == '__member') {?>
<!--                <li class="topbar-logmsg" style="border-left: none;">-->
<!--                    <div class="topbar-logout">-->
<!--                    	<em>-->
<!--	                        -->
<!--	                        <a href="--><?//= yii\helpers\Url::to(['account/overview']); ?><!--" style="padding: 0 4px;" rel="nofollow"> <i class="yicon yicon-user"><img alt="" width="15" style="vertical-align: middle;" src="--><?//= $directoryAsset; ?><!--/images/account.png"></i>--><?php //echo yii::$app->user->identity->username;?><!--  </a>-->
<!--	                        <span class="hidden-sm">，欢迎来到理财王理财！</span>-->
<!--	                        <a href="--><?//= yii\helpers\Url::to(['site/logout']); ?><!--" style="float:right;font-size:14px;"  rel="nofollow" class="logout-a">[退出]</a>-->
<!--	                        <div class="clear"></div>-->
<!--                    	</em>-->
<!--                </div>-->
<!--                </li>-->
<!--                      --><?php //}?><!--      -->
                <div class="clear"></div>
            </ul>
            <div class="clear"></div>
        </div>
    </div>
    <div class="top_nav main">
    	<div class="logo left">
        	<a href="<?= yii\helpers\Url::to(['site/index']); ?>"><img src="<?= $directoryAsset; ?>/images/logo.png" /></a>
        </div>
        <ul class="nav right">
            <li><a <?php if( \yii::$app->controller->action->id =='index' && yii::$app->controller->id == 'site' ){echo "class='hover'";} ?> href="<?= yii\helpers\Url::to(['site/index']); ?>">首 页</a></li>
            <li><a <?php if(\yii::$app->controller->action->id =='invest' || yii::$app->controller->id == 'investment' || \yii::$app->controller->action->id =='investinfos'){echo "class='hover'";} ?> href="<?= yii\helpers\Url::to(['investment/investing']); ?>">我要投资</a></li>
            <li><a <?php if(yii::$app->controller->id == 'insurance'){echo "class='hover'";} ?> href="<?= yii\helpers\Url::to(['insurance/plan']); ?>">安全保障</a></li>
            <li><a <?php if(yii::$app->controller->id == 'about' || yii::$app->controller->id == 'help'){echo "class='hover'";} ?> href="<?= yii\helpers\Url::to(['about/company']); ?>">关于我们</a></li>
<!--            <li><a --><?php //if(yii::$app->controller->id != 'site' && yii::$app->controller->id != 'insurance' && yii::$app->controller->id != 'about' && yii::$app->controller->id != 'investment'){echo "class='hover'";} ?><!-- href="--><?//= yii\helpers\Url::to(['account/overview']); ?><!--">我的账户</a></li>-->
            <div class="clear"></div>
        </ul>
        <div class="clear"></div>
    </div>
    <?php if(Yii::$app->controller->action->id == 'index' && yii::$app->controller->id != 'help' && yii::$app->controller->id != 'sign'):?>
      <div class="xj-banner">
        <div class="slide_container">
          <ul class="rslides" id="slider">
<!--            <li>-->
<!--              <a href="https://www.licaiwang.com/events/festival20150924?code=YW5mZpJgmGqRZGo=">-->
<!--              <img style="height:350px;" src="--><?php //echo $directoryAsset;?><!--/images/index350px4.jpg" alt="">-->
<!--              </a>-->
<!--            </li>-->
              <li>
                  <a href="//www.licaiwang.com/">
                      <img style="height:350px;" src="<?php echo $directoryAsset;?>/images/2015-10-15/pcbanner1.jpg" alt="">
                  </a>
              </li>
              <li>
                  <a href="//www.licaiwang.com/">
                      <img style="height:350px;" src="<?php echo $directoryAsset;?>/images/2015-10-15/pcbanner2.jpg" alt="">
                  </a>
              </li>
              <li>
                  <a href="//www.licaiwang.com/">
                      <img style="height:350px;" src="<?php echo $directoryAsset;?>/images/2015-10-15/pcbanner3.jpg" alt="">
                  </a>
              </li>
              <li>
                  <a href="//www.licaiwang.com/">
                      <img style="height:350px;" src="<?php echo $directoryAsset;?>/images/2015-10-15/pcbanner4.jpg" alt="">
                  </a>
              </li>
              <li>
                  <a href="//www.licaiwang.com/events/festival20151015intro">
                      <img style="height:350px;" src="<?php echo $directoryAsset;?>/images/2015-10-15/pcbanner5.jpg" alt="">
                  </a>
              </li>
          </ul>
        </div>
        <?php if(yii::$app->user->isGuest) {?>
        <div class="top_fot" style="height:auto; top: 19px;">
        	<div class="kz_bh">
            	<p>预计年化收益率</p>
                <h4>8.0%+</h4>
                <p>新浪支付第三方账户托管<br />
    			快速转让变现，实时免费提现</p>
                <a class="" href="<?= yii\helpers\Url::to(['site/signup'])?>">注册享收益</a>
            </div>
        </div>
   	 <?php } else {?>
   	 <div class="top_fot top_fotxj1" style="height:auto; top: 19px;">
            <div class="kz_bh">
                <p class="kz_bh1">预计年化收益率</p>
                <h4 class="kz_bh2">8.0%+</h4>
                <p class="kz_bh3">昨日收益：<span><?php if(isset($income_array['smoney'])){echo number_format($income_array['smoney'], 2, '.', '') ;}else{echo 0.00;}?>元</span></p>
                <p class="kz_bh4 clearFloat"><span>在投收益</span><span>累计收益</span></p>
                <p class="kz_bh5 clearFloat">
                <span><?php if($profit){echo number_format($profit, 2, '.', '') ;}else{echo 0.00;}?>元</span>
                <span><?php if($income_total){echo number_format($income_total, 2, '.', '') ;}else{echo 0.00;}?>元</span></p>
                <p class="kz_bh6"><a class="" href="<?php echo yii\helpers\Url::to(['account/overview'])?>">我的账户</a></p>
                <p class="kz_bh7 clearFloat"><a href="<?php echo yii\helpers\Url::to(['money/withdraw'])?>">提现</a><a href="<?php echo yii\helpers\Url::to(['money/recharge'])?>">充值</a></p>
            </div>
        </div>
      <?php }?>
  </div>
    <?php endif;?>
</div>
