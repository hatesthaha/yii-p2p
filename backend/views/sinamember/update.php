<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaMember */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Sina Member',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sina Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="sina-member-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
