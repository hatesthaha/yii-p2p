<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use common\models\base\cms\Cat;
use common\models\base\cms\Link;

/* @var $this \yii\web\View */
/* @var $content string */
\www\assets\AppAsset::register($this);
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
//友情链接
try {
    $cat_id = Cat::find()->where(['name' => '友情链接', 'status' => 1])->one()->id;
    if ($cat_id) {
        $friendly_links = Link::find()->where(['cat_id' => $cat_id, 'status' => 1])->asArray()->all();
    }
} catch (ErrorException $e) {
    $friendly_links = "";
}
?>
<!-- footer -->

<div id="footer">
    <div class="foot_top main">
        <div class="dilb left">
            <ul>
                <h5>我要投资</h5>
                <li><a href="<?php echo yii\helpers\Url::to(['investment/investing']); ?>">理财王</a></li>
            </ul>
            <!--            <ul>-->
            <!--            	<h5>安全保障</h5>-->
            <!--<!--                <li><a href="">标的认证说明</a></li>-->
            <!--<!--                <li><a href="-->
            <?php ////echo yii\helpers\Url::to(['site/partner']); ?><!--<!--">合作伙伴</a></li>-->
            <!--<!--                <li><a href="-->
            <?php ////echo yii\helpers\Url::to(['site/guarantee']); ?><!--<!--">担保机构</a></li>-->
            <!--                <li><a href="">保障金制度</a></li>-->
            <!--                <li><a href="">风险控制</a></li>-->
            <!--                <li><a href="">合同审核</a></li>-->
            <!--            </ul>-->
            <ul>
                <h5>使用帮助</h5>
                <li><a href="<?php echo yii\helpers\Url::to(['about/help']); ?>">如何认证</a></li>
                <li><a href="<?php echo yii\helpers\Url::to(['about/help']); ?>">绑定银行卡</a></li>
                <li><a href="<?php echo yii\helpers\Url::to(['about/help']); ?>">充值流程</a></li>
                <li><a href="<?php echo yii\helpers\Url::to(['about/help']); ?>">投资流程</a></li>
                <li><a href="<?php echo yii\helpers\Url::to(['about/help']); ?>">赎回和提现</a></li>
            </ul>
            <ul>
                <h5>关于我们</h5>
                <li><a href="<?php echo yii\helpers\Url::to(['about/company']); ?>">公司介绍</a></li>
                <!--                <li><a href="-->
                <?php //echo yii\helpers\Url::to(['about/partner']); ?><!--">合作伙伴</a></li>-->
                <!--                <li><a href="-->
                <?php //echo yii\helpers\Url::to(['about/news']); ?><!--">最新动态</a></li>-->
                <!--                <li><a href="-->
                <?php //echo yii\helpers\Url::to(['about/media']); ?><!--">媒体报道</a></li>-->
                <li><a href="<?php echo yii\helpers\Url::to(['about/contact']); ?>">联系我们</a></li>
                <li><a href="<?php echo yii\helpers\Url::to(['about/join']); ?>">加入我们</a></li>
            </ul>
            <ul>
                <h5>关注我们</h5>
                <li>
                    <a class="lx gy" href="http://weibo.com/u/3960759785" target="_blank"></a>
                </li>
                <li class="huwx">
                    <div class="wx gy" href=""></div>
                    <div class="yc">
                        <span></span>
                        <b>扫二维码下载</b>

                        <p><img src="<?= $directoryAsset; ?>/images/erwm.png"/></p>
                    </div>
                </li>
            </ul>
            <ul>
                <h5>APP下载</h5>
                <li class="huaz">
                    <div class="az gy" href=""></div>
                    <div class="yc">
                        <span></span>
                        <b>扫二维码下载</b>

                        <p><img src="<?= $directoryAsset; ?>/images/phone.png"/></p>
                    </div>
                </li>
                  <li class="hupg">
                    <div class="pg gy" href=""></div>
                               <div class="yc">
                                <span></span>
                                    <b>扫二维码下载</b>
                                   <p><img src="<?= $directoryAsset; ?>/images/phone.png"/></p>
                               </div>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
        <div class="dbsjb left">
            <p><?php if ($tel1) echo $tel1; ?></p>

            <p><?php if ($tel2) echo $tel2; ?></p>

            <p class="sj">（<?php if ($work) echo $work; ?>）</p>

            <p class="sj"><?php if ($email) echo $email; ?></p>
        </div>
        <div class="clear"></div>
    </div>
    <?php if ($friendly_links) {
        foreach ($friendly_links as $K => $V) { ?>
            <div class="foot_con main">
                <p>
                    <a href="<?php echo $V['link']; ?>"><?php echo $V['intro']; ?></a>
                </p>
            </div>
        <?php }
    } ?>
    <div class="foot_bot">
        <div class="main">
            <p>ICP备案编号：京ICP备15037172号-1 版权所有 北京理财王投资有限公司</p>

<!--            <p><a href="//webscan.360.cn/index/checkwebsite/url/www.licaiwang.com"><img border="0"-->
<!--                                                                                             src="//img.webscan.360.cn/status/pai/hash/2b0f01aba2602cb3b98bd8ed2f6ea146"/></a>-->
<!--            </p>-->
            <table style="margin-left:45%;" width="135" border="0" cellpadding="2" cellspacing="0" title="Click to Verify - This site chose GeoTrust SSL for secure e-commerce and confidential communications.">
                <tr>
                    <td width="135" align="center" valign="top"><script type="text/javascript" src="https://seal.geotrust.com/getgeotrustsslseal?host_name=www.licaiwang.com&amp;size=S&amp;lang=en"></script><br />
                        <a href="http://www.geotrust.com/ssl/" target="_blank"  style="color:#000000; text-decoration:none; font:bold 7px verdana,sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;"></a></td>
                </tr>
            </table>
            <!--<
            <p>北京市信息服务业行业协会会员单位 | 北京市金融信息服务业专业委员会会员单位 | 北京市网络信贷服务业企业联盟单位</p>
            <ul>
            	<li><a href=""><img src="<?= $directoryAsset; ?>/images/verify_09.png" onmousemove="this.src='<?= $directoryAsset; ?>/images/verify_09_h.png'" onmouseout="this.src='<?= $directoryAsset; ?>/images/verify_09.png'" /></a></li>
                <li><a href=""><img src="<?= $directoryAsset; ?>/images/verify_03.png" onmousemove="this.src='<?= $directoryAsset; ?>/images/verify_03_h.png'" onmouseout="this.src='<?= $directoryAsset; ?>/images/verify_03.png'" /></a></li>
                <li><a href=""><img src="<?= $directoryAsset; ?>/images/verify_05.png" onmousemove="this.src='<?= $directoryAsset; ?>/images/verify_05_h.png'" onmouseout="this.src='<?= $directoryAsset; ?>/images/verify_05.png'" /></a></li>
                <li><a href=""><img src="<?= $directoryAsset; ?>/images/verify_07.png" onmousemove="this.src='<?= $directoryAsset; ?>/images/verify_07_h.png'" onmouseout="this.src='<?= $directoryAsset; ?>/images/verify_07.png'" /></a></li>
                <li><a href=""><img src="<?= $directoryAsset; ?>/images/verify_01.png" onmousemove="this.src='<?= $directoryAsset; ?>/images/verify_01_h.png'" onmouseout="this.src='<?= $directoryAsset; ?>/images/verify_01.png'" /></a></li>
                <div class="clear"></div>
            </ul>
             -->
        </div>
    </div>