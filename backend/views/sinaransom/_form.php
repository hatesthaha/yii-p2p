<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaRansom */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sina-ransom-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput() ?>

    <?= $form->field($model, 'identity_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'out_trade_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'summary')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'trade_close_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payer_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payer_ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_method')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'money_sina')->textInput() ?>

    <?= $form->field($model, 'payee_out_trade_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'msg')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'update_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
