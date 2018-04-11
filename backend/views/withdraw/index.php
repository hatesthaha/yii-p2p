<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\yeepay\WithdrawSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '提现记录');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="withdraw-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'uid',
                'value'=>function ($model) {
                    return $model->member ? $model->member->username : '-';
                },
            ],

            // 'card_last',
            [
                'attribute' => 'amount',
                'value'=>function ($model) {
                    return $model->amount*0.01;
                },
            ],

            // 'userip',
            // 'ybdrawflowid',
            'status',
            'msg',
            [
                'attribute' => 'create_at',
                'format' => ['date', 'php:Y-m-d']

            ],
            // 'update_at',


        ],
    ]); ?>

</div>
