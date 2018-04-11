<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\base\activity\ActivityLog */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Activity Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-log-view">

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
//            'id',
            'phone',
//            'invite_id',
            'invite_phone',
            'experience_money',
            'red_packet',
            'actibity_source',
            'inviter_draw',
            'invitee_draw',
            'status',
            'create_at',
            'update_at',
            'end_at',
        ],
    ]) ?>

</div>
