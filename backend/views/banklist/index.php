<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\base\setting\BankListQuery */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '银行限制列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-list-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'bank_name',
            'binding_pay_1time_limit',
            'binding_pay_time_limit',
            'binding_pay_day_limit',
            'binding_pay_time_min_limit',
            'is_valid',
            [
                'attribute' => 'is_valid',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->is_valid == \common\models\base\setting\BankList::IS_VALID_TRUE) {
                        $class = 'label-success';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->is_valid . '</span>';
                },
            ],
            // 'is_delete',
            // 'create_at',
            // 'update_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
