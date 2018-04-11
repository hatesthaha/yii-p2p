<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\base\experience\Rule;
use common\models\UcenterMember;
use yii\captcha\Captcha;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = yii::$app->request->baseUrl.'/../../backend/web/upload/';
$user = UcenterMember::find()->where(['id'=>$_GET['id']])->one();
?>

<span class="spanti-c2"></span>
<section>
    <p><img class="d-block" width="100%" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/sign2-bg.jpg" alt="理财王"></p>
    <div class="Invitat-top Invtiat2-top">
        <p class="Invitat-tp1">您的好友<span><?= $user->username ?></span> 邀请您一起使用天天理财</p>
        <p class="Invitat-tp2">赚钱的好事要分享，独食难肥哟~</p>
        <form  method="post" id="signupform" action="<?php echo yii\helpers\Url::to(['gold/gsignup']);?>">
            <span id="invitacode" class="spanti-flash">
               <?php
               if(\Yii::$app->getSession()->getFlash("errors")) {
                   echo \Yii::$app->getSession()->getFlash("errors")['info'];
               }
               ?>
            </span>
            <span class="spanti-c" id="spanti"></span>
            <input type ="hidden" name="_csrf" value="<?php echo yii::$app->request->getCsrfToken();?>" />
            <input type="hidden" value="<?php if(isset($code)) {echo $code;}?>" name="url_code" />
            <div class="tel_box">
                <span class="tel_num">+86</span>
                <input data-role="none" name="phone" id="phone" class="tel_inp" type="tel" autocomplete="off" placeholder="请输入手机号" maxlength="11" value="">
            </div>
            <div>
                <input data-role="none" name="keyword" id="password" class="keyword" type="password" placeholder="请设置登录密码(6-18位数字与字母组合)">
            </div>
            <div class="code-area">
                <a class="border0 bgd-787878 hxyzm c-ffffff send" href="javascript:;" id="codetext" style="width:24% ">获取验证码</a>
                <div class="input-wrap">
                    <input type="text" class="j-input" placeholder="请输入验证码" autocomplete="off" value="" data-role="none" id="code" name="code">
                </div>
            </div>

            <div class="squaredTwodiv clearFloat">
                <div class="squaredTwo">
                    <input data-role="none" type="checkbox" value="checked" id="squaredTwo" name="check" checked="">
                    <label data-role="none" for="squaredTwo" class="icon-ok"></label>
                </div>
                <div class="squaredTwo_r">
                    我已经阅读<a href="#" data-ajax="false" class="ui-link">《天天理财注册服务协议》</a>
                </div>
            </div>
            <div class="sub_registered">
                <input type="submit" id="submit1" value="完成注册"  />
            </div>
        </form>
    </div>
</section>
<section>
    <div class="Invitat-Mdiv1">
        <h2>理财王是什么？</h2>
        <p>理财王是由华夏融创全新打造，</p>
        <p>致力于手机平台最全面省力的理财应用，</p>
        <p>在这里您可以享受到便捷的一站式投资理财乐趣。</p>
    </div>
    <div class="Invitat-Mdiv2">
        <p class="Invitat-LineT clearFLoat"><em></em><span>五大战略合作机构助力安全</span><em></em></p>
        <p><img width="100%" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/Invitat3.jpg" alt=""></p>
    </div>
    <div class="Invitat-Mdiv3">
        <p class="Invitat-LineT"><em></em><span>高享收益</span><em></em></p>
        <p class="Invitat-LineP">最高12.68+%预期年化收益，轻松赚钱!</p>
        <p><img width="100%" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/Invitat1.jpg" alt=""></p>
    </div>
    <div class="Invitat-Mdiv3">
        <p class="Invitat-LineT"><em></em><span>无限便捷</span><em></em></p>
        <p class="Invitat-LineP">手机登陆网站或下载APP即可实现财富增值</p>
        <p><img width="100%" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/Invitat2.jpg" alt=""></p>
    </div>
    <div class="Invitat-Mdiv4">
        <p><a href="#"><img width="100%" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/Invitat4.jpg" alt=""></a></p>
    </div>
</section>

<?php $this->beginBlock('inline_scripts'); ?>
<script>
    $(document).ready(function(){
        setTimeout(function(){$('#flash').fadeOut(500);},2600);
        $("body").css("min-height",$(window).height());
        if($(window).width()<640){
            var B = $(window).width()/320*100*0.625+"%";
            $("html").css("font-size",B)
        }
        var UA=window.navigator.userAgent;  //使用设备
        var CLICK="click";
        if(/ipad|iphone|android/.test(UA)){   //判断使用设备
            CLICK="tap";
        }
        $(".squaredTwo")[CLICK](function(){
            $(this).css("border-color","#3c3c3c");
            $(this).css("color","#3c3c3c");
        });
        $(".squaredTwodiv").width($(".squaredTwo").width()+$(".squaredTwo_r").width()+10);
        $(".downLoad-app").css("border-radius",$(".downLoad-app").height()/2);
    })
    $(window).resize(function(){
        $("body").css("min-height",$(window).height());
        $(".downLoad-app").css("border-radius",$(".downLoad-app").height()/2);
    })
    var flag=[0,0];
    $('#phone').blur(function(){

        var phone = $('#phone').val();
        var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
        if(reg.test(phone)){
            $.post("<?php echo yii\helpers\Url::to(['phone/smsphone']);?>",{'CellPhone':phone,'_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'},function (data){
                if(!data){
                    $('#spanti').html('手机号码已经注册');
                    $('#spanti').show();
                    $('#code').attr('disabled','disabled');
                    $('#codetext').attr('class','border0 bgd-787878 hxyzm c-ffffff fr');
                    $('#invitation_code').attr('disabled','disabled');
                    $('#submit1').attr('class','border0 bgd-787878 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                    flag[0]=0;
                    setTimeout(function(){$('#spanti').fadeOut(500);},2600);
                }
                else
                {
                    $('#spanti').hide();
                    $('#invitacode').hide();
                    $('#code').removeAttr("disabled");;
                    $('#codetext').attr('class','border0 bgd-e44949 hxyzm c-ffffff fr send');
                    $('#invitation_code').removeAttr("disabled");;
                    // $('#submit').attr('class','border0 bgd-e44949 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
                    flag[0]=1;
                }
            });
        }
        if(!reg.test(phone)){
            $('#spanti').html('你输入的手机号码不正确');
            $('#spanti').show();
            $('#code').attr('disabled','disabled');
            $('#codetext').attr('class','border0 bgd-787878 hxyzm c-ffffff fr');
            $('#invitation_code').attr('disabled','disabled');
            $('#submit1').attr('class','border0 bgd-787878 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
            flag[0]=0;
            setTimeout(function(){$('#spanti').fadeOut(500);},2600);
        }

    });
    var wait=60;
    function time() {

        if (wait == 0) {
            $('#codetext').attr('class','border0 bgd-e44949 hxyzm c-ffffff fr send');
            $('#codetext').html('发送验证码');

        } else {
            setTimeout(function() {
                $('#codetext').attr('class','border0 bgd-787878 hxyzm c-ffffff fr');
                $('#codetext').html(wait);
            },1000);
            wait--;
            setTimeout(function() {
                time();

            },1000);
        }
    }
    $('.send').click(function(){
        var phone = $('#phone').val();

        var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;

        if(reg.test(phone)) {
            $.post("<?php echo yii\helpers\Url::to(['phone/sendcode']);?>",{'CellPhone':phone,'_csrf':'<?php echo yii::$app->request->getCsrfToken();?>'},function (data){
                if(data == '验证码已发送，请注意查收。')
                {
                    wait=60;
                    time();
                    $(".spanti-c2").html(data);
                    $(".spanti-c2").show();
                    setTimeout(function(){$(".spanti-c2").fadeOut(500);},2600);
                }
                else
                {
                    $(".spanti-c2").html(data);
                    $(".spanti-c2").show();
                    setTimeout(function(){$(".spanti-c2").fadeOut(500);},2600);
                }
            });
        }else{
            $('#spanti').html('你输入的手机号码不正确');
            $('#spanti').show();
            $('#code').attr('disabled','disabled');
            $('#codetext').attr('class','border0 bgd-787878 hxyzm c-ffffff fr');
            $('#invitation_code').attr('disabled','disabled');
            $('#submit1').attr('class','border0 bgd-787878 c-ffffff p-bottom-10 p-top-10 m-bottom-10');
            setTimeout(function(){$('#spanti').fadeOut(500);},2600);
        }
    });
    $('#code').blur(function(){
        var phone = $('#phone').val();
        var code = $('#code').val();
        if(code){
            flag[1]=1;
//                $.post("<?php //echo yii\helpers\Url::to(['phone/codecheck']);?>//",{'CellPhone':phone,'code':code,'_csrf':'<?php //echo yii::$app->request->getCsrfToken();?>//'},function (data){
//                    if(!data)
//                    {
//                        $('#spanti').html('验证码填写不正确');
//                        $('#spanti').show();
//                        flag[1]=0;
//                    }else{
//                        $('#spanti').hide();
//                        $('#invitacode').hide();
//                        flag[1]=1;
//                    }
//                });
        }else{
//                $('#spanti').html('你没有填写验证码');
//                $('#spanti').show();
//                flag[1]=0;
            flag[1]=0;
        }
    });

    $('#submit1').click(function(){
        var phone = $('#phone').val();
        var code = $('#code').val();
        var res = true;
        for(var i=0;i<flag.length;i++){
            if(flag[i]==0){
                res=false;  break;
            }
        }
        if(res){
            $('#signupform').submit();
        }else{
            console.log(123);
        }
    });
</script>
<?php $this->endBlock(); ?>