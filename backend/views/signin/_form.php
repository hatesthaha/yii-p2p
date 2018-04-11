<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\post\SignIn */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sign-in-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput() ?>

    <?= $form->field($model, 'sign_in_time')->textInput() ?>

    <?= $form->field($model, 'sign_in_money')->textInput() ?>

    <?= $form->field($model, 'sign_in_ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sign_in_from')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'update_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
