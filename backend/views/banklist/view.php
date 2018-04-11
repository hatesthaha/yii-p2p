<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\base\setting\BankList */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bank Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-list-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'bank_name',
            'bank_code',
            'service_tel',
            'bank_logo',
            'card_type',
            'card_attribute',
            'binding_pay_1time_limit',
            'binding_pay_time_limit',
            'binding_pay_day_limit',
            'binding_pay_time_min_limit',
            'is_valid',
            'create_at',
            'update_at',
        ],
    ]) ?>

</div>
