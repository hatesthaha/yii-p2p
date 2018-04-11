<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\LoginForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */
$this->title = '登录';
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@almasaeed/');
?>

<div style="background: rgba(0, 0, 0, 0) url('<?php echo $directoryAsset ?>/images/please_download.jpg') no-repeat scroll center top;height: 700px;width: 100%;"></div>