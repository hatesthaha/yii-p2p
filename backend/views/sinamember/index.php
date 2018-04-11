<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\sinapay\SinaMemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '新浪会员记录');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sina-member-index">



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'identity_id',
            'uid',
            'name',
            'idcard',
            'user_ip',
            'phone',
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
                    \common\models\sinapay\SinaMember::labels(),
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
            ['class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return $model->status == 1 ?
                            Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => '查看新浪余额'] ) : '';;
                    },


                ],
            ],
        ],
    ]); ?>

</div>
