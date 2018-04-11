<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\base\ucenter\Cat */

$this->title = Yii::t('app', '更新 {modelClass}: ', [
    'modelClass' => '会员分类',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '会员分类'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="cat-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
