<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\invation\AssetConfig */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="asset-config-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'deposit_num')->textInput() ?>

    <?= $form->field($model, 'deposit_min')->textInput() ?>

    <?= $form->field($model, 'deposit_max')->textInput() ?>

    <?= $form->field($model, 'deposit_time')->textInput() ?>

    <?= $form->field($model, 'invest_num')->textInput() ?>

    <?= $form->field($model, 'invest_min')->textInput() ?>

    <?= $form->field($model, 'invest_max')->textInput() ?>

    <?= $form->field($model, 'invest_time')->textInput() ?>

    <?= $form->field($model, 'ransom_num')->textInput() ?>

    <?= $form->field($model, 'ransom_min')->textInput() ?>

    <?= $form->field($model, 'ransom_max')->textInput() ?>

    <?= $form->field($model, 'ransom_time')->textInput() ?>

    <?= $form->field($model, 'withdraw_num')->textInput() ?>

    <?= $form->field($model, 'withdraw_min')->textInput() ?>

    <?= $form->field($model, 'withdraw_max')->textInput() ?>

    <?= $form->field($model, 'withdraw_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
