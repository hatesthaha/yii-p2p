<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\base\activity\RaiseCard;
/* @var $this yii\web\View */
/* @var $model common\models\base\activity\RaiseCard */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '增值卡'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="raise-card-view">



    <p>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'member_id',
//            'fund_order_id',
//            'validity_start_at',
//            'validity_out_at',
            [
                'attribute' => 'validity_start_at',
                'format' =>  ['date', 'php:Y-m-d H:i:s'],
            ],
            [
                'attribute' => 'validity_out_at',
                'format' =>  ['date', 'php:Y-m-d H:i:s'],
            ],
            'rate',
//            [
//                'attribute' => 'status',
//                'value' => $model->statusLabel,
//            ],

        ],
    ]) ?>

</div>
