<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\base\activity\ActivityLog */

$this->title = '更新: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '更新', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="activity-log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
