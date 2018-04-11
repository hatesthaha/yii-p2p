<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaDeposit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sina-deposit-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput() ?>

    <?= $form->field($model, 'identity_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'out_trade_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'account_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payer_ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_method')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ticket')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'validate_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'msg')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'update_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
