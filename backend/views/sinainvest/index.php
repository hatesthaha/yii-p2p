<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\sinapay\SinaInvestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '新浪投资记录');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sina-invest-index">



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'uid',
            'identity_id',
            //'out_trade_no',
            'summary',
            // 'trade_close_time',
            // 'payer_ip',
            // 'pay_type',
            // 'account_type',
            // 'goods_id',
            'money',
            [
                'attribute'=>'status',
                'format' => 'html',
                'value'=>function ($model) {
                    if ($model->status ===3) {
                        $class = 'label-success';
                    }elseif ($model->status === 1) {
                        $class = 'label-success';
                    }elseif ($model->status === 2) {
                        $class = 'label-warning';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->statusLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    \common\models\sinapay\SinaInvest::labels(),
                    ['class' => 'form-control', 'prompt' => '请筛选']
                )
            ],
            'msg',
            // 'payee_out_trade_no',
            // 'payee_identity_id',
            // 'payee_account_type',
            'payee_amount',
            'payee_summary',
            'refund_amount',
            'refund_summary',
            [
                'attribute' => 'create_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
                'headerOptions' => ['width' => '100'],
            ],
            // 'update_at',

        ],
    ]); ?>

</div>
