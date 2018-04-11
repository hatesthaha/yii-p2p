<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\base\cms\Link */

$this->title = Yii::t('app', '更新 {modelClass}: ', [
    'modelClass' => '网站信息',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '网站信息'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="link-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
