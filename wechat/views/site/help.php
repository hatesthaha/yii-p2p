<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = yii::$app->request->baseUrl.'/../../backend/web/upload/';
?>
<style>
    .help-two{
        padding: 0;
    }
    .help-twocon{
        display: block;
    }
    .help-two li{
        padding: 0 1rem;
    }
    .help-twocon p{
        margin: 0 0 1rem;
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
<section id="body_a"></section>
<section>
    <!-- <p><img class="d-block" width="100%" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/wyh.jpg" alt="为什么选择我们"></p> -->
    <ul class="help-two">
        <?php if($infos) {foreach ($infos as $K=>$V){?>
        <li>
            <p class="help-twoT clearFloat"><a href="#"><span>1、<?php echo $V->title;?></span></a></p>
            <div class="help-twocon">
                <p><?php echo strip_tags($V->content);?></p>
            </div>
        </li>
        <?php }}?>

    </ul>
</section>
<?php $this->beginBlock('inline_scripts'); ?>
<script>
    $(document).ready(function(){
        $("body").css("min-height",$(window).height());
        var UA=window.navigator.userAgent;  //使用设备
        var CLICK="click";
        if(/ipad|iphone|android/.test(UA)){   //判断使用设备
            CLICK="tap";
        }
        // $(".help-twoT")[CLICK](function(event){
        //     event.preventDefault();
        //     var T_S = $(this).siblings(".help-twocon");
        //     // $(".help-twocon:not(this)").slideUp();
        //     if(T_S.css('display') == 'none'){
        //         $(this).find("i").addClass("icon-angle-down");
        //         T_S.css('display',"block");
        //     }else{
        //         $(this).find("i").removeClass("icon-angle-down");
        //         T_S.css('display',"none");
        //     }
        // });
        $("#body_a").hide();
    })
    $(window).resize(function(){
        $("body").css("min-height",$(window).height());
    })
</script>
<?php $this->endBlock(); ?>

