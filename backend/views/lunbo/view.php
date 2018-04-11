<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\cms\Lunbo */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Lunbos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lunbo-view">

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
            'title',
            'url:url',
            'status',
            'order',
            'info',
            'content',
//            'create_at',
//            'update_at',
        ],
    ]) ?>

</div>
