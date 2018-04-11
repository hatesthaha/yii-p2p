<?php 
 use yii\base\View;
use yii\widgets\LinkPager;
?>
<div class="main aqbz">
    	<div id="left" class="left">
            <div class="page-con4">
                <p class="page-con41"><a href="#"><img width="100px" height="100px" alt="头像" src="images/head-portrait.jpg"></a></p>
                <p>姓名</p>
                <p>13188888888888</p>
            </div>
        	<ul class="Personal">
                <li class="PersonalT">账户信息</li>
            	<li class="Personal1"><a href="<?= yii\helpers\Url::to(['site/center']); ?>">账户概况</a></li>
            	<li class="Personal2"><a href="<?= yii\helpers\Url::to(['site/recharge']); ?>">充值提现</a></li>
                <li class="Personal3 hover"><a href="<?= yii\helpers\Url::to(['site/gift']); ?>">我的礼券</a></li>
            	<li class="Personal4"><a href="<?= yii\helpers\Url::to(['site/setting']); ?>">个人设置</a></li>
                <li class="Personal5"><a href="<?= yii\helpers\Url::to(['site/invitation']); ?>">邀请注册</a></li>
                <li class="Personal6"><a href="<?= yii\helpers\Url::to(['site/law']); ?>">法律服务</a></li>
            </ul>
        </div>
        <div style="background:#f6f6f6; border:none;" id="right" class="right">
        	<?= View::render("@www/views/layouts/ucenter.php",['infos_rar'=>$infos_rar]); ?>
            <div class="shadowBox spage-con16">
                <p class="spage-con16T"><span>我的礼券</span></p>
                <div class="spage-con16div">
                    <p class="spage-con161 clearFloat"><span class="left">点击签到领取礼券</span><a href="#" class="right">签到</a></p>
                    <div>
                        <ul class="spage-con162 clearFloat">
                            <li data-cato="wsy" class=" cur-on3">未使用</li>
                            <li data-cato="ysy">已使用</li>
                            <li data-cato="ygq">已过期</li>
                        </ul>
                        <div id="wsy" class="spage-con163" style="display: block;">
                            <table class="spage-con163table">
                                <tbody><tr class="spage-con163taconT">
                                    <th>利率</th>
                                    <th>有效期</th>
                                    <th>类型</th>
                                </tr>
                                <?php if(isset($gift_Notuse_Infos)) { foreach ($gift_Notuse_Infos as $K=>$V) {?>
                                <tr class="spage-con163tacon">
                                    <td><?php echo $V['rate'] * 100; ?>%</td>
                                    <td><?php echo date("Y-m-d", $V['validity_start_at']);?> &mdash; <?php echo date("Y-m-d", $V['validity_out_at']);?></td>
                                    <td><?php echo $V['title']; ?></td>
                                </tr>
                                <?php } }?>
                            </tbody></table>
                            <?php echo LinkPager::widget([
                            	'pagination' => $pages_Notuse,
                            ])?>
                        </div>
                        <div id="ysy" class="spage-con163" style="display: none;">
                            <table class="spage-con163table">
                                <tbody><tr class="spage-con163taconT">
                                    <th>利率</th>
                                    <th>已使用</th>
                                    <th>类型</th>
                                </tr>
                                <?php if(isset($gift_Used_Infos)) { foreach ($gift_Used_Infos as $K=>$V) {?>
                                <tr class="spage-con163tacon">
                                    <td><?php echo $V['rate'] * 100; ?>%</td>
                                    <td><?php echo date("Y-m-d", $V['validity_start_at']);?> &mdash; <?php echo date("Y-m-d", $V['validity_out_at']);?></td>
                                    <td><?php echo $V['title']; ?></td>
                                </tr>
                                <?php } }?>
                            </tbody></table>
                            <?php echo LinkPager::widget([
                            	'pagination' => $pages_Used,
                            ])?>
                        </div>
                        <div id="ygq" class="spage-con163" style="display: none;">
                            <table class="spage-con163table">
                                <tbody><tr class="spage-con163taconT">
                                    <th>利率</th>
                                    <th>已过期</th>
                                    <th>类型</th>
                                </tr>
                                <?php if(isset($gift_Expire_Infos)) { foreach ($gift_Expire_Infos as $K=>$V) {?>
                                <tr class="spage-con163tacon">
                                    <td><?php echo $V['rate'] * 100; ?>%</td>
                                    <td><?php echo date("Y-m-d", $V['validity_start_at']);?> &mdash; <?php echo date("Y-m-d", $V['validity_out_at']);?></td>
                                    <td><?php echo $V['title']; ?></td>
                                </tr>
                                <?php } }?>
                            </tbody></table>
                            <?php echo LinkPager::widget([
                            	'pagination' => $pages_Expire,
                            ])?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="clear"></div>
    </div>
    <script src="<?php echo Yii::$app->homeUrl;?>myAssetsLib/js/jquery-1.7.1.js"></script>
    <script>
    $(document).ready(function(){
        $(".spage-con163tacon:odd").addClass("trOdd"); 
        var catoFram=$(".spage-con163");
        var subNav=$(".spage-con162 li");
        catoFram[0].style.display="block";
        subNav[0].className += " cur-on3";
        subNav.click(function(event){
            event.preventDefault();
            var _this=$(this);
            var id=_this.data("cato"); 
            var cur=$("#"+id);
            subNav.removeClass("cur-on3");
            _this.addClass("cur-on3");
            catoFram.hide();
            cur.scrollTop(0);        
            cur.show();
        });

    });
</script>