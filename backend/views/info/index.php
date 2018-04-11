<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\base\asset\InfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '会员资金');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="info-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'member_id',
                'value'=>function ($model) {
                    return $model->member? $model->member->username: '' ;
                },
            ],
            'bank_card',
//            'bank_card_phone',
            'balance',
            'invest',
            'total_invest',
            'profit',
            'total_revenue',

            ['class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return '' ;
                    },
//                    'delete' => function ($url, $model, $key) {
//                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
//                            'title' => '删除',
//                            'data'=>[
//                                'confirm'=>'你确定要删除吗？',
//                                'method'=>'post'
//                            ]
//                        ] ) ;
//                    },
                ]
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{lock}{unbundling}{collect}{uncollect}',
                'buttons' => [
                    'lock' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-comment"></span>&nbsp;&nbsp;', $url, ['title' => '冻结资金'] );
                    },
//                    'unlock' => function ($url, $model, $key) {
//                        return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', $url, ['title' => '解冻资金'] );
//                    },
                    'unbundling' => function ($url, $model, $key) {
                        return $model->bank_card ? Html::a('&nbsp;&nbsp;<span class="glyphicon glyphicon-tasks"></span>', $url, [
                            'title' => '解绑银行卡',
                            'data'=>[
                                'confirm'=>'你确定要解绑银行卡吗？',
                                'method'=>'post'
                            ]
                        ] ) :'' ;
                    },
                    'collect' => function ($url, $model, $key) {
                        return Html::a('&nbsp;&nbsp;<span class="glyphicon glyphicon-share-alt"></span>&nbsp;&nbsp;', $url, ['title' => '发放资金'] );
                    },
                    'uncollect' => function ($url, $model, $key) {
                        return Html::a('&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;&nbsp;', $url, ['title' => '提取资金'] );
                    },
                ],
                'headerOptions' => ['width' => '80'],
            ],
        ],
    ]); ?>

</div>
