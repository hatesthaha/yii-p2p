<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\base\fund\Product;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\base\fund\productSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '发布项目');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            'title',
            'amount',
            [
            'attribute' => 'start_at',
			'format' => ['date', 'php:Y-m-d H:i:s'],
            'headerOptions' => ['width' => '100'],
            ],
            [
                'attribute' => 'end_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
                'headerOptions' => ['width' => '100'],
            ],
            // 'rate',
            'invest_people',
            'invest_sum',
            'virtual_amonnt',
            'virtual_invest_people',
            // 'each_min',
            [
                'attribute' => 'create_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
                'headerOptions' => ['width' => '100'],
            ],
            // 'create_at',
//             'update_at',
            [
                'attribute' => 'update_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
                'headerOptions' => ['width' => '100'],
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'headerOptions' => ['width' => '120'],
                'value' => function ($model) {
                    if ($model->status === $model::STATUS_UNLOCK) {
                        $class = 'label-success';
                    } elseif ($model->status === $model::STATUS_LOCK) {
                        $class = 'label-warning';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->statusLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    Product::getArrayStatus(),
                    ['class' => 'form-control', 'prompt' => Yii::t('app', 'Please Filter')]
                )
            ],
            [
                'attribute' => 'type',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->type === $model::TYPE_PRO) {
                        return '<span class="label label-success">' . $model->typeLabel . '</span>';
                    } elseif ($model->type === $model::TYPE_THIRD) {
                        return  '<span class="label label-warning">'.$model->typeLabel.'</span>';
                    } else {
                        $class = 'label-danger';
                    }
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'type',
                    Product::getArrayTypes(),
                    ['class' => 'form-control', 'prompt' => Yii::t('app', 'Please Filter')]
                )
            ],
            [
                'attribute' => 'create_user_id',
                'value'=>function ($model) {
                    return $model->createUser ? $model->createUser->username : '-';
                },
            ],
            [
                'attribute' => 'ocreditor',
                'value'=>function ($model) {
                    return $model->ocreditor ? $model->typeocUser->username : '-';
                },
            ],
            [
                'attribute' => 'maxcreditor',
                'value'=>function ($model) {
                    return $model->maxcreditor ? $model->typemaxUser->username : '-';
                },
                'headerOptions' => ['width' => '100'],
            ],
            // 'create_user_id',
            // 'check_user_id',
             ['class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                 'template' => '{view}{lock}{unlock}{update}{delete}{list}{thirdlist}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return $model->start_at > strtotime("now") ?
                            Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => '编辑'] ) : '';;
                    },
                    'delete' => function ($url, $model, $key) {
                        return $model->start_at > strtotime("now") ?
                            Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,  [
                                'title' => '删除',
                                'data'=>[
                                    'confirm'=>'你确定要删除吗？',
                                    'method'=>'post'
                                ]
                            ]  ) : '';
                    },
                    'lock' => function ($url, $model, $key) {
                        return $model->start_at < strtotime("now") && $model->status != Product::STATUS_LOCK ?
                            Html::a('&nbsp;&nbsp;<span class="glyphicon glyphicon-lock"></span>', $url, ['title' => '锁定'] ) : '';
                    },
                    'unlock' => function ($url, $model, $key) {
                        return $model->status == Product::STATUS_LOCK ?
                            Html::a('&nbsp;&nbsp;<span class="glyphicon glyphicon-magnet"></span>', $url, ['title' => '解锁'] ) : '';
                    },
                    'list' => function ($url, $model, $key) {
                        return $model->start_at < strtotime("now") ?
                            Html::a('&nbsp;&nbsp;<span class="glyphicon glyphicon-align-justify"></span>', $url, ['title' => '投资列表'] ) : '';
                    },
                    'thirdlist' => function ($url, $model, $key) {
                        return $model->type == Product::TYPE_THIRD ?
                            Html::a('&nbsp;&nbsp;<span class="glyphicon glyphicon-user"></span>', $url,['title'=>'债权人列表']) : '';
                    },
                ],
            ],
            
        ],
    ]); ?>

</div>
