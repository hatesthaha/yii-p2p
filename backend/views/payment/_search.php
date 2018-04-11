<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\yeepay\PaymentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'orderid') ?>

    <?= $form->field($model, 'transtime') ?>

    <?= $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'userip') ?>

    <?php // echo $form->field($model, 'productname') ?>

    <?php // echo $form->field($model, 'identityid') ?>

    <?php // echo $form->field($model, 'orderexpdate') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'sendtime') ?>

    <?php // echo $form->field($model, 'yborderid') ?>

    <?php // echo $form->field($model, 'ybamount') ?>

    <?php // echo $form->field($model, 'msg') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
