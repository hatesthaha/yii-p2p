<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\base\asset\Info */

$this->title = Yii::t('app', '更新 {modelClass}: ', [
    'modelClass' => '会员资金',
]) . ' ' . $model->member_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '会员资金'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->member_id, 'url' => ['view', 'id' => $model->member_id]];
$this->params['breadcrumbs'][] = Yii::t('app', '更新');
?>
<div class="info-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
