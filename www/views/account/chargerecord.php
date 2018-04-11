<?php 
 use yii\base\View;
use yii\widgets\LinkPager;
$this->title = '充值记录';
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
            	<li class="Personal1 hover"><a href="<?= yii\helpers\Url::to(['account/overview']); ?>">账户概况</a></li>
            	<li class="Personal2"><a href="<?= yii\helpers\Url::to(['money/recharge']); ?>">充值提现</a></li>
                <li class="Personal3"><a href="<?= yii\helpers\Url::to(['sign/index']); ?>">我的签到</a></li>
            	<li class="Personal4"><a href="<?= yii\helpers\Url::to(['setting/setting']); ?>">个人设置</a></li>
                <li class="Personal5"><a href="<?= yii\helpers\Url::to(['invitation/invitation']); ?>">邀请注册</a></li>
               <!--  <li class="Personal6"><a href="<?= yii\helpers\Url::to(['law/law']); ?>">法律服务</a></li>  -->
            </ul>
        </div>
        <div class="right" id="right" style="background:#f6f6f6; border:none;">
        	<?= View::render("@www/views/layouts/ucenter.php",['infos_rar'=>$infos_rar]); ?>
            <div class="shadowBox page-con6">
                <ul class="page-con6tab clearFloat">
                    <li data-cato="overview">账户概况</li>
                    <li data-cato="chargerecord">充值记录</li>
                    <li data-cato="investlog">投资记录</li>
                    <li data-cato="withdrawrecord">提现记录</li>
                    <li data-cato="tradelog">交易记录</li>
                </ul>
                <div class="page-con6con" id="czjl">
                    <table class="page-con102">
                        <tr>
                            <th>序号</th>
                            <th>充值金额</th>
                            <th>充值时间</th>
                        </tr>
                        <?php if($recharge_log){ $i=$pages_recharge+1; foreach ($recharge_log as $K=>$V){?>
                        <tr>
                            <td><?= $i;$i++;?></td>
                            <td><?= number_format($V['step'],2);?>元</td>
                            <td><?=date('Y-m-d  H:i', $V['create_at']);?></td>
                        </tr>
                        <?php }} else {?>
                         <tr class="spage-con163tacon">
                             <td colspan="3" style="padding-top:30px;color:gray;font-size:14px;">暂无充值记录</td>
                         </tr>
                        <?php } ?>
                    </table>
                     <?php 
                   if($recharge_log) {
						// 显示分页
						echo LinkPager::widget([
						    'pagination' => $pages,
							'maxButtonCount' =>5
						]);
                   }
					?>           
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <script src="<?php echo Yii::$app->homeUrl;?>myAssetsLib/js/jquery-1.7.1.js"></script>
    <script>
    $(document).ready(function(){
        var catoFram=$(".page-con6con");
        var subNav=$(".page-con6tab li");
        catoFram[0].style.display="block";
        subNav[1].className += " cur-on";
        subNav.click(function(event){
            event.preventDefault();
            var _this=$(this);
            var id=_this.data("cato"); 
            location.href="<?php echo yii\helpers\Url::to(['account/" + id + "'])?>";
        });
        $(".page-con102 tr:even").addClass("trOdd");  
    });
</script>