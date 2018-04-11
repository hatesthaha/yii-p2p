<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaConfigSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sina-config-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'sinapay_site_prefix') ?>

    <?= $form->field($model, 'sinapay_version') ?>

    <?= $form->field($model, 'sinapay_partner_id') ?>

    <?= $form->field($model, 'sign_type') ?>

    <?php // echo $form->field($model, 'sinapay_md5_key') ?>

    <?php // echo $form->field($model, 'sinapay_input_charset') ?>

    <?php // echo $form->field($model, 'sinapay_rsa_sign_private_key') ?>

    <?php // echo $form->field($model, 'sinapay_rsa_sign_public_key') ?>

    <?php // echo $form->field($model, 'sinapay_rsa_public__key') ?>

    <?php // echo $form->field($model, 'sinapay_mgs_url') ?>

    <?php // echo $form->field($model, 'sinapay_mas_url') ?>

    <?php // echo $form->field($model, 'sinapay_site_email') ?>

    <?php // echo $form->field($model, 'sinapay_give_accrual') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
