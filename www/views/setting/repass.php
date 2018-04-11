<?php 
 use yii\base\View;
 use yii\bootstrap\ActiveForm;
 $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
?>
<div class="main">
	<div class="zhuce" style="margin-top:30px;margin-bottom:30px;">
		<table class="xj-jtab">
			<tr>
				<td>
					<p class="xj-jtabline"></p>
					<p class="xj-jnum">1</p>
				</td>
				<td>
					<p class="xj-jtabline xj-jtablineh"></p>
					<p class="xj-jnum xj-jtablineh">2</p>
				</td>
			</tr>
			<tr>
				<td>填写账户信息</td>
				<td>重置密码</td>
			</tr>
		</table>
        <?php $form = ActiveForm::begin(); ?>
        <div class="sjyz" style="margin:50px auto 50px;">
            <div class="xjerr_msg" id="phonespan" style="display: none;">
	          <div class="warning">
	            <i id="phonestu" class="warn-icon"></i>
	              <p id="phonetext">手机号码格式不正确</p>
	          </div>
	        </div>
            <p class="xj-class1"><span class="dian">*</span> <span class="tite">手机号</span><span class="text"><input type="text" value="" name="username" id="username" /></span></p>
            <div class="xjerr_msg" id="validatespan" style="display: none;">
	          <div class="warning">
	            <i id="validatestu" class="warn-icon"></i>
	              <p id="validatetext">验证码未填写</p>
	          </div>
	         </div>
            <p class="xj-class1"><span class="dian">*</span> <span class="tite">验证码</span><span class="text wkd"><input type="text" value="" id="validate_code" /></span><span class='yellow-btn' id="sendcode" onclick="_sendSmsCode();" style=" background:#5cb85c;margin-left:20px; width:125px;height:40px;">发送验证码</span></p>
            <span disabled id="info1" style="float: right; line-height: 15px; margin-left: 5px; color: rgb(153, 153, 153);  text-align: left; display: none;"></span>
            <div class="xjerr_msg" id="nextspan" style="display: none;">
	          <div class="warning">
	            <i id="nextstu" class="warn-icon"></i>
	              <p id="nexttext">手机号码格式不正确</p>
	          </div>
	        </div>
            <p class="dl"><input id="submit" type="button" value="下一步" /></p>
        </div>
        <?php ActiveForm::end(); ?>
        <div class="clear"></div>
    </div>
</div>
<script src="<?php echo Yii::$app->homeUrl;?>myAssetsLib/js/jquery-1.7.1.js"></script>
    <script>
	function _sendSmsCode()
	{
		var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
		var CellPhone = $("#username").val();
		if(reg.test(CellPhone))
		{
			  $.post("<?php echo yii\helpers\Url::to(['sms/sendcode_repass']);?>",{'CellPhone':CellPhone, '_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'},function (data){

				  if(data == '验证码已发送，请注意查收。')
				  {
					  $('#sendcode').attr('class','hui-btn');
					  $('#sendcode').css('cursor','pointer');
					  wait=60;
					  time();
				  }
				  else
				  {
					  $('#sendcode').css('cursor','text');
					  $('#nextspan').css('display','block');
					  $('#nexttext').html(data);
				  }
			  });
		}
		if(!reg.test(CellPhone)){
			$('#sendcode').css('cursor','text');
            $('#phonespan').css('display','block');
        }
        
	}

	$("#username").blur(function(){
		var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
		var CellPhone = $("#username").val();
		if(reg.test(CellPhone)){
			$('#sendcode').css('cursor','pointer');
			$('#phonespan').css('display','none');
		}

        if(!reg.test(CellPhone)){
        	$('#sendcode').css('cursor','text');
            $('#phonespan').css('display','block');
        }
	});
	
	$('#validate_code').blur(function(){
		var code = $('#validate_code').val();

		if(code){
			$('#sendcode').css('cursor','pointer');
			$('#validatespan').css('display','none');
		}else{
			$('#sendcode').css('cursor','text');
			$('#validatespan').css('display','block');
			$('#validatetext').html('验证码未填写');
		}	
	});

	var wait=60;
	function time() {

			if (wait == 0) {
				$('#sendcode').attr('class','yellow-btn');
				$('#sendcode').html('发送验证码');

			} else {
				setTimeout(function() {
					$('#sendcode').attr('css','hui-btn');
					$('#sendcode').html(wait); 
				},1000);
				wait--;
				setTimeout(function() {
					time();
					
				},1000);
			}
	}	
</script>
<script>
$(document).ready(function(){
	$("#submit").click(function(){
	var flag=[0,0];
	var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
	var CellPhone = $("#username").val();
	var code = $('#validate_code').val();
	var validate_code = document.getElementById("validate_code").value;
	
	
		if(reg.test(CellPhone)){
			$('#phonespan').css('display','none');
			flag[0]=1;
		}

        if(!reg.test(CellPhone)){
            $('#phonespan').css('display','block');
            flag[0]=0;
        }
	
		if(code){
			$('#validatespan').css('display','none');
			flag[1]=1;
		}else{
			$('#validatespan').css('display','block');
			$('#validatetext').html('验证码未填写');
			flag[1]=0;
		}
	if(reg.test(CellPhone))
	{
		var res = true;
		for(var i=0;i<flag.length;i++){
		    if(flag[i]==0){
           		res=false;  break;
         } 	
	   }
	  if(res)
	  {
		  $.post("<?php echo yii\helpers\Url::to(['setting/repass']);?>",{'CellPhone':CellPhone,'validate_code':validate_code,'_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'},function (data){

			  if(data == '验证通过0001')
			  {
				location.href="<?php echo yii\helpers\Url::to(['setting/repassing']);?>";
			  }
			  else if(data == '验证失败0002')
			  {
				  $('#nexttext').html('未通过验证，请重新获取验证码。');
			  }
			  else
			  {
				  $('#nextspan').css('display','block');
					$('#nexttext').html(data);
			  }
		  });
	  }
	}
	
	});
		});
</script>