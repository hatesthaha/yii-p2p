<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
/* @var $this yii\web\View */
/* @var $model common\models\base\activity\Card */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <div class="form-group field-thirdproduct-start_at required" style="width: 300px">
        <label class="control-label" for="thirdproduct-amount">有效期开始时间</label>
        <?= DatePicker::widget([
            'model' => $model,
            'attribute' => 'use_start_at',
            'language' => 'zh-CN',
            'size' => 'ms',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayBtn' => true
            ]
        ]);?>
    </div>

    <div class="form-group field-thirdproduct-start_at required" style="width: 300px">
        <label class="control-label" for="thirdproduct-amount">有效期结束时间</label>
        <?= DatePicker::widget([
            'model' => $model,
            'attribute' => 'use_out_at',
            'language' => 'zh-CN',
            'size' => 'ms',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayBtn' => true
            ]
        ]);?>
    </div>


    <?= $form->field($model, 'rate')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
