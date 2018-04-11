<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\base\fund\ThirdproductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '已审核');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thirdproduct-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'intro',
            'source',
            [
                'attribute' => 'creditor',
                'value'=>function ($model) {
                    return $model->typeocUser ? $model->typeocUser->username : '-';
                },
            ],
            [
                'attribute' => 'maxcreditor',
                'value'=>function ($model) {
                    return $model->typemaxUser ? $model->typemaxUser->username : '-';
                },
                'headerOptions' => ['width' => '100'],
            ],
            // 'contract',
            // 'remarks',
            'amount',
            // 'start_at',
            // 'end_at',
            // 'rate',
            // 'invest_people',
            // 'invest_sum',
            // 'create_at',
            // 'update_at',
            // 'status',
            // 'create_user_id',
            // 'check_user_id',
            [
                'attribute' => 'check_user_id',
                'value'=>function ($model) {
                    return $model->checkUser ? $model->checkUser->username : '-';
                },
            ],

            ['class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return false;
                    },
                    'delete' => function ($url, $model, $key) {
                        return false;
                    },
                ],
                'headerOptions' => ['width' => '80'],
            ]

    ],
    ]); ?>

</div>
