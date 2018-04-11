<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\base\fund\product */

$this->title = Yii::t('app', '更新 {modelClass}: ', [
    'modelClass' => '项目',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '项目'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="product-update">



    <?= $this->render('_upform', [
        'model' => $model,
    	'products' =>$products,
    ]) ?>

</div>
