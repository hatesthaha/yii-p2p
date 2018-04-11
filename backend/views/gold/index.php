<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\base\experience\Gold;

/* @var $this yii\web\View */
/* @var $searchModel common\models\base\experience\GoldSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '体验金记录');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gold-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', '指定会员发体验金'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'id',
            [
                'attribute' => 'uid',
                'value'=>function ($model) {
                    return $model->user ? $model->user->username : '-';
                },
            ],
            [
                'attribute' => 'rid',
                'value'=>function ($model) {
                    return $model->rule ? $model->rule->title : '-';
                },
            ],
            'money',

            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
                'headerOptions' => ['width' => '100'],
            ],
            // 'updated_at',
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
//                    Gold::labels(),
//                    ['class' => 'form-control', 'prompt' => Yii::t('app', 'Please Filter')]
//                )
//            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{delete}',
                'buttons' => [

                    'change' => function ($url, $model, $key) {
                        return $model->status == '1'? Html::a('<span class="glyphicon glyphicon-list-alt"></span>', $url, ['title' => '删除'] ):'';
                    },

                ],
                'headerOptions' => ['width' => '80'],
            ],

        ],
    ]); ?>

</div>
