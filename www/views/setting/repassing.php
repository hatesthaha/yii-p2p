
<div class="main">
    	<div class="zhuce" style="margin-top:30px;margin-bottom:30px; font-size:14px;">
			<table class="xj-jtab">
				<tr>
					<td>
						<p class="xj-jtabline xj-jtablineh"></p>
						<p class="xj-jnum xj-jtablineh">1</p>
					</td>
					<td>
						<p class="xj-jtabline"></p>
						<p class="xj-jnum">2</p>
					</td>
				</tr>
				<tr>
					<td>填写账户信息</td>
					<td>重置密码</td>
				</tr>
			</table>
            <div class="sjyz" style="margin:50px auto 50px;width:490px;">
	            <div class="xjerr_msg" id="phonespan" style="display: none;">
		          <div class="warning" style="margin:0 0 0 130px;">
		            <i id="phonestu" class="warn-icon"></i>
		              <p id="phonetext">密码不能为空</p>
		          </div>
		        </div>
                <p class="xj-class1"><span class="tite1" style="line-height:40px; text-align:center;">新密码：</span><span class="text"><input type="password" id="newpassword" name="password" /></span></p>
                <div class="xjerr_msg" id="validatespan" style="display: none;">
		          <div class="warning" style="margin:0 0 0 130px;">
		            <i id="validatestu" class="warn-icon"></i>
		              <p id="validatetext">重复密码不能为空</p>
		          </div>
		         </div>
                <p class="xj-class1"><span class="tite1" style="line-height:40px; text-align:center;">重置新密码：</span><span class="text"><input type="password" id="repeatpassword" name="repeatpassword" /></span></p>
               
                <div class="xjerr_msg" id="nextspan" style="display: none;">
		          <div class="warning" style="margin:0 0 0 130px;">
		            <i id="nextstu" class="warn-icon"></i>
		              <p id="nexttext"></p>
		          </div>
		        </div>
                <p class="dl"><input id="submit" type="button" style="margin-left:132px;" value="提交" /></p>
            </div>
            <div class="clear"></div>
        </div>
    </div>
<script src="<?php echo Yii::$app->homeUrl;?>myAssetsLib/js/jquery-1.7.1.js"></script>
<script>
 var flag=[0,0];
 $(document).ready(function(){
	 $("#newpassword").blur(function(){
		 var newpass = $("#newpassword").val();
		 var reg_pass = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/;
			if(newpass == '')
			{
				$("#phonespan").css("display","block");
				flag[0]=0;
			}
			
			else if(!reg_pass.test(newpass))
			{
				$("#phonetext").text("6-16位数字+字母组合");
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
	 		 var reg_pass = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/;
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
			else if(!reg_pass.test(repeatpass))
			{
				$("#validatetext").text("6-16位数字+字母组合");
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
					$.post("<?php echo yii\helpers\Url::to(['setting/repassing']);?>",{'password':newpass,'repeatpassword':repeatpass, '_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'},function (data){
						  if(data == '密码修改成功0001')
						  {
						  	 window.location.href="<?php echo yii\helpers\Url::to(['setting/repassed'])?>";
						  }
						  else if(data == '密码修改失败，请联系客服！')
						  {
							  $('#nexttext').html(data);
							  $('#nextspan').css('display','block');
						  }
						  else if(data == '受阻0003')
						  {
							  window.location.href="<?php echo yii\helpers\Url::to(['account/overview'])?>";
						  }
						  else
						  {
							  $('#nexttext').html(data);
							  $("#nextspan").css("display","block");
						  }
					  	});
					}
			});
	 });
</script>