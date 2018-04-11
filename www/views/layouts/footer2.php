
<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

/* @var $this \yii\web\View */
/* @var $content string */
\www\assets\AppAsset::register($this);
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
?>
<!-- footer -->
 
 <style>
     body{
        background: #f2f3f5;
     }
     #footer .foot_bot p {
      text-align: center;
      color: #999;
      line-height: 24px;
    }
    #footer .foot_bot {
      background-color: #f2f3f5;
      width: 100%;
      padding: 10px 0 10px;
    }
    .xjfriend-link a {
      border-right: 1px solid #999;
      color: #999;
      font-size: 12px;
      margin-left: -3px;
      padding: 0 15px;
    }
    .xjfriend-link a:last-child {
      border-right: 0;
    }

 </style>
 <div id="footer">
    <div class="foot_bot">
    <div class="foot_bot" style="background:#f2f3f5;">
        <div class="main">
            <p class="xjfriend-link">
                <a href="<?php echo yii\helpers\Url::to(['about/company']); ?>">关于我们</a>
                <a href="<?php echo yii\helpers\Url::to(['about/help']); ?>">帮助中心</a>
                <a href="<?php echo yii\helpers\Url::to(['insurance/plan']); ?>">安全保障</a>
                <a href="<?php echo yii\helpers\Url::to(['about/contact']); ?>">联系我们</a>
            </p>
            <p>ICP备案编号：京ICP备15037172号-1  版权所有 北京理财王投资有限公司</p>
            <table style="margin-left:45%;" width="135" border="0" cellpadding="2" cellspacing="0" title="Click to Verify - This site chose GeoTrust SSL for secure e-commerce and confidential communications.">
                <tr>
                    <td width="135" align="center" valign="top"><script type="text/javascript" src="https://seal.geotrust.com/getgeotrustsslseal?host_name=www.licaiwang.com&amp;size=S&amp;lang=en"></script><br />
                        <a href="http://www.geotrust.com/ssl/" target="_blank"  style="color:#000000; text-decoration:none; font:bold 7px verdana,sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;"></a></td>
                </tr>
            </table>
            <!-- 
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