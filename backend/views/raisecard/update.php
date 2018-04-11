<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\base\activity\RaiseCard */

$this->title = Yii::t('app', '更新 {modelClass}: ', [
    'modelClass' => '增值卡',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Raise Cards'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', '更新');
?>
<div class="raise-card-update">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
