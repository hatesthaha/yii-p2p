<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '用户手机页面注册';
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
//获取邀请链接里面的邀请码
$code = Yii::$app->request->get('code', 0);
?>
<form class="xj-zhuceform clearFloat" method="post" id="signupform" action="<?php echo yii\helpers\Url::to(['events/signup']);?>">
    <input type ="hidden" name="_csrf" value="<?php echo yii::$app->request->getCsrfToken();?>" />
    <input type="hidden" value="<?php echo $code;?>" name="url_code" />
    <table class="xj-zhucetable">
        <tr>
            <td class="label-tdl">
                手机号码
            </td>
            <td class="label-tdr">
                <input class="xj-wanphone" type="text" value="" id='phone' maxlength="11" name="SignupForm[username]" placeholder="请输入您的手机号" />
            </td>
        </tr>
        <tr>
            <td class="label-tdl">
                登录密码
            </td>
            <td class="label-tdr">
                <input class="xj-wanpsw" type="password" value="" name="SignupForm[password]" id="password" placeholder="设置登录密码" />
            </td>

        </tr>
        <tr>
            <td class="label-tdl">
                确认密码
            </td>
            <td class="label-tdr">
                <input class="xj-wanpsw" type="password" value="" name="SignupForm[password_repeat]" id="repassword" placeholder="确认登录密码" />
            </td>

        </tr>
        <tr>
            <td class="label-tdl">
                验证码
            </td>
            <td class="label-tdr label-tdcode">
                <input type="text" value="" name="SignupForm[validate_code]" id="validate_code" placeholder="请输入验证码" />
                <span class='yellow-btn' id="sendcode">发送验证码</span>
            </td>

        </tr>
        <tr>
            <td class="label-tdl">
                &nbsp;
            </td>
            <td class="label-tdr label-tdcheck">
                <input type="button" id="submit" class="hui" value="提交" />
            </td>
        </tr>
    </table>
</form>
<script src="<?php echo Yii::$app->homeUrl;?>myAssetsLib/js/jquery-1.7.1.js"></script>
<script>
    $(document).ready(function(){
       $('#sendcode').click(function(){
           var phone = $("input[id='phone']").val();
           $.post("<?php echo yii\helpers\Url::to(['events/sendcode']);?>",
               {
                   'Phone' : phone,
                   '_csrf' : '<?php echo yii::$app->request->getCsrfToken();?>'
               },
               function (data){
                data = JSON.parse(data);
                if(parseInt(data['errorNum'])){
                    alert(data['errorMsg']);
                }else{
                    alert('短信发送成功');
                }
           });
       });
        $('#submit').click(function () {
            var phone = $("input[id='phone']").val();
            var password = $("input[id='password']").val();
            var repassword = $("input[id='repassword']").val();
            var validate_code = $("input[id='validate_code']").val();
            var url_code = $("input[name='url_code']").val();
            var actibity_source = '中秋节活动';

            $.post("<?php echo yii\helpers\Url::to(['events/signup']);?>",
                {
                    'phone' : phone,
                    '_csrf' : '<?php echo yii::$app->request->getCsrfToken();?>',
                    'password' : password,
                    'repassword' : repassword,
                    'validate_code' : validate_code,
                    'invite_code' : url_code,
                    'actibity_source' : actibity_source
                },
                function (data){
                    data = JSON.parse(data);
                    if(parseInt(data['errorNum'])){
                        alert(data['errorMsg']);
                    }else{
                        alert('注册成功');
                    }
                });
        });
    });
</script>
