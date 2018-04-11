<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\fund;
/* @var $this yii\web\View */
/* @var $model common\models\base\fund\Thirdproduct */

$web = \App::getAlias('@web') . '/upload/'.$model->contract;
$webs = \App::getAlias('@web') . '/upload/'.$model->intentcontract;
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' =>"第三方债权", 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thirdproduct-view">


    <p><a href="<?=$web ?>" target="_blank">合同查看</a></p>
    <p><a href="<?=$webs ?>" target="_blank">意向合同查看</a></p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'intro',
            'source',
            'creditor',
            'realname',
            'remarks',
            'amount',
            'start_at',
            'end_at',
            'rate',
            'create_at',
            'update_at',
            [
                'attribute' => 'status',
                'value' => $model->statusLabel,
            ],
            'reject',

        ],
        'template' => '<tr><th>{label}</th><td>{value}</td></tr>',
    ]) ?>

</div>
