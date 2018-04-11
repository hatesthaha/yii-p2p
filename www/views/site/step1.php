<?php 
	use yii\bootstrap\ActiveForm;
?>
<div class="main">
    	<div class="zhuce" style="margin-top:30px;margin-bottom:30px; font-size:14px;">
        	<div class="zc_title">
        		<h3>欢迎注册理财王,您身边的理财专家</h3>
            </div>
            <?php $form = ActiveForm::begin(); ?>
            <div class="smzc" style="font-size:14px;">
            <div class="xjerr_msg" id="namespan" style="display: none;">
		          <div class="warning" style="margin:0 0 0 130px;">
		            <i id="namestu" class="warn-icon"></i>
		              <p id="nametext">姓名不能为空</p>
		          </div>
		     </div>
                <p class="xj-class1"><span class="dian">*</span> <span class="tite1" style="line-height:40px;">姓名：</span>
                <span class="text"><input class="pname" type="text" id="name" name="name" /></span></p>
                
                <div class="xjerr_msg" id="idcardspan" style="display: none;">
		          <div class="warning" style="margin:0 0 0 130px;">
		            <i id="idcardstu" class="warn-icon"></i>
		              <p id="idcardtext">密码不能为空</p>
		          </div>
		        </div>
		        
                <p class="xj-class1"><span class="dian">*</span>  <span class="tite1" style="line-height:40px;">身份证号：</span>
                <span class="text"><input type="text" id="idcard" name="idcard" /></span></p>
                
                <div class="xjerr_msg" id="newpasswordspan" style="display: none;">
		          <div class="warning" style="margin:0 0 0 130px;">
		            <i id="newpasswordstu" class="warn-icon"></i>
		              <p id="newpasswordtext">密码不能为空</p>
		          </div>
		        </div>
		        
                <p class="xj-class1"><span class="dian">*</span>  <span class="tite1" style="line-height:40px;">输入新密码：</span>
                <span class="text"><input type="password" id="newpassword" name="password" /></span></p>
                
                <div class="xjerr_msg" id="repeatpasswordspan" style="display: none;">
		          <div class="warning" style="margin:0 0 0 130px;">
		            <i id="repeatpasswordstu" class="warn-icon"></i>
		              <p id="repeatpasswordtext">密码不能为空</p>
		          </div>
		        </div>
		        
                <p class="xj-class1"><span class="dian">*</span>  <span class="tite1" style="line-height:40px;">重复新密码：</span>
                <span class="text"><input type="password" id="repeatpassword" name="repeatpassword" /></span></p>
                
                <div class="xjerr_msg" id="returnspan" style="display: none;">
		          <div class="warning" style="margin:0 0 0 130px;">
		            <i id="returnstu" class="warn-icon"></i>
		              <p id="returntext">处理异常</p>
		          </div>
		        </div>
		        
                <p class="dl"><input style="width:280px;margin-left:132px;" type="submit" id="submit" value="修改密码" /></p>
            </div>
            <?php ActiveForm::end(); ?>
            <div class="clear"></div>
        </div>
    </div>
     <script src="<?php echo Yii::$app->homeUrl;?>myAssetsLib/js/jquery-1.7.1.js"></script>
  <script>
 var flag=[0,0,0,0];
 $(document).ready(function(){
	 $("#name").blur(function(){
		 var name = $("#name").val();
		 if(name == '')
			{
				$("#namespan").css("display","block");
				flag[0]=0;
			}
		 else
		 	{
			 $("#nametext").text("");
				$("#namespan").css("display","none");
				flag[0]=1;
			}
			 
		 });

	 $("#idcard").blur(function(){
		 var reg=/^[1-9]{1}[0-9]{14}$|^[1-9]{1}[0-9]{16}([0-9]|[xX])$/;
		 var idcard = $("#idcard").val();
		 if(idcard == '')
			{
				$("#idcardspan").css("display","block");
				flag[1]=0;
			}
		 else if(!reg.test(idcard))
		 	{
			 	$("#idcardtext").text("身份证号格式不正确");
				$("#idcardspan").css("display","block");
				flag[1]=0;
			}
		 else
		 	{
			 $("idcardtext").text("");
				$("#idcardspan").css("display","none");
				flag[1]=1;
			}
			 
		 });
	 
	 $("#newpassword").blur(function(){
		 var newpass = $("#newpassword").val();
		 var reg_pass = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/;
			if(newpass == '')
			{
				$("#newpasswordspan").css("display","block");
				flag[2]=0;
			}
			
			else if(!reg_pass.test(newpass))
			{
				$("#newpasswordtext").text("6-16位数字+字母组合");
				$("#newpasswordspan").css("display","block");
				flag[2]=0;
			}
			else
			{
				$("#newpasswordtext").text("");
				$("#newpasswordspan").css("display","none");
				flag[2]=1;
			}	
			
		 	});
	 	$("#repeatpassword").blur(function(){
	 		 var newpass = $("#newpassword").val();
	 		 var repeatpass = $("#repeatpassword").val();
	 		 var reg_pass = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/;
			if(repeatpass == '')
			{
				$("#repeatpasswordspan").css("display","block");
				flag[3]=0;
			}
			else if(newpass != repeatpass)
			{
				$("#repeatpasswordtext").text("两次密码输入不一致");
				$("#repeatpasswordspan").css("display","block");
				flag[3]=0;
			}
			else if(!reg_pass.test(repeatpass))
			{
				$("#repeatpasswordtext").text("6-16位数字+字母组合");
				$("#repeatpasswordspan").css("display","block");
				flag[3]=0;
			}
			else
			{
				$("#repeatpasswordtext").text("");
				$("#repeatpasswordspan").css("display","none");
				flag[3]=1;
			}	
			
		 	});
		$("#submit").click(function(){
			var name = $("#name").val();
			var idcard = $("#idcard").val();
			 var password = $("#newpassword").val();
			 var res = true;
				for(var i=0;i<flag.length;i++){
				    if(flag[i]==0){ 
	               		res=false;  break;
	               	} 	
				}
				if(res)
					{
					$.post("<?php echo yii\helpers\Url::to(['site/step2']);?>",{'password':newpass,'name':name,'idcard':idcard, '_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'},function (data){
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
							 $("#returnspan").css("display","block");
						  }
					  	});
					}
			});
	 });
</script>