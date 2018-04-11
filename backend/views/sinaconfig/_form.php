<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaConfig */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sina-config-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sinapay_site_prefix')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sinapay_version')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sinapay_partner_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sign_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sinapay_md5_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sinapay_input_charset')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sinapay_rsa_sign_private_key')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sinapay_rsa_sign_public_key')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sinapay_rsa_public__key')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sinapay_mgs_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sinapay_mas_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sinapay_site_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sinapay_give_accrual')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
