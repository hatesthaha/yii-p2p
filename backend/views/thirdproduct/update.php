<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\base\fund\Thirdproduct */

$this->title = Yii::t('app', '更新', [
    'modelClass' => 'Thirdproduct',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => "第三方债权", 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="thirdproduct-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
