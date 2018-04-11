<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\base\asset\Info */

$this->title = $model->member_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '会员资金'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="info-view">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'member_id',
            'bank_card',
            'bank_card_phone',
            'balance',
            'freeze',
            'invest',
            'total_invest',
            'profit',
            'profit_freeze',
            'total_revenue',
            [
                'attribute' => 'create_at',
                'format' => ['date', 'php:Y-m-d H:i:s']
            ],
            [
                'attribute' => 'update_at',
                'format' => ['date', 'php:Y-m-d H:i:s']
            ],
        ],
    ]) ?>
    <?php if( $model->sinamoney){ ?>
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>新浪账户余额</th>
            <th><?php echo $model->sinamoney['balance']; ?></th>
        </tr>
        </thead>
    </table>
    <?php } ?>
</div>
