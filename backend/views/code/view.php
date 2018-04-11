<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\base\activity\Code;
/* @var $this yii\web\View */
/* @var $model common\models\base\activity\Code */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '兑换码'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="code-view">


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
            'name',
            'validity_time',

            'rate',
            [
                'attribute' => 'use_at',
                'format' => ['date', 'php:Y-m-d']
            ],
            [
                'attribute' => 'use_end_time',
                'format' => ['date', 'php:Y-m-d']
            ],
            [
                'attribute' => 'status',
                'value' => $model->statusLabel,
            ],

        ],
    ]) ?>

</div>
