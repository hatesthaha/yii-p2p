<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = yii::$app->request->baseUrl.'/../../backend/web/upload/';
?>
<span class="spanti-c2"></span>
<section>
    <p><img class="d-block" width="100%" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/sign2-bg.jpg" alt="理财王"></p>
    <div class="Invitat-top">
        <p class="Invitat-tp1">邀请您的好友一起使用活理财，领取体验金</p>
        <p class="Invitat-tp2">赚钱的好事要分享，独食难肥哟~</p>
        <form class="Invitat-form" action="#" method="post">
            <input type="text" id="invitation_url" value="<?php echo yii::$app->urlManager->hostInfo . yii\helpers\Url::to(['site/signup', 'code' => $invitation_code]); ?>" placeholder="" />
            <input id="check" type="button" onclick="copyUrl();" value="立即复制" />
        </form>
    </div>
</section>
<section>
    <div class="Invitat-Mdiv1">
        <p><img width="100%" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/invitaxj.jpg" alt=""></p>
    </div>
    <div class="Invitat-Mdiv2">

</section>
<?php $this->beginBlock('inline_scripts'); ?>
<script>
    function copyUrl() {
        var Url2 = document.getElementById("invitation_url");
        Url2.select(); // 选择对象
        document.execCommand("Copy"); // 执行浏览器复制命令
        $(".spanti-c2").html("已复制好，可贴粘。");
        $(".spanti-c2").show();
        setTimeout(function(){$(".spanti-c2").fadeOut(500);},2600);
    }
</script>

<?php $this->endBlock(); ?>


