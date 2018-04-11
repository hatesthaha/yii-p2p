<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\base\activity\HoldActivityQuery */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hold-activity-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'activity_name') ?>

    <?= $form->field($model, 'activity_begin') ?>

    <?= $form->field($model, 'activity_end') ?>

    <?= $form->field($model, 'gold_money') ?>

    <?php // echo $form->field($model, 'gold_rate') ?>

    <?php // echo $form->field($model, 'gold_day') ?>

    <?php // echo $form->field($model, 'red_bothway') ?>

    <?php // echo $form->field($model, 'red_money_rang') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
