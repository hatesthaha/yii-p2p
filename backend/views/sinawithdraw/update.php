<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaWithdraw */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Sina Withdraw',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sina Withdraws'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="sina-withdraw-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
