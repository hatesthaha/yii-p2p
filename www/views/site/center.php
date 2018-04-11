<?php 
 use yii\base\View;
 use yii\widgets\LinkPager;
?>
<div class="main aqbz">
    	<div id="left" class="left">
            <div class="page-con4">
                <p class="page-con41"><a href="#"><img width="100px" height="100px" alt="头像" src="images/head-portrait.jpg"></a></p>
                <p><?php echo yii::$app->user->identity->username;?></p>
                <p><?php if(isset($infos_rar['phone'])) echo $infos_rar['phone'];?></p>
            </div>
        	<ul class="Personal">
                <li class="PersonalT">账户信息</li>
            	<li class="Personal1 hover"><a href="<?= yii\helpers\Url::to(['site/center']); ?>">账户概况</a></li>
            	<li class="Personal2"><a href="<?= yii\helpers\Url::to(['site/recharge']); ?>">充值提现</a></li>
                <li class="Personal3"><a href="<?= yii\helpers\Url::to(['site/gift']); ?>">我的礼券</a></li>
            	<li class="Personal4"><a href="<?= yii\helpers\Url::to(['site/setting']); ?>">个人设置</a></li>
                <li class="Personal5"><a href="<?= yii\helpers\Url::to(['site/invitation']); ?>">邀请注册</a></li>
                <li class="Personal6"><a href="<?= yii\helpers\Url::to(['site/law']); ?>">法律服务</a></li>
            </ul>
        </div>
        <div style="background:#f6f6f6; border:none;" id="right" class="right">
        	<?= View::render("@www/views/layouts/ucenter.php",['infos_rar'=>$infos_rar]); ?>
            <div class="shadowBox page-con6">
                <ul class="page-con6tab clearFloat">
                    <li data-cato="zhgk">账户概况</li>
                    <li data-cato="tx">投资记录</li>
                    <li data-cato="bd">交易记录</li>
                </ul>
                <div id="zhgk" class="page-con6con" style="display: block;">
                    <div class="spage-con13">
                        <div class="spage-con131 clearFloat">
                            <div class="left">
                                <p>总资产：<span class="red-awod"> <?php echo $infos_rar['amount_total']; ?>元</span></p>
                                <ul>
                                    <li>当前投资总额：<span class="cor4-f">0元</span></li>
                                    <li>账户可用余额：<span class="cor4-f">0元</span></li>
                                    <li>账户冻结资金：<span class="cor4-f">0元</span></li>
                                </ul>
                            </div>
                            <div class="left">
                                <p>总收益：<span class="red-awod"><?php echo $infos_rar['income_total']; ?>元</span></p>
                                <ul>
                                    <li>当前投资总额：<span class="cor4-f"><?php echo $infos_rar['invest_total']; ?>元</span></li>
                                    <li>账户可用余额：<span class="cor4-f"><?php echo $infos_rar['balance']; ?>元</span></li>
                                    <li>账户冻结资金：<span class="cor4-f"><?php echo $infos_rar['freeze']; ?>元</span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="spage-con132">
                            <p class="spage-con132T">* 当前投资记录</p>
                            <table class="spage-con132table">
                                <tbody><tr>
                                    <th>项目名称</th>
                                    <th>投资金额</th>
                                    <th>年化利率</th>
                                    <th>计息时间</th>
                                </tr>
                                <?php foreach ($invest_log as $K=>$V) {?>
                                <tr>
                                    <td><?php echo $V['title']; ?></td>
                                    <td>￥<?php echo $V['money']; ?>元</td>
                                    <td><?php echo $V['rate']; ?>%</td>
                                    <td><?php echo date("Y-m-d",$V['start_at']+86400); ?></td>
                                </tr>
                                <?php }?>
                            </tbody></table>
                            <p><a class="look-more look-morenob" href="#">查看全部&gt;&gt;</a></p>
                        </div>
                    </div>
                </div>
                <div id="tx" class="page-con6con">
                    <table class="page-con102">
                        <tbody><tr class="trOdd">
                            <th>投资金额</th>
                            <th>投资状态</th>
                            <th>投资时间</th>
                        </tr>
                        <?php if(isset($array_investlog)) { foreach ($array_investlog as $K => $V) {?>
                        <tr>
                            <td><?= $V['step'];?></td>
                            <td><?= $V['remark'];?></td>
                            <td><?=date('Y-m-d  H:i', $V['create_at']);?></td>
                        </tr>
                        <?php }}?>
                      </tbody>
                   </table>
                   <?php 
                   if(isset($array_investlog)) {
						// 显示分页
						echo LinkPager::widget([
						    'pagination' => $pages,
						]);
                   }
					?>
                </div>
                
                <div id="bd" class="page-con6con">
                    <table class="page-con102">
                        <tbody><tr class="trOdd">
                            <th>交易金额</th>
                            <th>交易状态</th>
                            <th>交易时间</th>
                        </tr>
                        <?php if(isset($array_tradelog)) { foreach ($array_tradelog as $K => $V) {?>
                        <tr>
                            <td><?= $V['step'];?></td>
                            <td><?= $V['remark'];?></td>
                            <td><?=date('Y-m-d  H:i', $V['create_at']);?></td>
                        </tr>
                        <?php }}?>
                      </tbody>
                   </table>
                   <?php 
                   if(isset($array_tradelog)) {
						// 显示分页
						echo LinkPager::widget([
						    'pagination' => $pages_trade,
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
        subNav[0].className += " cur-on";
        subNav.click(function(event){
            event.preventDefault();
            var _this=$(this);
            var id=_this.data("cato"); 
            var cur=$("#"+id);
            subNav.removeClass("cur-on");
            _this.addClass("cur-on");
            catoFram.hide();
            cur.scrollTop(0);        
            cur.show();
        });
        $(".Color-change tr:odd").addClass("trOdd"); 
        $(".Color-change2 tr:odd").addClass("trOdd"); 
        $(".Color-change3 tr:odd").addClass("trOdd"); 
    });
</script>