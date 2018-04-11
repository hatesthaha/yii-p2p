<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\yeepay\Withdraw */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="withdraw-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput() ?>

    <?= $form->field($model, 'identityid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'identitytype')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'card_top')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'card_last')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'userip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ybdrawflowid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'msg')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'update_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
