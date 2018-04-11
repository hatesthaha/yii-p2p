<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaDeposit */

$this->title = Yii::t('app', 'Create Sina Deposit');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sina Deposits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sina-deposit-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
