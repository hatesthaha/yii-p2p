<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\base\fund\product */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '项目'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'intro',
            'amount',
            'start_at',
            'end_at',
            'rate',
            'invest_people',
            'invest_sum',
            'each_max',
            'each_min',
            'create_at',
            'update_at'

        ],
    ]) ?>

</div>
