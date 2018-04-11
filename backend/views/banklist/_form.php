<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\base\setting\BankList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bank-list-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'bank_code')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'service_tel')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'bank_logo')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'card_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'card_attribute')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'binding_pay_1time_limit')->textInput() ?>

    <?= $form->field($model, 'binding_pay_time_limit')->textInput() ?>

    <?= $form->field($model, 'binding_pay_day_limit')->textInput() ?>

    <?= $form->field($model, 'binding_pay_time_min_limit')->textInput() ?>

    <?= $form->field($model, 'is_valid')->dropDownList($model->isValidLables()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
