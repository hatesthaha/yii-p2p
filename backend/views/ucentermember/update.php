<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UcenterMember */

$this->title = Yii::t('app', '更新 {modelClass}: ', [
    'modelClass' => '会员',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '会员信息'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="ucenter-member-update">


    <?= $this->render('_upform', [
        'model' => $model,
    ]) ?>

</div>
