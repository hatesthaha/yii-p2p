<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SiteSinaBalance */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Site Sina Balances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-sina-balance-view">

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
            'uid',
            'identity_id',
            'phone',
            'user_name',
            'bank_card',
            'site_balance',
            'sina_available_balance',
            'user_earnings',
            'sina_balance',
            'sina_bonus',
            'sina_bonus_day',
            'sina_bonus_month',
            'sina_bonus_sum',
            'create_time',
            'status',
            'msg',
            'create_at',
            'update_at',
        ],
    ]) ?>

</div>
