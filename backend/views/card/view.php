<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\base\activity\Card */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '增息卡'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-view">


    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'attribute' => 'use_start_at',
                'format' => ['date', 'php:Y-m-d']
            ],
            [
                'attribute' => 'use_out_at',
                'format' => ['date', 'php:Y-m-d']
            ],
            'validity_time',
            'rate',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d']
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:Y-m-d']
            ],

        ],
    ]) ?>

</div>
