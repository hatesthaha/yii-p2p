<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\base\site\IdcardLog */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Idcard Log',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Idcard Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="idcard-log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
