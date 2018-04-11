<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\base\activity\Code;
/* @var $this yii\web\View */
/* @var $searchModel common\models\base\activity\CodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '兑换码');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="code-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', '创建'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', '批量生成兑换码'), ['import'], ['class' => 'btn btn-info']) ?>
    </p>
    <p>

    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'validity_time',
            [
                'attribute' => 'rate',
                'value' => function ($model) {
                    return $model->rate*100;
                }
            ],
            [
                'attribute' => 'use_end_time',
                'format' => ['date', 'php:Y-m-d']
            ],
            // 'created_at',
            // 'updated_at',
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->status === $model::STATUS_USE) {
                        $class = 'label-success';
                    } elseif ($model->status === $model::STATUS_INACTIVE) {
                        $class = 'label-warning';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->statusLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    Code::labels(),
                    ['class' => 'form-control', 'prompt' => Yii::t('app', 'Please Filter')]
                )
            ],
            [
                'attribute' => 'display',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->display === $model::DISPLAY_INACTIVE) {
                        $class = 'label-success';
                    } elseif ($model->display === $model::DISPLAY_USE) {
                        $class = 'label-warning';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->displaysLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'display',
                    Code::displays(),
                    ['class' => 'form-control', 'prompt' => Yii::t('app', 'Please Filter')]
                )
            ],
            // 'display',

            ['class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => '删除',
                            'data'=>[
                                'confirm'=>'你确定要删除吗？',
                                'method'=>'post'
                            ]
                        ] ) ;
                    },
                ]
            ],
        ],
    ]); ?>

</div>
