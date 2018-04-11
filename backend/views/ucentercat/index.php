<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\base\ucenter\CatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '会员分类');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cat-index">



    <p>
        <?= Html::a(Yii::t('app', '创建'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],
            //'updated_at',
            [
                'attribute' => 'status',
                'format' => 'html',
                'headerOptions' => ['width' => '120'],
                'value' => function ($model) {
                    if ($model->status === $model::STATUS_ACTIVE) {
                        $class = 'label-success';
                    } elseif ($model->status === $model::STATUS_DELETED) {
                        $class = 'label-danger';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->statusLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    common\models\base\ucenter\Cat::labels(),
                    ['class' => 'form-control', 'prompt' => Yii::t('app', 'Please Filter')]
                )
            ],

            ['class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return $model->id == 1 || $model->id ==2  ? "" :
                            Html::a('&nbsp;&nbsp;<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => '修改'] );
                    },
                    'delete' => function ($url, $model, $key) {
                        return $model->id == 1 || $model->id ==2 ? "" :
                            Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
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
