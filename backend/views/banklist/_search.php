<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\base\setting\BankListQuery */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bank-list-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'bank_code') ?>

    <?= $form->field($model, 'card_type') ?>

    <?= $form->field($model, 'card_attribute') ?>

    <?= $form->field($model, 'binding_pay_1time_limit') ?>

    <?php // echo $form->field($model, 'binding_pay_time_limit') ?>

    <?php // echo $form->field($model, 'binding_pay_day_limit') ?>

    <?php // echo $form->field($model, 'binding_pay_time_min_limit') ?>

    <?php // echo $form->field($model, 'is_valid') ?>

    <?php // echo $form->field($model, 'is_delete') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
