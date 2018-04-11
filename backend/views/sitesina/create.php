<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SiteSinaBalance */

$this->title = 'Create Site Sina Balance';
$this->params['breadcrumbs'][] = ['label' => 'Site Sina Balances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-sina-balance-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
