<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SiteSinaBalance */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="site-sina-balance-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput() ?>

    <?= $form->field($model, 'identity_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bank_card')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'site_balance')->textInput() ?>

    <?= $form->field($model, 'sina_available_balance')->textInput() ?>

    <?= $form->field($model, 'user_earnings')->textInput() ?>

    <?= $form->field($model, 'sina_balance')->textInput() ?>

    <?= $form->field($model, 'sina_bonus')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sina_bonus_day')->textInput() ?>

    <?= $form->field($model, 'sina_bonus_month')->textInput() ?>

    <?= $form->field($model, 'sina_bonus_sum')->textInput() ?>

    <?= $form->field($model, 'create_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'update_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
