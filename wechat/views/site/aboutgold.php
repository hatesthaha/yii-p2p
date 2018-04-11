<?php
use yii\helpers\Html;
use yii\helpers\Url;
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
                <td class="ex-num"><span>88888.88</span></td>
                <td class="ex-num"><span>8.88</span> <a href="#">提现</a></td>
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
                        <p class="experience4-tabP">获取XXXX元体验金</p>
                    </td>
                    <td class="experience4-tabCome">
                        <a href="#">去完成</a>
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
                        <p class="experience4-tabP">获取XXXX元体验金</p>
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
                        <p class="experience4-tabP">获取XXXX元体验金</p>
                    </td>
                    <td class="experience4-tabCome">
                        <a href="#">去完成</a>
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
                        <p class="experience4-tabP">获取XXXX元体验金</p>
                    </td>
                    <td class="experience4-tabCome">
                        <a href="#">去完成</a>
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
                        <p class="experience4-tabP">获取XXXX元体验金</p>
                    </td>
                    <td class="experience4-tabCome">
                        <a href="#">去完成</a>
                    </td>
                </tr>
            </table>
        </li>
        <p class="experienct4-BOT"><a href="#">活动详情</a></p>
    </ul>

</div>


<?php $this->beginBlock('inline_scripts'); ?>
    <script>
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
            $("#check")[CLICK](function(event){
                event.preventDefault();
                $("#wh_cover").show();
                $(".downLoad-chose").show();
            });

        })
        $(window).resize(function(){
            $("body").css("min-height",$(window).height());
            $(".downLoad-app").css("border-radius",$(".downLoad-app").height()/2);
        })
    </script>

<?php $this->endBlock(); ?>