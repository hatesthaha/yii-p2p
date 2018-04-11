<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\sinapay\SinaWithdrawSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '新浪提现操作');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sina-withdraw-index">



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
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
            //'type',
            [
                'attribute'=>'status',
                'format' => 'html',
                'value'=>function ($model) {
                    if ($model->status ===5) {
                        $class = 'label-success';
                    }elseif ($model->status === 4) {
                        $class = 'label-warning';
                    }elseif ($model->status === 3) {
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
