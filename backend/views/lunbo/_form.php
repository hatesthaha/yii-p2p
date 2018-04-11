<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use xj\ueditor\Ueditor;
/* @var $this yii\web\View */
/* @var $model common\models\cms\Lunbo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lunbo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'order')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'info')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'content')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'event_link')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'share_link')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'type')->dropDownList(\common\models\cms\Lunbo::type_labels()) ?>
    <?= $form->field($model, 'status')->dropDownList(\common\models\cms\Lunbo::status_labels()) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
