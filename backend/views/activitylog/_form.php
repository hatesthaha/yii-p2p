<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\base\activity\ActivityLog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="activity-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invite_id')->textInput() ?>

    <?= $form->field($model, 'invite_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'experience_money')->textInput() ?>

    <?= $form->field($model, 'red_packet')->textInput() ?>

    <?= $form->field($model, 'actibity_source')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inviter_draw')->dropDownList(common\models\base\activity\ActivityLog::inviterLabel()) ?>
    <?= $form->field($model, 'invitee_draw')->dropDownList(common\models\base\activity\ActivityLog::inviteeLabel()) ?>
    <?= $form->field($model, 'status')->textInput() ?>


    <?= $form->field($model, 'end_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
