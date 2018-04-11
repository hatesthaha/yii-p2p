<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\yeepay\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '充值记录');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            [
                'attribute' => 'uid',
                'value'=>function ($model) {
                    return $model->member ? $model->member->username : '-';
                },
            ],
//            'orderid',
            [
                'attribute' => 'transtime',
                'format' => ['date', 'php:Y-m-d']

            ],

            [
                'attribute' => 'amount',
                'value'=>function ($model) {
                    return $model->amount*0.01;
                },
            ],
            // 'userip',
             'productname',
            // 'identityid',
            // 'orderexpdate',
             'phone',
             'status',
            // 'sendtime:datetime',
            // 'yborderid',
            // 'ybamount',
             'msg',
            // 'create_at',
            // 'update_at',

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
