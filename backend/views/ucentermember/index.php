<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\UcenterMember;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel common\models\UcenterMemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '会员用户');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ucenter-member-index">
    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group">
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

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
//            'auth_key',
//            'password_hash',
//            'password_reset_token',
             'phone',
             'email:email',
             'idcard',
             'real_name',
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->status === $model::STATUS_ACTIVE) {
                        $class = 'label-success';
                    } elseif($model->status === $model::STATUS_REAL){
                        $class = 'label-success';
                    } elseif($model->status === $model::STATUS_BIND){
                        $class = 'label-success';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->statusLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    common\models\UcenterMember::getArrayStatus(),
                    ['class' => 'form-control', 'prompt' => Yii::t('app', 'Please Filter')]
                ),
            ],
            [
                'attribute' => 'lock',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->lock === $model::TYPE_UNLOCK) {
                        $class = 'label-success';
                    }elseif ($model->lock === $model::TYPE_LOCK) {
                        $class = 'label-warning';
                    } else {
                        $class = 'label-danger';
                    }

                    return '<span class="label ' . $class . '">' . $model->typeLabel . '</span>';
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'lock',
                    common\models\UcenterMember::getTypeStatus(),
                    ['class' => 'form-control', 'prompt' => Yii::t('app', 'Please Filter')]
                ),
            ],
//            [
//                'attribute' => 'type',
//                'format' => 'html',
//                'value' => function ($model) {
//                    if ($model->type === $model::CUS_MAXCRE) {
//                        $class = 'label-success';
//                    }elseif ($model->type === $model::CUS_CRE) {
//                        $class = 'label-warning';
//                    } else{
//                        $class = 'label-danger';
//                    }
//
//                    return '<span class="label ' . $class . '">' . $model->cusLabel . '</span>';
//                },
//                'filter' => Html::activeDropDownList(
//                    $searchModel,
//                    'type',
//                    common\models\UcenterMember::getCusStatus(),
//                    ['class' => 'form-control', 'prompt' => Yii::t('app', 'Please Filter')]
//                ),
//            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d H:i:s']
            ],
            // 'updated_at',
            // 'create_ip',
            // 'create_area',
            // 'create_channel',
            // 'login_ip',
            // 'login_area',
            // 'error_num',
            // 'parent_member_id',
            // 'vip',

            ['class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => '删除',
                            'data'=>[
                                'confirm'=>'你确定要删除吗？',
                                'method'=>'post'
                            ]
                        ] ) ;
                    },
                ]
            ],
        ],
    ]); ?>

</div>
