<?php 
 use yii\base\View;
 use yii\bootstrap\ActiveForm;
 $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
$this->title = '个人设置';
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
            	<li class="Personal2"><a href="<?= yii\helpers\Url::to(['money/recharge']); ?>">充值提现</a></li>
                <li class="Personal3"><a href="<?= yii\helpers\Url::to(['sign/index']); ?>">我的签到</a></li>
            	<li class="Personal4 hover"><a href="<?= yii\helpers\Url::to(['setting/setting']); ?>">个人设置</a></li>
                <li class="Personal5"><a href="<?= yii\helpers\Url::to(['invitation/invitation']); ?>">邀请注册</a></li>
                <!--  <li class="Personal6"><a href="<?= yii\helpers\Url::to(['law/law']); ?>">法律服务</a></li>  -->
            </ul>
        </div>
        <div class="right" id="right" style="background:#f6f6f6; border:none;">
        	<?= View::render("@www/views/layouts/ucenter.php",['infos_rar'=>$infos_rar]); ?>
            <div class="right" id="right">
                <div class="detail_title"><p>个人设置</p></div>
                <?php if(!$is_Authentic) {?>
                <div class="spage-con11 clearFloat">
                    <p class="spage-con112 left">实名认证：</p>
                    <div class="left clearFloat spage-con121 page-cons31">
                    <div id="loading" style="display: none;margin:auto 0;z-index:9999;position:relative; top:-10px; left:162px;"><img width="23px" height="23px" alt="" src="<?php echo $directoryAsset;?>/images/loading.gif" align="center" /></div>
                    <?php ActiveForm::begin();?>
                        <input type="hidden" value="<?php echo yii::$app->request->getCsrfToken();?>">
                            <div class="clearFloat">
                                <p class="left cons31-left">姓名:</p>
                                <p class="left"><input class="small-wname" id="realname" type="text" name="realname" /></p>
                            </div>
                            
                            <div class="xjerr_msg" id="cardspan" style="display: none;">
	                        <div class="warning">
	                            <i id="cardtu" class="warn-icon"></i>
	                            <p id="cardtext">请输入正确手机号码</p>
	                        </div>
	                    	</div>
	                    	
                            <div class="clearFloat">
                                <p class="left cons31-left">身份证号:</p>
                                <p class="left"><input type="text" id="idcard" name="idcard" maxlength="18" /></p>
                            </div>
                            <div class="clearFloat">
                                <p class="left cons31-left"></p>
                                <p><input type="button" id="verify" value="保存信息" /></p>
                            </div>
                        <?php ActiveForm::end();?>
                    </div>
                </div>  
                <?php } else {?>
                <div class="spage-con11 clearFloat">
                    <p class="spage-con112 left">实名认证：</p>
                    <div class="left clearFloat spage-con121 page-cons31">
                        <div class="clearFloat">
                            <p class="left cons31-left">姓名:</p>
                            <p class="left"><?php echo $is_Authentic['real_name'];?></p>
                        </div>
                        <div class="clearFloat">
                            <p class="left cons31-left">身份证号:</p>
                            <p class="left"><?php echo substr($is_Authentic['idcard'], 0, 4)?><span class="blue-cor"> **************</span></p>
                        </div>
                    </div>
                </div>
                <?php }?>
                <?php $form=ActiveForm::begin([
                		'options' =>['enctype' => 'multipart/form-data'],
                		'id' => 'face-form'
                ])?>
                <div class="spage-con11 clearFloat">
                    <p class="spage-con112 left">更改头像</p>
                    <div class="left clearFloat">
                        <p class="spage-con113 left"><img width="119px" height="119px" id="img-face" src="<?php echo yii::$app->homeUrl;?>upload/<?php echo $model->person_face; ?>" /></p>
                        <div class="right">
                        	
                            <p><?= $form->field($model, 'person_face')->fileInput(['accept'=>'image/gif, image/jpeg, image/png','id'=>'ucentermember-person_face','style'=>'padding-left:0px;']); ?></p>
                            <p class='spage-con114'><img src="<?= $directoryAsset?>/images/danger.png" />请上传1M以内JPG或PNG格式的图片</p>
                            <Br/>
                            <p class="gaibian-btn sblue-btn"><input type="button" id="submit_face" style="background:#3598DB;border:none;color:#FFF;cursor:pointer;line-height:25px;" value="提交"></p>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end();?>
                
                <div class="spage-con12 clearFloat">
                    <p class="spage-con112 left">更改密码</p>
                    <div class="left clearFloat spage-con121">
                        <p class="left">登录密码</p>
                        <p class="left blue-cor">******************</p> 
                        <p class="left gaibian-btn sred-btn"><a href="<?php echo yii\helpers\Url::to(['setting/repass']);?>">更改</a></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
 
<script src="<?php echo Yii::$app->homeUrl;?>myAssetsLib/js/jquery-1.7.1.js"></script>
<script>
    $(document).ready(function(){
        $("#check-btn").click(function(event){
            $("body").css("position","fixed");
            $("body").css("width","100%");
            $(".Mask").show();
            $(".ui-popup").show();
        });
        $(".Mask").click(function(){
            $("body").css("position","relative");
            $(".Mask").hide();
            $(".ui-popup").hide();
        });
    });
</script>
<script>
 $(document).ready(function(){
		$("#submit").click(function(){
			var prepassword = $("#prepassword").val();
			var newpassword = $("#newpassword").val();
			var repeatpassword = $("#repeatpassword").val();
			if(prepassword.length<6 || newpassword.length<6 || repeatpassword.length<6)
			{alert("密码不能少于6位");}
			else if(newpassword != repeatpassword)
			{alert('两次密码输入不一致');}
			else
			{
				$.post(
				  '<?php echo yii\helpers\Url::to(['setting/setting']); ?>',
				  {prepassword:prepassword,newpassword:newpassword,repeatpassword:repeatpassword,_csrf:'<?php echo yii::$app->request->getCsrfToken();?>'},
				  function(data)
				  {
					alert(data);
					if(data == '密码修改成功')
					{
						location.href="<?php echo yii\helpers\Url::to(['setting/setting']);?>";
					}
				  }
					);
			}
			});
	 });
</script>

<script>
	var flag=[0,0];
	$('#username').blur(function(){
		var phone = $('#username').val();
		var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
		if(reg.test(phone)){
			$('#phonespan').css('display','none');
		}

        if(!reg.test(phone)){
            $('#phonespan').css('display','block');
        }
	});

</script>

<script>
 $(document).ready(function(){
		$("#verify").click(function(){
			var reg=/^[1-9]{1}[0-9]{14}$|^[1-9]{1}[0-9]{16}([0-9]|[xX])$/;
			var realname = $("#realname").val();
			var idcard = $("#idcard").val();
			if(realname == '')
			{alert("姓名不能为空");}
			else if(idcard == '')
			{alert("身份证号不能为空");}
			else if(!reg.test(idcard))
			{
				alert('身份证格式不正确');
		    }
			else
			{
				$("#loading").css("display","block");
				$.post(
				  '<?php echo yii\helpers\Url::to(['setting/setting']); ?>',
				  {realname:realname,idcard:idcard,_csrf:'<?php echo yii::$app->request->getCsrfToken();?>'},
				  function(data)
				  {
					$("#loading").css("display","none");
					alert(data);
					if(data == '身份认证成功')
					{
						location.href="<?php echo yii\helpers\Url::to(['money/bindcard']);?>";
					}
					
				  }
					);
			}
			});
	 });
</script>
<script>
$(document).ready(function(){
	$("#ucentermember-person_face").change(function(){
		var objUrl = getObjectURL(this.files[0]);
		if (objUrl) {
			$("#img-face").attr("src", objUrl);
		     }
		});

	$("#submit_face").click(function(){
		var face = $("#ucentermember-person_face").val();
		if(face == undefined || face == '')
		{
			alert('请上传图片');
		}
		else
		{
			$("#face-form").submit();
		}
		});
});


function getObjectURL(file) {
  var url = null;
  if (window.createObjectURL != undefined) { // basic
      url = window.createObjectURL(file);
  } else if (window.URL != undefined) { // mozilla(firefox)
      url = window.URL.createObjectURL(file);
  } else if (window.webkitURL != undefined) { // webkit or chrome
      url = window.webkitURL.createObjectURL(file);
  }
  return url;
}
</script>