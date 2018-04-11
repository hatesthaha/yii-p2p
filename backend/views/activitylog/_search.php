<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\base\activity\ActivityLogQuery */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="activity-log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'invite_id') ?>

    <?= $form->field($model, 'invite_phone') ?>

    <?= $form->field($model, 'experience_money') ?>

    <?php // echo $form->field($model, 'red_packet') ?>

    <?php // echo $form->field($model, 'actibity_source') ?>

    <?php // echo $form->field($model, 'inviter_draw') ?>

    <?php // echo $form->field($model, 'invitee_draw') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <?php // echo $form->field($model, 'end_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
