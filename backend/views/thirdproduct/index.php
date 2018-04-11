<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\base\fund\Thirdproduct;
/* @var $this yii\web\View */
/* @var $searchModel common\models\base\fund\ThirdproductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '第三方债权');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thirdproduct-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', '创建已购债权审核'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', '创建意向债权'), ['createyi'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'title',

            [
                'attribute'=>'status',
                'format' => 'html',
                'value'=>function ($model) {
                    if ($model->status ===1) {
                        $class = 'label-success';
                    } elseif ($model->status === 0) {
                        $class = 'label-warning';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->statusLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    \common\models\fund\Thirdproduct::labels(),
                    ['class' => 'form-control', 'prompt' => '请筛选']
                )
            ],
            [
                'attribute'=>'intent',
                'format' => 'html',
                'value'=>function ($model) {
                    if ($model->intent ===1) {
                        $class = 'label-success';
                    } elseif ($model->intent === 2) {
                        $class = 'label-warning';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->intentLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'intent',
                    \common\models\fund\Thirdproduct::yilabels(),
                    ['class' => 'form-control', 'prompt' => '请筛选']
                )
            ],
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
            // 'amount',
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

            ['class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}{update}{delete}{list}{change}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return $model->status == 0 || $model->status ==3  ?
                            Html::a('&nbsp;&nbsp;<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => '修改'] ) : '';
                    },
                    'delete' => function ($url, $model, $key) {
                        return $model->status == 0 || $model->status ==3 ?
                            Html::a('&nbsp;&nbsp;<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => '删除',
                                'data'=>[
                                    'confirm'=>'你确定要删除吗？',
                                    'method'=>'post'
                                ]
                            ] ) : '';
                    },
                    'change' => function ($url, $model, $key) {
                        return $model->intent == 1 && $model->status != 3 ?
                            Html::a('&nbsp;&nbsp;<span class="glyphicon glyphicon-send"></span>', $url, ['title' => '转为可购债权'] ) : '';
                    },
                ],
            ],
        ],
    ]); ?>

</div>
