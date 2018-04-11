<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaDepositSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sina-deposit-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'identity_id') ?>

    <?= $form->field($model, 'out_trade_no') ?>

    <?= $form->field($model, 'account_type') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'payer_ip') ?>

    <?php // echo $form->field($model, 'pay_method') ?>

    <?php // echo $form->field($model, 'ticket') ?>

    <?php // echo $form->field($model, 'validate_code') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'msg') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
