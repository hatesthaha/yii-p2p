<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\base\cms\Link;
/* @var $this yii\web\View */
/* @var $model common\models\base\cms\Link */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '网站信息'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="link-view">


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
            'cat_id',
            'intro',
            'bannar',
            'link',
            [
                'attribute' => 'status',
                'value' => $model->statusLabel,
            ],
        ],
    ]) ?>

</div>
