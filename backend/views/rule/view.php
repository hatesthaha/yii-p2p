<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\base\experience\Rule */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '体验金'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rule-view">




    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'money',
            'time',


            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d']
            ],


//            'updated_at',
            [
                'attribute' => 'status',
                'value' => $model->statusLabel,
            ],
        ],
    ]) ?>

</div>
