<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\cms\LunboSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '轮播图';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lunbo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建轮播图', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            'url:url',
            'order',
            'info',
            'event_link',
            'share_link',
            [
                'attribute'=>'type',
                'format' => 'html',
                'value'=>function ($model) {
                    if ($model->type == \common\models\cms\Lunbo::TYPE_LUNBO) {
                        $class = 'label-success';
                    } elseif ($model->status == \common\models\cms\Lunbo::TYPE_QIDONG) {
                        $class = 'label-warning';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->typeLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    \common\models\cms\Lunbo::getArrayType(),
                    ['class' => 'form-control', 'prompt' => '请筛选']
                )
            ],
            [
                'attribute'=>'status',
                'format' => 'html',
                'value'=>function ($model) {
                    if ($model->status == \common\models\cms\Lunbo::STATUS_SUCCESS) {
                        $class = 'label-success';
                    } elseif ($model->status == \common\models\cms\Lunbo::STATUS_DELETED) {
                        $class = 'label-warning';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->statusLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    \common\models\cms\Lunbo::getArrayStatus(),
                    ['class' => 'form-control', 'prompt' => '请筛选']
                )
            ],
            ['class' => 'yii\grid\ActionColumn'],

        ],
    ]); ?>

</div>
