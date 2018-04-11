<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\sinapay\SinaBankSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '新浪绑卡记录');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sina-bank-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'uid',
            'identity_id',
            //'request_no',
            'bank_code',
            'bank_name',
            'bank_account_no',
            // 'card_type',
            // 'card_attribute',
            'phone_no',
            'province',
            'city',
            // 'bank_branch',
            // 'ticket',
            // 'valid_code',
            // 'card_id',
            [
                'attribute'=>'status',
                'format' => 'html',
                'value'=>function ($model) {
                    if ($model->status ===2) {
                        $class = 'label-success';
                    } elseif ($model->status === 1) {
                        $class = 'label-warning';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->statusLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    \common\models\sinapay\SinaBank::labels(),
                    ['class' => 'form-control', 'prompt' => '请筛选']
                )
            ],
            'msg',
            [
                'attribute' => 'create_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
                'headerOptions' => ['width' => '100'],
            ],
            // 'update_at',

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
