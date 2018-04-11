<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\post\SignIn;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = yii::$app->request->baseUrl.'/../../backend/web/upload/';
?>
<style>
    .sRecord-listL{
        border: none;
    }
    .sign-QDNum{
        color: #5396ca;
    }
    .sign-info{
        background: #f7f7f7;
        padding: 1rem 1rem;
    }
    .sign-QDbtn{
        width: 90%;
        height: 45px;
        line-height: 45px;
        
    }
    .sign-QDbtn span{
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }
   .sign-infoT span{
        left: 12%;
        top: 0;
   }
    #body_a{
        position: absolute;
        top:0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #FFF;
        z-index: 9999;
    }
</style>
<div id="body_a"></div>
<span class="spanti-c2"></span>
<section>
    <!-- <div class="sRecord-Top">
        <div class="sRecord-MD">
            <p class="sRecord-M"><img width="100%" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/write.png" alt="奖励金额"></p>
            <span class="sRecord-W"><?php echo $signin['smoney']? $signin['smoney']:0; ?> </span>
        </div>
        <p>我的累计奖励金额金额（元）</p>
    </div> -->

    <div class="sRecord-listL">
        <ul class="clearFloat">
            <li>
                <p class="cor-78">昨日签到人数（人）</p>
                <p class="cor-3c"><span class="font-17"><?php echo  $count ?$count :0; ?></span></p>
            </li>
            <li>
                <p class="cor-78">昨日红包总额（元）</p>
                <p class="cor-3c"><span class="font-17"><?php echo  $signin['smoney']? ($signin['smoney']/$count) :0; ?></span></p>
            </li>
        </ul>
    </div>
</section>
    <section>
        <div class="sign-QD">
            <p>今日已签到人数（人）</p>
            <p class="sign-QDNum" id="countPeson"><?= $today_sign_in['data']['count'] ?></p>
            <?php if($isCheckin){ ?>
                <p class="sign-QDbtn" ><span style="background: #6b7b85">已签到</span></p>
            <?php }else { ?>
                <p class="sign-QDbtn" id="signin"><span>签到</span></p>
            <?php
            }
            $reward = 0;
            if($yesterday_sign_in['data']['count']){
                $reward = $yesterday_sign_in['data']['money'] / $yesterday_sign_in['data']['count'];
            }
            $reward_sum = 0;
            if($reward){
                $reward_sum = ($reward * 365)/0.08;
            }?>
            <p>昨日签到奖励<?php echo $reward.'元';?>，相当于帮您多投<?php echo $reward_sum.'元'; ?><span></span></p>
        </div>
        <div class="sign-info">
            <p class="sign-infoT"><span>签到规则</span></p>
            <ul>
                <li>在投资金≥1000的用户可以签到</li>
                <li>用户在成功签到后，次日奖励红包自动派发至昨日收益</li>
                <li>签到结束后，系统计算签到总人数并均分当日红包总额</li>
                <li>您在结算时间内（凌晨0点到2点）的投资金额小于1000元将无法领取签到红包</li>
            </ul>
        </div>
    </section>
    <br>
<!-- <section>
    <p class="s-recordT">奖励记录</p>
    <table class="s-record">
        <tr>
            <th>时间</th>
            <th>奖励记录</th>
            <th>签到状态</th>
        </tr>
        <?php if($all) { foreach ($all as $K=>$V) {?>
        <tr>
            <td><?php echo date('Y-m-d',$V->sign_in_time); ?></td>
            <td>奖励金额<?php echo number_format($V->sign_in_money,2); ?>元</td>
            <td>已签到</td>
        </tr>
        <?php } } else {?>
            <tr >
                <td colspan="3">暂无签到记录</td>
            </tr>
        <?php }?>
    </table>
</section> -->
<?php $this->beginBlock('inline_scripts'); ?>
<script>
    $(document).ready(function(){
        var UA=window.navigator.userAgent;  //使用设备
        var CLICK="click";
        if(/ipad|iphone|android/.test(UA)){   //判断使用设备
            CLICK="tap";
        }
        $("body").css("min-height",$(window).height());
        $("#signin")[CLICK](function(){
            $.post(
                "<?php echo yii\helpers\Url::to(['member/checkin']); ?>",
                {'_csrf':"<?php echo Yii::$app->request->getCsrfToken(); ?>"},
                function (data){ //回传函数
                    var jsonobj = eval('('+data+')');
                    if(jsonobj.errorNum == 0)
                    {
                        var totalPerson = <?php echo count(SignIn::find()->where('sign_in_time >='.strtotime(date("Y-m-d")))->all());?>;
                        $("#countPeson").empty();
                        $("#countPeson").text(totalPerson * 1 + 1);
                        $(this).css("border-color","#ffa200");
                        $(this).find("span").css("background","#ffa200");
                        $(this).find("span").html("已签到");
                    }
                    else if(jsonobj.errorNum == 1 && jsonobj.errorMsg != '在投资金小于1000')
                    {
                        $(".spanti-c2").html(jsonobj.errorMsg);
                        $(".spanti-c2").show();
                        setTimeout(function(){$(".spanti-c2").fadeOut(500);},2600);
                    }
                    if(jsonobj.errorMsg == '在投资金小于1000')
                    {
                        $(".spanti-c2").html('在投金额≥1000元才可以参与签到');
                        $(".spanti-c2").show();
                        setTimeout(function(){$(".spanti-c2").fadeOut(500);},2600);
                    }

                }
            );
        });
    });

    $(window).resize(function(){
        $("body").css("min-height",$(window).height());
    })
    $(document).ready(function(){
        $("body").css("min-height",$(window).height());
        $(".downLoad-app").css("border-radius",$(".downLoad-app").height()/2);
        $("#body_a").hide();
    })
    $(window).resize(function(){
        $("body").css("min-height",$(window).height());
        $(".downLoad-app").css("border-radius",$(".downLoad-app").height()/2);
    })
</script>
<?php $this->endBlock(); ?>