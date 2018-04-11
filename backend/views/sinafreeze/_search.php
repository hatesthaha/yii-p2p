<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SinaFreezeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sina-freeze-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'identity_id') ?>

    <?= $form->field($model, 'account_type') ?>

    <?= $form->field($model, 'out_freeze_no') ?>

    <?php // echo $form->field($model, 'freeze_money') ?>

    <?php // echo $form->field($model, 'freeze_summary') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'msg') ?>

    <?php // echo $form->field($model, 'out_unfreeze_no') ?>

    <?php // echo $form->field($model, 'unfreeze_money') ?>

    <?php // echo $form->field($model, 'unfreeze_summary') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
