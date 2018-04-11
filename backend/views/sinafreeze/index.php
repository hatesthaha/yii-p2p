<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\sinapay\SinaFreezeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '新浪冻结解冻记录');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sina-freeze-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'uid',
            'identity_id',
            'account_type',
            'out_freeze_no',
            'freeze_money',
            'freeze_summary',
            [
                'attribute'=>'status',
                'format' => 'html',
                'headerOptions' => ['width' => '100'],
                'value'=>function ($model) {
                    if ($model->status ===2) {
                        $class = 'label-success';
                    } elseif ($model->status === 1) {
                        $class = 'label-success';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->statusLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    \common\models\sinapay\SinaFreeze::labels(),
                    ['class' => 'form-control', 'prompt' => '请筛选']
                )
            ],
            'msg',
            // 'out_unfreeze_no',
            'unfreeze_money',
            'unfreeze_summary',
            [
                'attribute' => 'create_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
                'headerOptions' => ['width' => '100'],
            ],
            // 'update_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{unlock}',
                'buttons' => [

                    'unlock' => function ($url, $model, $key) {
                        return $model->status == \common\models\sinapay\SinaFreeze::STATUS_FREEZE? Html::a('<span class="glyphicon glyphicon-list-alt"></span>', $url, ['title' => '解冻资金'] ):"";
                    },

                ],
                'headerOptions' => ['width' => '80'],
            ],
        ],
    ]); ?>

</div>
