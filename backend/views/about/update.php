<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\base\cms\About */

$this->title = Yii::t('app', '更新 {modelClass}: ', [
    'modelClass' => '关于我们',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '关于我们'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="about-update">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
