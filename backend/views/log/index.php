<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\models\base\asset\Log;
/* @var $this yii\web\View */
/* @var $searchModel common\models\base\asset\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '资金情况记录');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row" style="margin-bottom: 20px">
<?php $form = ActiveForm::begin(); ?>
<div class="form-group" >
    <div class="col-md-6">
        <label>时间筛选:</label>
        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input type="text" class="form-control pull-right" value="<?= date('Y-m-d 至 Y-m-d',time()) ?>" name="time" id="reservation"/>
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
</div>
<div class="row">
<div class="log-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'member_id',
                'value'=>function ($model) {
                    return $model->member ? $model->member->username : '-';
                },
            ],
            'step',
            'action',
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->status === $model::STATUS_WITHDRAW_SUC) {
                        $class = 'label-success';
                    } elseif($model->status === $model::STATUS_INVEST_SUC){
                        $class = 'label-success';
                    } elseif($model->status === $model::STATUS_REDEM_SUC){
                        $class = 'label-success';
                    }elseif($model->status === $model::STATUS_RECHAR_SUC){
                        $class = 'label-success';
                    }else{
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->statusLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    Log::getArrayStatus(),
                    ['class' => 'form-control', 'prompt' => Yii::t('app', 'Please Filter')]
                ),
            ],
             'bankcard',
             'remark',
            [
                'attribute' => 'create_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],
            [
                'attribute' => 'update_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],



        ],
    ]); ?>
</div>
</div>
