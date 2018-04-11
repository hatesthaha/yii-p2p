<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\base\activity\HoldActivity */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Hold Activities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hold-activity-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
            'red_bothway',
            'red_money_rang',
            'status',
//            'create_at',
//            'update_at',
        ],
    ]) ?>

</div>
