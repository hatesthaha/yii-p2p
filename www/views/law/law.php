<?php 
 use yii\base\View;
?>
<div class="main aqbz">
    	<div class="left" id="left">
            <div class="page-con4">
                <p class="page-con41"><a href="<?php echo yii\helpers\Url::to(['setting/setting']);?>"><img width="100px" height="100px" src="<?php  echo yii::$app->homeUrl;?>upload/<?php echo $model["person_face"]; ?>" alt="头像" /></a></p>
                
                <?php if(yii::$app->user->identity->real_name){?>
                <p><?php echo yii::$app->user->identity->real_name;?></p>
                <?php } else {?>
                <p><?php echo yii::$app->user->identity->username;?></p>
                <?php }?>
            </div>
        	<ul class="Personal">
                <li class="PersonalT">账户信息</li>
            	<li class="Personal1"><a href="<?= yii\helpers\Url::to(['account/overview']); ?>">账户概况</a></li>
            	<li class="Personal2"><a href="<?= yii\helpers\Url::to(['money/recharge']); ?>">充值提现</a></li>
                <li class="Personal3"><a href="<?= yii\helpers\Url::to(['sign/index']); ?>">我的签到</a></li>
            	<li class="Personal4"><a href="<?= yii\helpers\Url::to(['setting/setting']); ?>">个人设置</a></li>
                <li class="Personal5"><a href="<?= yii\helpers\Url::to(['invitation/invitation']); ?>">邀请注册</a></li>
                <li class="Personal6 hover"><a href="<?= yii\helpers\Url::to(['law/law']); ?>">法律服务</a></li>
            </ul>
        </div>
        <div class="right" id="right" style="background:#f6f6f6; border:none;">
        	<?= View::render("@www/views/layouts/ucenter.php",['infos_rar'=>$infos_rar]); ?>
            <div class="right" id="right">
                <div class="detail_title"><p><?php if(isset($infos)){echo $infos->title;}?></p></div>
                <div class="anbz_nr">
				<?php if(isset($infos)){echo $infos->content;}?>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>