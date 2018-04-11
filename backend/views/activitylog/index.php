<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\base\activity\ActivityLogQuery */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '活动红包记录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-log-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'phone',
//            'invite_id',
            'invite_phone',
//            'experience_money',
             'red_packet',
             'actibity_source',
            [
                'attribute'=>'inviter_draw',
                'format' => 'html',
                'value'=>function ($model) {
                    if ($model->inviter_draw == common\models\base\activity\ActivityLog::STATUS_INVITER_DRAW_SUCC ) {
                        $class = 'label-success';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->inviterLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'inviter_draw',
                    \common\models\base\activity\ActivityLog::inviterLabel(),
                    ['class' => 'form-control', 'prompt' => '请筛选']
                )
            ],
            [
                'attribute'=>'invitee_draw',
                'format' => 'html',
                'value'=>function ($model) {
                    if ($model->invitee_draw == common\models\base\activity\ActivityLog::STATUS_INVITEE_DRAW_SUCC ) {
                        $class = 'label-success';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->inviteeLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'invitee_draw',
                    \common\models\base\activity\ActivityLog::inviteeLabel(),
                    ['class' => 'form-control', 'prompt' => '请筛选']
                )
            ],
            [
                'attribute' => 'create_at',
                'format' => ['date', 'php:Y-m-d']
            ],
            [
                'attribute' => 'end_at',
                'format' => ['date', 'php:Y-m-d']
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
