<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datetimepicker\DateTimePicker;
/* @var $this yii\web\View */
/* @var $model common\models\base\activity\HoldActivity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hold-activity-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'activity_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group field-thirdproduct-start_at required" style="width: 300px">
        <label class="control-label" for="thirdproduct-amount">起始时间</label>
        <?= DateTimePicker::widget([
            'model' => $model,
            'attribute' => 'activity_begin',
            'language' => 'zh-CN',
            'size' => 'ms',
            'clientOptions' => [
                'autoclose' => true,
                'linkFormat' => 'yyyy-mm-dd  HH:ii',
                'todayBtn' => true
            ]
        ]);?>
    </div>

    <div class="form-group field-thirdproduct-start_at required" style="width: 300px">
        <label class="control-label" for="thirdproduct-amount">结束时间</label>
        <?= DateTimePicker::widget([
            'model' => $model,
            'attribute' => 'activity_end',
            'language' => 'zh-CN',
            'size' => 'ms',
            'clientOptions' => [
                'autoclose' => true,
                'linkFormat' => 'yyyy-mm-dd  HH:ii',
                'todayBtn' => true
            ]
        ]);?>
    </div>


    <?= $form->field($model, 'gold_money')->textInput() ?>

    <?= $form->field($model, 'activity_rate')->textInput() ?>

    <?= $form->field($model, 'gold_day')->textInput() ?>

    <?= $form->field($model, 'rid_list')->textInput() ?>


    <label class="control-label" for="thirdproduct-amount">示例：1-2,20/2-3,30/3-4,40/4-5,50（起始金额-终止金额,出现的概率/起始金额-终止金额,出现的概率）</label>
    <?= $form->field($model, 'red_money_rang')->textarea(['rows' => 6]) ?>


    <?= $form->field($model, 'red_bothway')->dropDownList(common\models\base\activity\HoldActivity::statusbothwayLabel()) ?>

    <?= $form->field($model, 'status')->dropDownList(common\models\base\activity\HoldActivity::statusLabel()) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
