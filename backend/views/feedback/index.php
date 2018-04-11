<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\base\cms\FeedbackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '反馈信息');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'uid',
                'value'=>function ($model) {
                    return $model->user ? $model->user->username : '-';
                },
            ],
            'feedback:ntext',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d']
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:Y-m-d']
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
