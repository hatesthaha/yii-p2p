<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\lianlian\payLLSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '连连支付充值记录');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pay-ll-index">



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'uid',
            'idcard',
            'real_name',

            [
                'attribute' => 'user_id',
                'value'=>function ($model) {
                    return $model->member ? $model->member->username : '-';
                },
            ],
            // 'busi_partne',
            // 'no_order',
            // 'name_goods',
             'money_order',
             'card_no',
            // 'from_ip',
            // 'bank_code',
            // 'status',
             'remark',
            // 'sign_type',
            // 'sign',
            // 'oid_paybill',
            // 'money_lianlian',
            [
                'attribute' => 'settle_date',
                'format' => ['date', 'php:Y-m-d']

            ],

            // 'pay_type',
            // 'create_at',
            // 'update_at',


        ],
    ]); ?>

</div>
