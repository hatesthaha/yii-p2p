<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\base\activity\RaiseCardSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="raise-card-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'member_id') ?>

    <?= $form->field($model, 'fund_order_id') ?>

    <?= $form->field($model, 'use_start_at') ?>

    <?= $form->field($model, 'use_at') ?>

    <?php // echo $form->field($model, 'use_out_at') ?>

    <?php // echo $form->field($model, 'validity_time') ?>

    <?php // echo $form->field($model, 'rate') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
