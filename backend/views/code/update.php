<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\base\activity\Code */

$this->title = Yii::t('app', '更新 兑换码: ', [
    'modelClass' => 'Code',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Codes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="code-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
