<?php 
 use yii\base\View;
use yii\widgets\LinkPager;
$this->title = '我的签到';
?>
<div class="main aqbz">
    	<div id="left" class="left">
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
                <li class="Personal3 hover"><a href="<?= yii\helpers\Url::to(['sign/index']); ?>">我的签到</a></li>
            	<li class="Personal4"><a href="<?= yii\helpers\Url::to(['setting/setting']); ?>">个人设置</a></li>
                <li class="Personal5"><a href="<?= yii\helpers\Url::to(['invitation/invitation']); ?>">邀请注册</a></li>
               <!--  <li class="Personal6"><a href="<?= yii\helpers\Url::to(['law/law']); ?>">法律服务</a></li>  -->
            </ul>
        </div>
        <div style="background:#f6f6f6; border:none;" id="right" class="right">
        	<?= View::render("@www/views/layouts/ucenter.php",['infos_rar'=>$infos_rar]); ?>
            <div class="shadowBox spage-con16">
                <p class="spage-con16T"><span>我的签到</span></p>
                <div class="spage-con16div">
                    
                    <div>
                        <ul class="spage-con162 clearFloat">
                        </ul>
                        <div id="wsy" class="spage-con163">
                            <table class="spage-con163table">
                                <tbody><tr class="spage-con163taconT">
                                    <th>奖励金额</th>
                                    <th>签到时间</th>
                                </tr>
                                <?php if($sign_Infos) { foreach ($sign_Infos as $K=>$V) {?>
                                <tr class="spage-con163tacon">
                                    <td>￥ <?php echo number_format($V['sign_in_money'],2); ?>元</td>
                                    
                                    <td><?php echo date('Y-m-d H:i',$V['sign_in_time']); ?></td>
                                    
                                </tr>
                                <?php } } else {?>
                                <tr class="spage-con163tacon">
                                 <td colspan="4" style="padding-top:30px;color:gray;font-size:14px;">暂无签到记录</td>
                                </tr>
                                <?php }?>
                            </tbody></table>
                            <?php echo LinkPager::widget([
                            	'pagination' => $pages_sign,
								'maxButtonCount' =>5
                            ])?>
                        </div>
                </div>
			   </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>