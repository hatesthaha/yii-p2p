<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SiteSinaBalance */

$this->title = 'Update Site Sina Balance: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Site Sina Balances', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="site-sina-balance-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
