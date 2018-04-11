<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\base\experience\RuleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '体验金');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rule-index">
    <p>
        <?= Html::a('创建', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            'title',
            'money',

            'time',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d']
            ],
            // 'updated_at',
            [
                'attribute'=>'status',
                'format' => 'html',
                'value'=>function ($model) {
                    if ($model->status ===\common\models\base\experience\Rule::STATUS_ACTIVE) {
                        $class = 'label-success';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->statusLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    \common\models\base\experience\Rule::labels(),
                    ['class' => 'form-control', 'prompt' => '请筛选']
                )
            ],

            ['class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'buttons' => [
                'delete' => function ($url, $model, $key) {
                    return '';
                },
                'view' => function ($url, $model, $key) {
                    return '';
                },
            ]
            ],
        ],
    ]); ?>

</div>
