<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\invation\AssetConfigSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="asset-config-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'deposit_num') ?>

    <?= $form->field($model, 'deposit_min') ?>

    <?= $form->field($model, 'deposit_max') ?>

    <?= $form->field($model, 'invest_num') ?>

    <?php // echo $form->field($model, 'invest_min') ?>

    <?php // echo $form->field($model, 'invest_max') ?>

    <?php // echo $form->field($model, 'withdraw_num') ?>

    <?php // echo $form->field($model, 'withdraw_min') ?>

    <?php // echo $form->field($model, 'withdraw_max') ?>

    <?php // echo $form->field($model, 'ransom_num') ?>

    <?php // echo $form->field($model, 'ransom_min') ?>

    <?php // echo $form->field($model, 'ransom_max') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
