<?php 
 use yii\base\View;
 $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
$this->title = '绑定银行卡';
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
            	<li class="Personal2 hover"><a href="<?= yii\helpers\Url::to(['money/recharge']); ?>">充值提现</a></li>
                <li class="Personal3"><a href="<?= yii\helpers\Url::to(['sign/index']); ?>">我的签到</a></li>
            	<li class="Personal4"><a href="<?= yii\helpers\Url::to(['setting/setting']); ?>">个人设置</a></li>
                <li class="Personal5"><a href="<?= yii\helpers\Url::to(['invitation/invitation']); ?>">邀请注册</a></li>
                <!--  <li class="Personal6"><a href="<?= yii\helpers\Url::to(['law/law']); ?>">法律服务</a></li>  -->
            </ul>
        </div>
        <div class="right" id="right" style="background:#f6f6f6; border:none;">
        	<?= View::render("@www/views/layouts/ucenter.php",['infos_rar'=>$infos_rar]); ?>
            <div class="shadowBox page-con6" style="margin: 0 0 20px;">
                <ul class="page-con6tab clearFloat">
                    <li data-cato="bindcard">绑定银行卡</li>
                    <li data-cato="recharge">充值</li>
                    <li data-cato="redemption">赎回</li>
                    <li data-cato="withdraw">提现</li>
                </ul>
                <div class="page-con6con" id="bd">
                    <p class="xj-wan002">为保证绑卡成功，您的银行卡开户姓名必须为  <span><?php if($result){echo $result['real_name'];}?></span></p>
                <?php if(!isset($result_bind['bank_account_no'])){?>
                    <form action="#">
                       <input type="hidden" id="request_no" value="" />
                       <input type="hidden" id="ticket" value="" />
                        <table class="page-cons30" style="margin-bottom:0; margin-top:0;">
                            <tr>
                                <td class="tr">银行卡号：</td>
                                <td><input type="text" id="bankcard" name="bankcard" placeholder="请填写有效银行卡号" style="background:none" /></td>
                            </tr>
                            <tr>
                                <td class="tr">姓名：</td>
                                <td><input type="text" id="username" name="username" value="<?php if($result){echo $result['real_name'];}?>" disabled /></td>
                            </tr>
                            <tr>
                                <td class="tr">身份证号：</td>
                                <td><input type="text" id="idcard" name="idcard" value="<?php if($result){echo $result['idcard'];}?>" disabled placeholder="请输入您的身份证号" /></td>
                            </tr>
                            <tr>
                                <td class="tr">银行预留手机号：</td>
                                <td><input id="phone" type="text" name="phone" placeholder="请填写有效手机号" style="background:none" /></td>
                            </tr>
                            
                            <tr>
                                <td class="tr">验证码：</td>
                                <td class="code"><input type="text" id="code" name="code" style="background:none" /><a id="codea">发送验证码</a></td>
                            </tr>
                           
                            <tr>
                                <td class="check-btn" colspan="2"><input id="register" type="button" value="开始绑定" /></td>
                            </tr>
                             <tr>
                                <td colspan="2"><div id="loading" style="display: none;margin:auto 0;z-index:9999;position:relative; top:-90px; left:162px;"><img width="23px" height="23px" alt="" src="<?php echo $directoryAsset;?>/images/loading.gif" align="center" /></div></td>
                            </tr>
                        </table>
                    </form>
                    <div class="xj-wan001">
                        <h2>提现银行卡说明：</h2>
                        <p>为了保证您的资金安全，您提现的银行卡开户姓名必须和实名认证的姓名一致，否则无法提现，目前仅限于中国大陆境内地区的银行，暂不支持其他地区的银行或非人民币币种的账号。</p>
                        <p>目前平台仅支持以下银行：中国工商银行、中国农业银行、中国银行、中国建设银行、交通银行、中信银行、光大银行、华夏银行、平安银行、招商银行、兴业银行、上海浦东发展银行、邮政储蓄、渤海银行、南京银行、上海银行、浙商银行、广发银行、北京银行、民生银行，</p>
                        <p>请您填写完整准确的开户行信息，如不能确定，请拨打银行卡上的客服热线转人工服务咨询。</p>
                    </div>



                    <?php }else{?>
                     <table class="page-cons30">
                            <tr>
                                <td class="tr">银行卡号：</td>
                                <td><!-- <input type="text" id="bankcard" name="bankcard" value="<?php if(isset($result_bind['bank_account_no'])){echo $result_bind['bank_account_no'];}?>" disabled/> -->
                                    <div class="xj-wan19R clearFloat">
                                        <img class="bank-icon" src="<?php echo $directoryAsset;?>/images/bank/<?php echo $logo_bind; ?>.png" alt="">
                                        <span class="bank-word"><?php echo $result_bind["bank_name"];?>  <?php echo substr($result_bind["bank_account_no"], 0, 4).'****************'.substr($result_bind["bank_account_no"], strlen($result_bind["bank_account_no"])-3, 3);?></span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="tr">姓名：</td>
                                <td><input type="text" id="username" name="username" value="<?php if($result){echo $result['real_name'];}?>" disabled /></td>
                            </tr>
                            </table>
                  <?php }?>

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
            location.href="<?php echo yii\helpers\Url::to(['money/" + id + "'])?>";
        });
        $(".page-con102 tr:even").addClass("trOdd");  
    });
</script>
    
<script>

	/* var phone = $("#phone").val();
	var bankcard = $("#bankcard").val();
	if(phone == '' || bankcard == '')
	{
		$("#codea").attr("class","hui");
	}
	$("#phone").blur(function(){
		var phone = $("#phone").val();
		var bankcard = $("#bankcard").val();
		if(phone == '' || bankcard == '')
		{
			$("#codea").attr("class","hui");
		}
		else
		{
			$("#codea").removeAttr("class");
		}
	});
	
	$("#bankcard").blur(function(){
		var phone = $("#phone").val();
		var bankcard = $("#bankcard").val();
		if(phone == '' || bankcard == '')
		{
			$("#codea").attr("class","hui");
		}
		else
		{
			$("#codea").removeAttr("class");
		}
	}); */
	var wait=0;
	function time() {

			if (wait == 0) {
				$('#codea').attr('class','');
				$('#codea').html('发送验证码');

			} else {
				setTimeout(function() {
					$('#codea').attr('css','hui');
					$('#codea').html(wait); 
				});
				wait--;
				setTimeout(function() {
					time();
					
				},1000);
			}
	}
$("#codea").click(function(){
	if(wait != 0)
	{
		return false;
	}
	var phone = $("#phone").val();
	var bankcard = $("#bankcard").val();
	var idcard = $("#idcard").val();
	var username = $("#username").val();
	var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
	if(reg.test(phone))
	{
	  $('#codea').attr('class','hui');
	  $("#loading").css("display","block");
	  $.post("<?php echo yii\helpers\Url::to(['money/bindcard']);?>",{'phone':phone,'idcard':idcard,'bankcard':bankcard,'username':username,'_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'},function (data){

		var jsonobj = eval('('+data+')');
		 if(jsonobj.errorNum == 0)
		{
				wait = 60;
				time();
			$("#ticket").val(jsonobj.data.ticket);
			$("#request_no").val(jsonobj.data.request_no);
			$("#loading").css("display","none");
			alert("验证码发送成功，请查收短信");
	    }
		else if(jsonobj.errorNum == 1)
		{
			$('#codea').attr('class','');
			$("#loading").css("display","none");
	  	    alert(jsonobj['errorMsg']);
		}
		 else
		 {
			 $('#codea').attr('class','');
			 $("#loading").css("display","none");
             alert(data);
	     }
	  });
	}
	else
	{
		$("#loading").css("display","none");
		alert('请输入正确的手机号');
	}
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
    $(document).ready(function(){
        $("#register").click(function(){
        	var reg=/^1[0-9]{10}$/;
        	var phone = $("#phone").val();
    		var bankcard = $("#bankcard").val();
    		var idcard = $("#idcard").val();
    		var username = $("#username").val();
    		var code = $("#code").val();
    		var ticket = $("#ticket").val();
    		var request_no = $("#request_no").val();
    		if(reg.test(phone))
    		{
        		if(ticket != '' && request_no != '')
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
        			$("#loading").css("display","block");
        		  	$.post("<?php echo yii\helpers\Url::to(['money/bindcard']);?>",{'request_no':request_no,'ticket':ticket,'code':code, 'phone':phone,'idcard':idcard,'bankcard':bankcard,'username':username,'_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'},function (data){
        			  
        			  $("#ticket").val("");
        			  $("#request_no").val("");
        			  $("#loading").css("display","none");
        		  	  alert(data);
        		  	  if(data == '绑卡成功')
        		  	  {
        		  	  	location.href="<?php echo yii\helpers\Url::to(['money/recharge']);?>";
        		  	  }
        		  });
            	}
        		else
        		{
        			wait = 0;
        			alert('请获取手机验证码');
            	}
    			
    		}
    		else
    		{
    			wait = 0;
    			$("#loading").css("display","none");
    			alert('请输入正确的手机号');
    		}
        });
    });
</script>