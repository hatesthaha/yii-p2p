<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel common\models\base\fund\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '投资记录');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>
    <div class="form-group">
        <div class="col-md-6">
            <label>时间筛选:</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" value="<?= $time; ?>" name="time" id="reservation"/>
            </div><!-- /.input group -->
        </div>
        <div class="col-md-6">
            <label></label>
            <div class="input-group" style="margin-top: 5px">
            <button class="btn btn-warning" id="search">搜索</button>&nbsp;
            <?= Html::a(Yii::t('app', '导出'), ['export'], ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
<div class="col-md-12">
<div class="order-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            [
                'attribute' => 'member_id',
                'value'=>function ($model) {
                    return $model->member ? $model->member->username : '-';
                },
            ],
            [
                'attribute' => 'product_id',
                'value'=>function ($model) {
                    return $model->product ? $model->product->title : '-';
                },
            ],
            'start_money',
            //'status',
            // 'start_at',
            // 'end_at',
            [
                'attribute' => 'start_at',
                'format' => ['date', 'php:Y-m-d H:i:s']
            ],
            // 'update_at',


        ],
    ]); ?>
</div>
</div>
