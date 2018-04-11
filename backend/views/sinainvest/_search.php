<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaInvestSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sina-invest-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'identity_id') ?>

    <?= $form->field($model, 'out_trade_no') ?>

    <?= $form->field($model, 'summary') ?>

    <?php // echo $form->field($model, 'trade_close_time') ?>

    <?php // echo $form->field($model, 'payer_ip') ?>

    <?php // echo $form->field($model, 'pay_type') ?>

    <?php // echo $form->field($model, 'account_type') ?>

    <?php // echo $form->field($model, 'goods_id') ?>

    <?php // echo $form->field($model, 'money') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'msg') ?>

    <?php // echo $form->field($model, 'payee_out_trade_no') ?>

    <?php // echo $form->field($model, 'payee_identity_id') ?>

    <?php // echo $form->field($model, 'payee_account_type') ?>

    <?php // echo $form->field($model, 'payee_amount') ?>

    <?php // echo $form->field($model, 'payee_summary') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
