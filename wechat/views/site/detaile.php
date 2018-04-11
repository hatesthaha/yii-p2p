<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = yii::$app->request->baseUrl.'/../../backend/web/upload/';
?>
    <style>
        #body_a{
            position: absolute;
            top:0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #FFF;
            z-index: 9999;
        }
        .cor-aa{
            color: #a0aab4;
        }
        .cor-54{
            color: #5497cc;
        }
        .index-listT a{
            color: #3c3c3c;
        }
        .cor-3c{
            color: #3c3c3c;
        }
        .index-listIW{
            font-size: 1.3rem;
        }
        .pro-listWf{
            color: #a0aab4;
        }
        .Tips{
            padding-top: 0.4rem;
        }
        .Tips .Tips-T{
            font-size: 1.5rem;
            line-height: 2.8rem;
        }
    </style>
    <div id="body_a"></div>
        <div class="bg-f7"> 
            <section style="width:100%;height:1rem;"></section>
            <section class="proP-list" style="margin-top:0;">
                <p class="index-listT"><a href="javascript:void(0);" class="clearFloat"><?= $product->title ?></a></p>
                <div class="index-listL">
                    <ul class="clearFloat">
                        <li>
                            <p class="cor-aa">项目总金额</p>
                            <p class="font-17 cor-3c"><?php echo $product->amount;?></p>
                        </li>
                        <li>
                            <p class="cor-aa">活期收益率</p>
                            <p class="font-17 cor-3c"><?php echo round($product->rate,3) * 100;?>%+</p>
                        </li>
                        <li>
                            <p class="cor-aa">已投人数</p>
                            <p class="font-17 cor-3c"><?php echo $product->invest_people;?></p>
                        </li>
                    </ul>
                </div>
                <?php  $now_time=strtotime("now");?>
                <?php if( ($product->invest_sum >= $product->amount) || ($product->end_at < $now_time)) { ?>
                <div class="index-listIng clearFloat">
                    <p class="index-listIW cor-3c">投资进度</p>
                    <p class="progress-bar"><span class="progress-num" style="width:100%;" ></span></p>
                    <p class="cor-aa">100%</p>
                </div>
                <?php }else{ ?>
                    <div class="index-listIng clearFloat">
                        <p class="index-listIW cor-3c">投资进度</p>
                        <p class="progress-bar"><span class="progress-num" style="width:<?php echo ceil(($product->invest_sum)/$product->amount * 100);?>%; " ></span></p>
                        <p class="cor-aa"><?php echo ceil(($product->invest_sum)/$product->amount * 100);?>%</p>
                    </div>
                <?php } ?>
<!--                 <div class="pro-listW">
                    <p class="pro-listWt cor-3c">投资限额</p>
                    <p class="pro-listWf"><?php echo $product->each_min; ?>~<?php echo $product->each_max; ?>元</p>
                </div>
 -->                <div class="pro-listW">
                    <p class="pro-listWt cor-3c">收益时间</p>
                    <p class="pro-listWf">投资成功即可开始计算收益</p>
                </div>
                <div class="pro-listW">
                    <p class="pro-listWt cor-3c">保障方式</p>
                    <p class="pro-listWf">本息保护</p>
                </div>
                <div class="pro-listW">
                    <p class="pro-listWt cor-3c">锁定期限</p>
                    <p class="pro-listWf">无锁定期，可随时赎回</p>
                </div>
                <div class="pro-listW">
                    <p class="pro-listWt cor-3c">投资协议</p>
                    <p class="pro-listWf"><a class="cor-54" href="<?php echo Url::to(['site/help']);?>">点击查看</a></p>
                </div>
                <div class="pro-listW">
                    <p class="pro-listWt cor-3c">如何投资</p>
                    <p class="pro-listWf"><a class="cor-aa" href="<?php echo Url::to(['site/help']);?>">查看帮助</a></p>
                </div>
            </section>
            <section class="index-listBtn">
                <?php if( ($product->invest_sum >= $product->amount) || ($product->end_at < $now_time)) { ?>
                    <a href="javascript:;" style="">已抢光</a>
                <?php }else{ ?>
                    <a href="<?php echo Url::to(['invest/index','id' => $product->id]);?>">立刻投资</a>
                <?php } ?>
            </section>
            <section class="Tips">
                <p class="Tips-T">投资小贴示</p>
                <div class="Tips-W">
                    <p>每期投资被抢光时间非常快，建议在投资项目抢购前做好预充值，避免因充值耽误时间导致项目售罄。</p>
                </div>
            </section>
        </div>
<?php $this->beginBlock('inline_scripts'); ?>
    <script>
        var bodyFoo = function(){
            $('.way-divR').each(function(){
                var T = $(this).parent(".way-div").height();
                $(this).css("margin-top",(T-$(this).height())/2)

            })
            $('.way-divR').each(function(){
                var T = $(this).parent(".way-div").width();
                var T_l = $(this).siblings(".way-divL").width();
                $(this).width(T-T_l-28);
            });
            $('.safety-divR').each(function(){
                var T = $(this).parent(".safety-div").height();
                $(this).css("margin-top",(T-$(this).height())/2)
            });
            $('.safety-divR').each(function(){
                var T = $(this).parent(".safety-div").width();
                var T_l = $(this).siblings(".safety-divL").width();
                $(this).width(T-T_l-15);

            });
        }
        $(document).ready(function(){
            $("body").css("min-height",$(window).height());
            bodyFoo();
            $("#body_a").hide();
        })
    </script>
<?php $this->endBlock(); ?>