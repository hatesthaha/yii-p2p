<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\base\cms\LinkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '网站信息');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="link-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', '创建'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            'cat_id',
            'intro',
            'bannar',
            'link',
            // 'created_at',
            // 'updated_at',
            [
                'attribute'=>'status',
                'format' => 'html',
                'value'=>function ($model) {
                    if ($model->status ===\common\models\base\cms\Link::STATUS_ACTIVE) {
                        $class = 'label-success';
                    } elseif ($model->status === \common\models\base\cms\Link::STATUS_INACTIVE) {
                        $class = 'label-warning';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->statusLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    \common\models\base\cms\Link::getArrayStatus(),
                    ['class' => 'form-control', 'prompt' => '请筛选']
                )
            ],

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
