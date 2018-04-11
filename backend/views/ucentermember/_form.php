<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\UcenterMember;
use yii\helpers\ArrayHelper;
Use yii\helpers\Url;
use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $model common\models\UcenterMember */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ucenter-member-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?php
    echo '<label class="control-label">头像</label>';
    echo FileInput::widget([
        'model' => $model,
        'attribute' => 'person_face',
        'pluginOptions' => [

            'uploadExtraData' => [
                'album_id' => 20,
                'cat_id' => 'Nature'
            ],
            'maxFileCount' => 10,
            'initialCaption'=> $model->person_face,
            "showUpload"=> false,
        ]
    ]);
    ?>


    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idcard')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'real_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(UcenterMember::getArrayStatus()) ?>

    <?= $form->field($model, 'lock')->dropDownList(UcenterMember::getTypeStatus()) ?>

    <?= $form->field($model, 'type')->dropDownList(UcenterMember::getCusStatus(),['multiple'=>'multiple','style'=>'font-size:14px;']) ?>

    <?= $form->field($model, 'create_ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_area')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_channel')->textInput() ?>

    <?= $form->field($model, 'parent_member_id')->textInput() ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
