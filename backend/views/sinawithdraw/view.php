<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaWithdraw */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sina Withdraws'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sina-withdraw-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'uid',
            'out_trade_no',
            'identity_id',
            'card_id',
            'site_balance',
            'sina_balance',
            'money',
            'money_fund',
            'money_site',
            'money_sina',
            'type',
            'status',
            'msg',
            'create_at',
            'update_at',
        ],
    ]) ?>

</div>
