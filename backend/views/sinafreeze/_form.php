<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaFreeze */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sina-freeze-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput() ?>

    <?= $form->field($model, 'identity_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'account_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'out_freeze_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'freeze_money')->textInput() ?>

    <?= $form->field($model, 'freeze_summary')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'msg')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'out_unfreeze_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'unfreeze_money')->textInput() ?>

    <?= $form->field($model, 'unfreeze_summary')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'update_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
