<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\base\experience\Rule */

$this->title = Yii::t('app', '更新 {modelClass}: ', [
    'modelClass' => '体验金',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '体验金'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="rule-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
