<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\base\activity\Card */

$this->title = Yii::t('app', '更新 {modelClass}: ', [
    'modelClass' => 'Card',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '增息卡'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="card-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
