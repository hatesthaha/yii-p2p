<?php 
	use yii\bootstrap\ActiveForm;
?>
<div class="main">
    	<div class="zhuce" style="margin-top:30px;margin-bottom:30px;padding-bottom: 70px;">
        	<div class="zc_title">
        		<h3>欢迎注册理财王,您身边的理财专家</h3>
            </div>
            <?php $form = ActiveForm::begin(); ?>
            <div class="sjyz" style="margin:90px auto 0;">
	            <div class="xjerr_msg" id="phonespan" style="display: none;">
		          <div class="warning">
		            <i id="phonestu" class="warn-icon"></i>
		              <p id="phonetext">手机号码格式不正确</p>
		          </div>
		        </div>
                <p class="xj-class1"><span class="dian">*</span> <span class="tite">手机号</span><span class="text"><input type="text" value="" name="username" id="username" style="background:#fff;"/></span></p>
                <div class="xjerr_msg" id="validatespan" style="display: none;">
		          <div class="warning">
		            <i id="validatestu" class="warn-icon"></i>
		              <p id="validatetext">验证码未填写</p>
		          </div>
		         </div>
                <p class="xj-class1"><span class="dian">*</span> <span class="tite">验证码</span><span class="text wkd"><input type="text" value="" id="validate_code" style="background:#fff;"/></span><span class='yellow-btn' id="sendcode" onclick="_sendSmsCode();" style="margin-left:45px; height:40px;">发送验证码</span></p>
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
	var flag=[0,0];
	function _sendSmsCode()
	{
		var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
		var CellPhone = $("#username").val();
		if(reg.test(CellPhone))
		{
			  var flag=[0,0];
			  $.post("<?php echo yii\helpers\Url::to(['sms/sendcode_']);?>",{'CellPhone':CellPhone, '_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'},function (data){

				  if(data == '验证码已发送，请注意查收。')
				  {
					  $('#sendcode').attr('class','hui-btn');
					  wait=60;
					  time();
				  }/* 
				  else
				  {
					  $('#nextspan').css('display','block');
						$('#nexttext').html(data);
				  } */
			  });
		}
		if(!reg.test(CellPhone)){
			flag[0] = 0;
            $('#phonespan').css('display','block');
        }
        
	}

	$("#username").blur(function(){
		var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
		var CellPhone = $("#username").val();
		if(reg.test(CellPhone)){
			$.post(
					'<?php echo yii\helpers\Url::to(['sms/smsphone']);?>',
					{'CellPhone':CellPhone,'_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'},
					function(data)
						{
							if(data)
							{
								$('#phonespan').css('display','block');
								$('#phonetext').html('手机未注册');
								flag[0] = 0;
							}
							else
							{
								$('#phonespan').css('display','none');
								flag[0] = 1;
							}
						}
				);
		}

        if(!reg.test(CellPhone)){
            $('#phonespan').css('display','block');
            flag[0] = 0;
        }
	});
	
	$('#validate_code').blur(function(){
		var code = $('#validate_code').val();

		if(code.length == 6){
			$('#validatespan').css('display','none');
			flag[1] = 1;
		}else{
			$('#validatespan').css('display','block');
			$('#validatetext').html('请输入6位验证码');
			flag[1] = 0;
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
				});
				wait--;
				setTimeout(function() {
					time();
					
				},1000);
			}
	}	
	
$(document).ready(function(){
	$("#submit").click(function(){
	var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
	var CellPhone = $("#username").val();
	var code = $('#validate_code').val();
	var validate_code = document.getElementById("validate_code").value;
	
		if(reg.test(CellPhone)){
			$.post(
					'<?php echo yii\helpers\Url::to(['sms/smsphone']);?>',
					{'CellPhone':CellPhone,'_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'},
					function(data)
						{
							if(data)
							{
								$('#phonespan').css('display','block');
								$('#phonetext').html('手机未注册');
								flag[0] = 0;
							}
							else
							{
								$('#phonespan').css('display','none');
								flag[0] = 1;
							}
						}
				);
		}

        if(!reg.test(CellPhone)){
            $('#phonespan').css('display','block');
            flag[0]=0;
        }
	
		if(code.length == 6){
			$('#validatespan').css('display','none');
			flag[1]=1;
		}else{
			$('#validatespan').css('display','block');
			$('#validatetext').html('请输入6位验证码');
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
		  $.post("<?php echo yii\helpers\Url::to(['site/forgot']);?>",{'CellPhone':CellPhone,'validate_code':validate_code,'_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'},function (data){

			  if(data == '已实名制认证0001')
			  {
				location.href="<?php echo yii\helpers\Url::to(['site/step1']);?>";
			  }
			  else if(data == '未实名制认证0002')
			  {
				location.href="<?php echo yii\helpers\Url::to(['site/step2']);?>";
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