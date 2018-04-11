<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\lianlian\payLLSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pay-ll-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'idcard') ?>

    <?= $form->field($model, 'real_name') ?>

    <?= $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'busi_partne') ?>

    <?php // echo $form->field($model, 'no_order') ?>

    <?php // echo $form->field($model, 'name_goods') ?>

    <?php // echo $form->field($model, 'money_order') ?>

    <?php // echo $form->field($model, 'card_no') ?>

    <?php // echo $form->field($model, 'from_ip') ?>

    <?php // echo $form->field($model, 'bank_code') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'sign_type') ?>

    <?php // echo $form->field($model, 'sign') ?>

    <?php // echo $form->field($model, 'oid_paybill') ?>

    <?php // echo $form->field($model, 'money_lianlian') ?>

    <?php // echo $form->field($model, 'settle_date') ?>

    <?php // echo $form->field($model, 'pay_type') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
