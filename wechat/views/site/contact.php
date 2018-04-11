<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = yii::$app->request->baseUrl.'/../../backend/web/upload/';
?>
<section>
    <ul class="con-list">
        <li>
            <a class="clearFloat" href="tel:400-8888-888">
                <img width="40px" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/con-phone.png" alt="客服电话">
                <span>客服电话：400-8888-888</span>
            </a>
        </li>
        <li>
            <a class="clearFloat" href="javascript:void(0);">
                <img style="margin-top:8px" width="40px" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/con-weixin.png" alt="客服电话">
                <span>微信服务号：理财王理财</span>
            </a>
        </li>
        <li>
            <a class="clearFloat" target="_blank" href="http://weibo.com/u/3960759785">
                <img style="margin-top:8px" width="40px" src="<?= Yii::getAlias('@web') . '/' ?>rq-images/con-weibo.png" alt="客服电话">
                <span>微博：@理财王理财</span>
            </a>
        </li>
    </ul>
</section>
