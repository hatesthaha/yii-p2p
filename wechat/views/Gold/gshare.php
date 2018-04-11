<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\base\experience\Rule;
use common\models\UcenterMember;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = yii::$app->request->baseUrl.'/../../backend/web/upload/';
$user = UcenterMember::find()->where(['id'=>$_GET['id']])->one();
?>

<section>
    <p><img class="d-block" width="100%" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/sign2-bg.jpg" alt="理财王"></p>
    <div class="Invitat-top">
        <p class="Invitat-tp1 Experience-tp1">您的好友 <?= $user->real_name ?> 已经在理财王赚翻了~</p>
        <p class="Invitat-tp2">新用户完成任务，最高可领<?= $rule = Rule::find()->where(['title'=>'手机号注册'])->one()->money; ?>元体验金~</p>
        <p class="Experience-tbtn"><a href="<?php echo Url::to(['gold/gsignup', 'code' => $user->invitation_code,'id'=>$user->id]);?>">立即购买体验</a></p>
    </div>
</section>
<section>
    <div class="Experience-bt">
        <p><img width="100%" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/Experience3.jpg" alt=""></p>
    </div>
    <div class="Experience-bt">
        <p><img width="100%" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/Experience1.jpg" alt=""></p>
    </div>
    <div>
        <p><img width="100%" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/Experience2.jpg" alt=""></p>
    </div>
    <div class="Invitat-Mdiv4">
        <p><a href="#"><img width="100%" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/Experience4.jpg" alt=""></a></p>
    </div>
</section>

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


