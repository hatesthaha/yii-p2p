<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\invation\AssetConfig */

$this->title = Yii::t('app', '更新 {modelClass}: ', [
    'modelClass' => '配置',
]) . ' ';

?>
<div class="asset-config-update">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
