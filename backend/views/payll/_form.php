<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\lianlian\payLL */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pay-ll-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput() ?>

    <?= $form->field($model, 'idcard')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'real_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'busi_partne')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'no_order')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_goods')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'money_order')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'card_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'from_ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bank_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sign_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sign')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'oid_paybill')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'money_lianlian')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'settle_date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'update_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
