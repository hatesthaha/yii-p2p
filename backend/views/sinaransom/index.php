<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\sinapay\SinaRansomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '新浪赎回操作');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sina-ransom-index">



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'uid',
            'identity_id',
            'out_trade_no',
            'summary',
            // 'trade_close_time',
            // 'payer_id',
            'payer_ip',
            // 'pay_method',
            'money_sina',
            // 'payee_out_trade_no',
            [
                'attribute'=>'status',
                'format' => 'html',
                'value'=>function ($model) {
                    if ($model->status ===1) {
                        $class = 'label-success';
                    }elseif ($model->status === 2) {
                        $class = 'label-success';
                    }elseif ($model->status === 0) {
                        $class = 'label-warning';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->statusLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    \common\models\sinapay\SinaRansom::labels(),
                    ['class' => 'form-control', 'prompt' => '请筛选']
                )
            ],
            'msg',
            [
                'attribute' => 'create_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
                'headerOptions' => ['width' => '100'],
            ],
            // 'update_at',

        ],
    ]); ?>

</div>
