<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaFreeze */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sina Freezes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sina-freeze-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
            'uid',
            'identity_id',
            'account_type',
            'out_freeze_no',
            'freeze_money',
            'freeze_summary',
            'status',
            'msg',
            'out_unfreeze_no',
            'unfreeze_money',
            'unfreeze_summary',
            'create_at',
            'update_at',
        ],
    ]) ?>

</div>
