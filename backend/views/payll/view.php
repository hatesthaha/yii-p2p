<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\lianlian\payLL */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pay Lls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pay-ll-view">

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
            'idcard',
            'real_name',
            'user_id',
            'busi_partne',
            'no_order',
            'name_goods',
            'money_order',
            'card_no',
            'from_ip',
            'bank_code',
            'status',
            'remark',
            'sign_type',
            'sign',
            'oid_paybill',
            'money_lianlian',
            'settle_date',
            'pay_type',
            'create_at',
            'update_at',
        ],
    ]) ?>

</div>
