<?php 
	use yii\bootstrap\ActiveForm;
?>
<div class="main">
    	<div class="zhuce" style="margin-top:30px;margin-bottom:30px; font-size:14px;">
        	<div class="zc_title">
        		<h3>欢迎注册理财王,您身边的理财专家</h3>
            </div>
            <?php $form = ActiveForm::begin(); ?>
            <div class="sjyz" style="margin:90px auto 0;width:490px;">
	            <div class="xjerr_msg" id="phonespan" style="display: none;">
		          <div class="warning" style="margin:0 0 0 130px;">
		            <i id="phonestu" class="warn-icon"></i>
		              <p id="phonetext">密码不能为空</p>
		          </div>
		        </div>
                <p class="xj-class1"><span class="dian">*</span> <span class="tite1" style="line-height:40px;">输入新密码：</span><span class="text"><input type="password" id="newpassword" name="password" /></span></p>
                <div class="xjerr_msg" id="validatespan" style="display: none;">
		          <div class="warning" style="margin:0 0 0 130px;">
		            <i id="validatestu" class="warn-icon"></i>
		              <p id="validatetext">重复密码不能为空</p>
		          </div>
		         </div>
                <p class="xj-class1"><span class="dian">*</span> <span class="tite1" style="line-height:40px;">再次输入新密码：</span><span class="text"><input type="password" id="repeatpassword" name="repeatpassword" /></span></p>
               
                <div class="xjerr_msg" id="nextspan" style="display: none;">
		          <div class="warning" style="margin:0 0 0 130px;">
		            <i id="nextstu" class="warn-icon"></i>
		              <p id="nexttext">处理异常</p>
		          </div>
		        </div>
                <p class="dl"><input id="submit" type="button" style="margin-left:132px;" value="下一步" /></p>
            </div>
            <?php ActiveForm::end(); ?>
            <div class="clear"></div>
        </div>
    </div>
    
<script src="<?php echo Yii::$app->homeUrl;?>myAssetsLib/js/jquery-1.7.1.js"></script>
<script>
 var flag=[0,0];
 $(document).ready(function(){
	 $("#newpassword").blur(function(){
		 var newpass = $("#newpassword").val();
			if(newpass == '')
			{
				$("#phonespan").css("display","block");
				flag[0]=0;
			}
			
			else if(newpass.length <6)
			{
				$("#phonetext").text("密码长度不能少于6位");
				$("#phonespan").css("display","block");
				flag[0]=0;
			}
			else
			{
				$("#phonetext").text("");
				$("#phonespan").css("display","none");
				flag[0]=1;
			}	
			
		 	});
	 	$("#repeatpassword").blur(function(){
	 		 var newpass = $("#newpassword").val();
	 		 var repeatpass = $("#repeatpassword").val();
			if(repeatpass == '')
			{
				$("#validatespan").css("display","block");
				flag[1]=0;
			}
			else if(newpass != repeatpass)
			{
				$("#validatetext").text("两次密码输入不一致");
				$("#validatespan").css("display","block");
				flag[1]=0;
			}
			else if( repeatpass.length<6)
			{
				$("#validatetext").text("密码长度不能少于6位");
				$("#validatespan").css("display","block");
				flag[1]=0;
			}
			else
			{
				$("#validatetext").text("");
				$("#validatespan").css("display","none");
				flag[1]=1;
			}
			
		 	});
		$("#submit").click(function(){
			 var newpass = $("#newpassword").val();
			 var repeatpass = $("#repeatpassword").val();
			 var res = true;
				for(var i=0;i<flag.length;i++){
				    if(flag[i]==0){ 
	               		res=false;  break;
	               	} 	
				}
				if(res)
					{
					$.post("<?php echo yii\helpers\Url::to(['site/step2']);?>",{'password':newpass,'repeatpassword':repeatpass, '_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'},function (data){
						  if(data == '密码修改成功')
						  {
						  	 alert(data);
						  	 window.location.href="<?php echo yii\helpers\Url::to(['site/login'])?>";
						  }
						  else if(data == '密码修改失败，请联系客服！')
						  {
							  alert(data);
							  window.location.href="<?php echo yii\helpers\Url::to(['site/index'])?>";
						  }
						  else
						  {
							  $("#nextspan").css("display","block");
						  }
					  	});
					}
			});
	 });
</script>