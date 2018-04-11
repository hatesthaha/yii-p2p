<?php 
 use yii\base\View;
use yii\helpers\Html;
 $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
$this->title = '提现';
?>
<?=Html::cssFile('@web/myAssetsLib/css/bootstrap.css')?>
<?=Html::jsFile('@web/myAssetsLib/js/jquery-1.9.1.min.js')?>
<?=Html::jsFile('@web/myAssetsLib/js/bootstrap.js')?>
<?=Html::jsFile('@web/myAssetsLib/js/bootbox.js')?>
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
            	<li class="Personal2 hover"><a href="<?= yii\helpers\Url::to(['money/recharge']); ?>">充值提现</a></li>
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
                    <li data-cato="bindcard">绑定银行卡</li>
                    <li data-cato="recharge">充值</li>
                    <li data-cato="redemption">赎回</li>
                    <li data-cato="withdraw">提现</li>
                </ul>
                
                <div class="page-con6con" id="tx">
                    <p class="xj-wan002">温馨提示：内测期提现 <span>15：00</span> 前 <span>T+1</span>，<span>15：00</span> 后 <span>T+2</span></p>
                <div id="loading" style="display: none;margin:auto 0;z-index:9999;position:relative; top:20px; left:392px;"><img width="23px" height="23px" alt="" src="<?php echo $directoryAsset;?>/images/loading.gif" align="center" /></div>
                        <div class="xjwanpage-con7 zhuce">
                            <div class="xj-wan9 clearFloat"><span class="xj-wan10L">可提现金额：</span>
                                <p class="xj-wan10R"><font class="org-color" style="font-size: 22px;"><?php echo number_format($balance,2);?></font> 元</p>
                            </div>
                            <div class="xj-wan19 clearFloat">
                                <span class="xj-wan19L">银行卡：</span>
                                <div class="xj-wan19R clearFloat" style="width:548px;background:rgba(0, 0, 0, 0) url('<?php echo $directoryAsset;?>/images/xj-img0256.jpg') no-repeat scroll 0 0 / 100% 100%;">
                                    <img class="bank-icon" src="<?php echo $directoryAsset;?>/images/bank/<?php echo $logo_bind; ?>.png" alt="" />
                                    <span class="bank-word"><?php echo $result_bind["bank_name"];?>  <?php echo substr($result_bind["bank_account_no"], 0, 4).'************'.substr($result_bind["bank_account_no"], strlen($result_bind["bank_account_no"])-3, 3);?></span>
                                    <span class="bank-info1">每日限提<?php if($withdraw_max){echo $withdraw_times;}?>次</span>
                                    <span class="bank-info2">限额：<?php echo $withdraw_min; ?> ~ <?php echo $withdraw_max; ?>
                                        元/次</span>
                                </div>
                            </div>
                            <p class="xj-wan9"><span>提现金额：</span>
                            <span class="text"><input type="text" id="money" name="czje" placeholder="请输入提现金额" />元</span></p>
                            <a class="check-btn" colspan="2"><input type="button" value="立即提现" class="page-con82" id="recharge" /></a>
                        </div>
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
        subNav[3].className += " cur-on";
        subNav.click(function(event){
            event.preventDefault();
            var _this=$(this);
            var id=_this.data("cato"); 
            location.href="<?php echo yii\helpers\Url::to(['money/" + id + "'])?>";
        });
        $(".page-con102 tr:even").addClass("trOdd");  
    });
</script>
    
<script>
var _wait = 0;
function _time() {
	if (_wait != 0) {
		_wait--;
		setTimeout(function() {
		  _time();
		},1000);
	}
}

var amount_total = <?php echo sprintf("%0.2f",$balance);?>;
var reg = /^[0-9]+[.]?[0-9]*$/;
$(document).ready(function(){
$(".page-con82").click(function(){
	var money = $("#money").val();
	var min = <?php echo $withdraw_min; ?>;
    var max = <?php echo $withdraw_max; ?>;
    var times = <?php echo $withdraw_times; ?>;
    var today_num = <?php echo $today_num; ?>;
	if(money == '')
	{
		alert("请输入提现金额");
    }
	else if(today_num >= times)
    {
		alert('今日提现次数不能超过'+times+'次');
    }
	else if (parseInt(money) < min || parseInt(money) > max) {
        alert("单笔限额：" + min + " ~ " + max + '元');
    }
	else if(!reg.test(money))
    {
        alert("金额格式不正确");//$(".page-con214").text("金额格式不正确");
    }
    else if(parseInt(money) > amount_total)
	{
    	alert("可提现金额不足");//$(".page-con214").text("可提现金额不足");
	}
	else if(confirm('请确认是否提现？'))
		{
			if(_wait == 0)
	        {
				_wait = 30;
				_time();
		    }
	        else
	        {
				alert('为了保证安全，请等待'+_wait+'秒再次操作！');
				return false;
			}
            bootbox.dialog({
                message: '请耐心等待，正在向服务器提交任务...'
            });
			$.post(
					'<?php echo yii\helpers\Url::to(['money/withdraw']);?>',
					{'money':money,'_csrf':"<?php echo Yii::$app->request->getCsrfToken(); ?>"},
					function(data){
                            bootbox.hideAll()
							alert(data);
							if(data == '提现成功')
							{
								window.location.href=window.location.href;
							}
						}		
						




					);
		}
	
	
	});

});

</script>