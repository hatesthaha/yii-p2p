<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\base\experience\Rule;
use common\models\base\experience\Gold;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = yii::$app->request->baseUrl.'/../../backend/web/upload/';
?>


    <div class="red-bg">
        <section>
            <p><img class="d-block" width="100%" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/sign2-bg.jpg" alt="理财王"></p>
        </section>
        <section class="experience3-Mdiv1">
            <table>
                <tr>
                    <th>已获体验金（元）</th>
                    <th>体验金收益（元）</th>
                </tr>
                <tr>
                    <td class="ex-num"><span><?= number_format($gold['smoney'],2) ?></span></td>
                    <td class="ex-num"><span><?= number_format($goldincome['sincome'],2) ?></span> <a href="<?php echo Url::to(['withdraw/index']);?>">提现</a></td>
                </tr>
            </table>
        </section>
        <div class="experience3-Mdiv2">
            <p class="experience3-MdivT"><img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/Experience.png" alt=""><span>收益计算器</span></p>
            <p class="experience3-MdivP">* 做任务，领体验金，得收益！</p>
            <p class="experience3-MdivP">* 体验金一旦被获取，直接进入“在投体验金”金额中，每天计算利息，利息所得可以赎回，并提现。</p>
        </div>
        <ul class="experience4-Mdiv">
            <li class="experience4-li">
                <img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/Experience42.jpg" alt="">
                <table class="experience4-tab">
                    <tr>
                        <td>
                            <p class="experience4-tabT">手机号注册</p>
                            <p class="experience4-tabP">获取<?= $rule = Rule::find()->where(['title'=>'手机号注册'])->one()->money; ?>元体验金</p>
                        </td>
                        <td class="experience4-tabCome">
                            <img  src="<?= Yii::getAlias('@web') . '/' ?>rq-images/Experience43.png" alt="">
                        </td>
                    </tr>
                </table>
            </li>
            <li class="experience4-li">
                <img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/Experience42.jpg" alt="">
                <table class="experience4-tab">
                    <tr>
                        <td>
                            <p class="experience4-tabT">绑定银行卡</p>
                            <p class="experience4-tabP">获取<?= $rule = Rule::find()->where(['title'=>'绑定银行卡'])->one()->money; ?>元体验金</p>
                        </td>
                        <td class="experience4-tabCome">
                            <img  src="<?= Yii::getAlias('@web') . '/' ?>rq-images/Experience43.png" alt="">
                        </td>
                    </tr>
                </table>
            </li>
            <li class="experience4-li">
                <img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/Experience42.jpg" alt="">
                <table class="experience4-tab">
                    <tr>
                        <td>
                            <p class="experience4-tabT">首次投资</p>
                            <p class="experience4-tabP">获取<?= $rule = Rule::find()->where(['title'=>'首次投资'])->one()->money; ?>元体验金</p>
                        </td>
                        <td class="experience4-tabCome">
                            <?php if(Gold::find()->andWhere(['uid'=>yii::$app->user->id])->andWhere(['rid'=>3])->one()){  ?>
                                <img  src="<?= Yii::getAlias('@web') . '/' ?>rq-images/Experience43.png" alt="">
                            <?php }else{ ?>
                                <a href="<?php echo Url::to(['site/main']);?>">去完成</a>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
            </li>
            <li class="experience4-li">
                <img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/Experience42.jpg" alt="">
                <table class="experience4-tab">
                    <tr>
                        <td>
                            <p class="experience4-tabT">好友注册</p>
                            <p class="experience4-tabP">获取<?= $rule = Rule::find()->where(['title'=>'好友注册'])->one()->money; ?>元体验金</p>
                        </td>
                        <td class="experience4-tabCome">
                            <a id="share" href="javascript:;">去完成</a>
                        </td>
                    </tr>
                </table>
            </li>
            <li class="experience4-li">
                <img src="<?= Yii::getAlias('@web') . '/' ?>rq-images/Experience42.jpg" alt="">
                <table class="experience4-tab">
                    <tr>
                        <td>
                            <p class="experience4-tabT">好友投资</p>
                            <p class="experience4-tabP">获取<?= $rule = Rule::find()->where(['title'=>'好友投资'])->one()->money; ?>元体验金</p>
                        </td>
                        <td class="experience4-tabCome">

                        </td>
                    </tr>
                </table>
            </li>
            <p class="experienct4-BOT"><a href="#">活动详情</a></p>
        </ul>

    </div>
    <section id="wh_cover"></section>
    <p class="share"><img width="100%" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/share.png" alt="分享"></p>

<?php $this->beginBlock('inline_scripts'); ?>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        var uid = <?php echo yii::$app->user->id; ?>;
        wx.config({
            debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId:'<?php echo $signpack["appId"]; ?>' , // 必填，公众号的唯一标识
            timestamp: '<?php echo $signpack["timestamp"]; ?>', // 必填，生成签名的时间戳
            nonceStr: '<?php echo $signpack["nonceStr"]; ?>', // 必填，生成签名的随机串
            signature: '<?php echo $signpack["signature"]; ?>',// 必填，签名，见附录1
            jsApiList: [
                'checkJsApi',
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                'onMenuShareQQ',
                'onMenuShareWeibo',
                'openLocation',
                'getLocation'
            ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        });

        wx.ready(function () {
            // 1 判断当前版本是否支持指定 JS 接口，支持批量判断
            /*document.querySelector('#checkJsApi').onclick = function () {
             wx.checkJsApi({
             jsApiList: [
             'getNetworkType',
             'previewImage'
             ],
             success: function (res) {
             //alert(JSON.stringify(res));
             }
             });
             };*/
            // 2. 分享接口
            // 2.1 监听“分享给朋友圈”，按钮点击、自定义分享内容及分享结果接口
            wx.onMenuShareTimeline({
                title: '理财新用户注册领取体验金', // 分享标题
                link: "http://0247.jiaoyinet.com/gold/gshare?id="+uid, // 分享链接
                imgUrl: '123', // 分享图标
                success: function () {
                    // 用户确认分享后执行的回调函数
                    $("#wh_cover").hide();
                    $(".share").hide();
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });


            // 2.2 监听“分享到朋友”按钮点击、自定义分享内容及分享结果接口
            wx.onMenuShareAppMessage({
                title: '理财新用户注册领取体验金', // 分享标题
                desc: '理财新用户注册领取体验金', // 分享描述
                link:  "http://0247.jiaoyinet.com/gold/gshare?id="+uid, // 分享链接
                imgUrl: '123', // 分享图标
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
                    // 用户确认分享后执行的回调函数
                    $("#wh_cover").hide();
                    $(".share").hide();
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });


        })
        $(document).ready(function(){
            var UA=window.navigator.userAgent;  //使用设备
            var CLICK="click";
            if(/ipad|iphone|android/.test(UA)){   //判断使用设备
                CLICK="tap";
            }
            $("body").css("min-height",$(window).height());
            if($(window).width()<640){
                var B = $(window).width()/320*100*0.625+"%";
                $("html").css("font-size",B)
            }
            $(".downLoad-app").css("border-radius",$(".downLoad-app").height()/2);
            $("#share")[CLICK](function(event){
                event.preventDefault();
                $("body,html").animate({
                    scrollTop: 0
                }, 500);
                $("body").css("position","fixed");
                $("#wh_cover").show();
                $(".share").show();
            });

        })
        $(window).resize(function(){
            $("body").css("min-height",$(window).height());
            $(".downLoad-app").css("border-radius",$(".downLoad-app").height()/2);
        })
    </script>

<?php $this->endBlock(); ?>