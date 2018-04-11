<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\base\setting\BankList */

$this->title = 'Update Bank List: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bank Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bank-list-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
