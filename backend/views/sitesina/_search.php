<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\sinapay\SiteSinaBalanceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="site-sina-balance-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'identity_id') ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'user_name') ?>

    <?php // echo $form->field($model, 'bank_card') ?>

    <?php // echo $form->field($model, 'site_balance') ?>

    <?php // echo $form->field($model, 'sina_available_balance') ?>

    <?php // echo $form->field($model, 'user_earnings') ?>

    <?php // echo $form->field($model, 'sina_balance') ?>

    <?php // echo $form->field($model, 'sina_bonus') ?>

    <?php // echo $form->field($model, 'sina_bonus_day') ?>

    <?php // echo $form->field($model, 'sina_bonus_month') ?>

    <?php // echo $form->field($model, 'sina_bonus_sum') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'msg') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
