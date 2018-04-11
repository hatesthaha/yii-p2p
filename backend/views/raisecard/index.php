<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\base\activity\RaiseCard;
/* @var $this yii\web\View */
/* @var $searchModel common\models\base\activity\RaiseCardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '发放体验金');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="raise-card-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', '指定会员发体验金'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'member_id',
                'value'=>function ($model) {
                    return $model->member ? $model->member->username : '-';
                },
            ],

            //'fund_order_id',
//            'use_start_at:datetime',
            [
                'attribute' => 'validity_start_at',
                'format' => ['date', 'php:Y-m-d']
            ],
            [
                'attribute' => 'validity_out_at',
                'format' => ['date', 'php:Y-m-d']
            ],

            // 'validity_time:datetime',
            [
                'attribute' => 'rate',
                'value' => function ($model) {
                    return $model->rate;
                }
            ],
//            [
//                'attribute' => 'status',
//                'format' => 'html',
//                'value' => function ($model) {
//                    if ($model->status === $model::STATUS_ACTIVE) {
//                        $class = 'label-success';
//                    } elseif ($model->status === $model::STATUS_INACTIVE) {
//                        $class = 'label-warning';
//                    } else {
//                        $class = 'label-danger';
//                    }
//
//                    return '<span class="label ' . $class . '">' . $model->statusLabel . '</span>';
//                },
//                'filter' => Html::activeDropDownList(
//                    $searchModel,
//                    'status',
//                    RaiseCard::labels(),
//                    ['class' => 'form-control', 'prompt' => Yii::t('app', 'Please Filter')]
//                )
//            ],
            // 'create_at',
            // 'update_at',

            ['class' => 'yii\grid\ActionColumn',
                            'header' => '操作',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return '';
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => '删除',
                            'data'=>[
                                'confirm'=>'你确定要删除吗？',
                                'method'=>'post'
                            ]
                        ] ) ;
                    },
                ],
            ],
        ],
    ]); ?>

</div>
