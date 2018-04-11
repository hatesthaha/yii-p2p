<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\base\fund\Thirdproduct */

$this->title = Yii::t('app', '驳回', [
    'modelClass' => 'Thirdproduct',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => "第三方债权", 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '驳回';
?>
<div class="thirdproduct-update">

    <?= $this->render('_reform', [
        'model' => $model,
    ]) ?>

</div>
