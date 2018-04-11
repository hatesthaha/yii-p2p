<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\base\activity\HoldActivityQuery */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '创建活动';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hold-activity-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建活动', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'activity_name',
            [
                'attribute' => 'activity_begin',
                'format' => ['date', 'php:Y-m-d : H:m:s']
            ],
            [
                'attribute' => 'activity_end',
                'format' => ['date', 'php:Y-m-d : H:m:s']
            ],
            'gold_money',
             'activity_rate',
             'gold_day',
            [
                'attribute'=>'red_bothway',
                'format' => 'html',
                'value'=>function ($model) {
                    if ($model->red_bothway == common\models\base\activity\HoldActivity::RED_BOTHWAY_YES ) {
                        $class = 'label-success';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->statusbothway . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    common\models\base\activity\HoldActivity::statusbothwayLabel(),
                    ['class' => 'form-control', 'prompt' => '请筛选']
                )
            ],
            [
                'attribute'=>'status',
                'format' => 'html',
                'value'=>function ($model) {
                    if ($model->status == common\models\base\activity\HoldActivity::STATUS_OPEN ) {
                        $class = 'label-success';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->statusLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    common\models\base\activity\HoldActivity::statusLabel(),
                    ['class' => 'form-control', 'prompt' => '请筛选']
                )
            ],
//            [
//                'attribute' => 'create_at',
//                'format' => ['date', 'php:Y-m-d']
//            ],
//            [
//                'attribute' => 'update_at',
//                'format' => ['date', 'php:Y-m-d']
//            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
