<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Article;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文章管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'user_id',
                'value'=>function ($model) {
                    return $model->user ? $model->user->username : '-';
                },
            ],
            [
                'attribute'=>'category_id',
                'value'=>function ($model) {
                    return $model->category->title;
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'category_id',
                    \backend\models\Article::getArrayCategory(),
                    ['class' => 'form-control', 'prompt' => '请筛选']
                )
            ],
            'title',
            //'intro',
            // 'content:ntext',
            // 'create_at',
            // 'update_at',
            [
                'attribute'=>'status',
                'format' => 'html',
                'value'=>function ($model) {
                    if ($model->status ===Article::STATUS_ACTIVE) {
                        $class = 'label-success';
                    } elseif ($model->status === Article::STATUS_INACTIVE) {
                        $class = 'label-warning';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->statusLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    Article::getArrayStatus(),
                    ['class' => 'form-control', 'prompt' => '请筛选']
                )
            ],
            ['class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{preview}{view}{update}{delete}{send}{unsend}',
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
                    'send' => function ($url, $model, $key) {
                        return $model->status == Article::STATUS_INACTIVE ?
                            Html::a('&nbsp;&nbsp;<span class="glyphicon glyphicon-send"></span>', $url, ['title' => '发布'] ) : '';
                    },
                    'unsend' => function ($url, $model, $key) {
                        return $model->status == Article::STATUS_ACTIVE ?
                            Html::a('&nbsp;&nbsp;<span class="glyphicon glyphicon-comment"></span>', $url, ['title' => '取消发布'] ) : '';
                    },

                ],
            ],
        ],
    ]); ?>

</div>
