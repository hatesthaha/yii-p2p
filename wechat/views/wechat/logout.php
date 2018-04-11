<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
$upload = yii::$app->request->baseUrl.'/../../backend/web/upload/';
?>

<div class="wapper bgd-f5f5f4">
    <form id="form" action="<?= \yii\helpers\Url::to(['wechat/logout']) ?>" method="post">
        <div>
            <input type="hidden" name="open_id" value="<?= yii::$app->request->get('open_id') ?>"/>
            <input type ="hidden" name="_csrf" value="<?php echo yii::$app->request->getCsrfToken();?>" />
            <input style="box-shadow: #61a627 0px 5px 0px 0px; background: #6eb92b;margin-top: 30%; margin-left: 50%" type="submit" value="关闭免登录模式"
                   class="login_btn">
        </div>
    </form>
 </div>
